<?php
/**
 * Typ wpisu „Opona” (jeden wpis = jeden rozmiar/wariant opony w katalogu) + taksonomie
 * używane do filtrowania katalogu (marka, oś montażu, sezon, typ pojazdu) — odpowiednik
 * dawnej tablicy TIRES zaszytej w assets/script.js, tylko że teraz edytowalny w panelu.
 */

if (!defined('ABSPATH')) exit;

function tyrepol_register_opona_cpt() {

    register_post_type('opona', [
        'labels' => [
            'name'               => __('Opony', 'tyrepol'),
            'singular_name'      => __('Opona', 'tyrepol'),
            'add_new_item'       => __('Dodaj nową oponę', 'tyrepol'),
            'edit_item'          => __('Edytuj oponę', 'tyrepol'),
            'all_items'          => __('Wszystkie opony', 'tyrepol'),
            'search_items'       => __('Szukaj opon', 'tyrepol'),
            'not_found'          => __('Nie znaleziono opon', 'tyrepol'),
            'menu_name'          => __('Katalog opon', 'tyrepol'),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-marker',
        // 'page-attributes' daje wbudowane w WordPressa pole „Kolejność” — ustala kolejność
        // wierszy w tabeli rozmiarów na stronie produktu (patrz single-opona.php), gdy kilka
        // wpisów ma ten sam „Wzór bieżnika” + „Markę” (czyli jest tym samym modelem opony).
        'supports'           => ['title', 'thumbnail', 'page-attributes'],
        'has_archive'        => false, // archiwum zastępuje dedykowana strona opony.html (page-opony.php)
        'rewrite'            => ['slug' => 'opony', 'with_front' => false],
        'show_in_menu'       => true,
    ]);

    $taxonomies = [
        'marka-opony' => [
            'label'  => __('Marka', 'tyrepol'),
            'plural' => __('Marki', 'tyrepol'),
            'terms'  => [
                'saucerman' => __('Saucerman', 'tyrepol'),
                'falken'    => __('Falken', 'tyrepol'),
            ],
        ],
        'os-montazu' => [
            'label'  => __('Oś montażu', 'tyrepol'),
            'plural' => __('Osie montażu', 'tyrepol'),
            'terms'  => [
                'steer'   => __('Steer (kierowana)', 'tyrepol'),
                'drive'   => __('Drive (napędowa)', 'tyrepol'),
                'trailer' => __('Trailer (naczepy)', 'tyrepol'),
                'on-off'  => __('On/Off (uniwersalna)', 'tyrepol'),
                'winter'  => __('Winter (zimowa)', 'tyrepol'),
                'a-p'     => __('A/P', 'tyrepol'),
            ],
        ],
        'sezon-opony' => [
            'label'  => __('Sezon', 'tyrepol'),
            'plural' => __('Sezony', 'tyrepol'),
            'terms'  => [
                'lato'       => __('Lato', 'tyrepol'),
                'zima'       => __('Zima', 'tyrepol'),
                'caloroczne' => __('Całoroczne', 'tyrepol'),
            ],
        ],
        'typ-pojazdu' => [
            'label'  => __('Typ pojazdu', 'tyrepol'),
            'plural' => __('Typy pojazdów', 'tyrepol'),
            'terms'  => [
                'ciezarowe' => __('Ciężarowe', 'tyrepol'),
                'dostawcze' => __('Dostawcze', 'tyrepol'),
            ],
        ],
    ];

    foreach ($taxonomies as $slug => $tax) {
        register_taxonomy($slug, ['opona'], [
            'labels' => [
                'name'          => $tax['plural'],
                'singular_name' => $tax['label'],
            ],
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => ['slug' => $slug],
        ]);
    }

    // Zapamiętujemy listę taksonomii + ich domyślne terminy, żeby jednorazowo utworzyć je
    // po aktywacji motywu (patrz tyrepol_maybe_seed_taxonomies poniżej).
    set_transient('tyrepol_taxonomies_definition', $taxonomies, DAY_IN_SECONDS);

    // Taksonomia „Cechy opon” — REJESTR dodatkowych parametrów (poza pkt. wyżej), NIEOGRANICZONY
    // co do liczby (bez seedowania domyślnych terminów — admin dodaje własne w Opony → Cechy opon).
    // Sama nazwa/ikona/sposób wyświetlania siedzi na terminie (patrz acf-json/group_cecha_opony.json),
    // a wartość dla konkretnej opony wpisuje się w osobnym boksie na ekranie edycji wpisu
    // (patrz inc/cechy-opony.php) — dzięki temu nazwa i ikona parametru są spójne dla wszystkich
    // opon naraz, zamiast wpisywać je osobno przy każdej sztuce.
    register_taxonomy('cecha-opony', ['opona'], [
        'labels' => [
            'name'          => __('Cechy opon', 'tyrepol'),
            'singular_name' => __('Cecha opony', 'tyrepol'),
            'add_new_item'  => __('Dodaj nowy parametr', 'tyrepol'),
            'edit_item'     => __('Edytuj parametr', 'tyrepol'),
            'search_items'  => __('Szukaj parametrów', 'tyrepol'),
            'not_found'     => __('Nie znaleziono parametrów', 'tyrepol'),
            'menu_name'     => __('Cechy opon', 'tyrepol'),
        ],
        'hierarchical'      => false,
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => false,
        'show_in_rest'      => true,
        'rewrite'           => false,
    ]);
}
add_action('init', 'tyrepol_register_opona_cpt');

/**
 * Jednorazowe utworzenie domyślnych terminów (marka/oś/sezon/typ pojazdu), żeby po instalacji
 * motywu filtry w panelu i na katalogu nie były puste. Nie nadpisuje terminów, które już istnieją.
 */
function tyrepol_maybe_seed_taxonomies() {
    if (get_option('tyrepol_taxonomies_seeded')) return;

    $taxonomies = get_transient('tyrepol_taxonomies_definition');
    if (!$taxonomies) return;

    foreach ($taxonomies as $slug => $tax) {
        foreach ($tax['terms'] as $term_slug => $term_name) {
            if (!term_exists($term_slug, $slug)) {
                wp_insert_term($term_name, $slug, ['slug' => $term_slug]);
            }
        }
    }

    update_option('tyrepol_taxonomies_seeded', 1);
}
add_action('init', 'tyrepol_maybe_seed_taxonomies', 20);

/**
 * Etykieta wzoru bieżnika i rozmiaru w liście admina — ułatwia rozpoznanie, które wpisy należą
 * do tego samego modelu, bez otwierania każdego z osobna.
 */
add_filter('manage_opona_posts_columns', function ($columns) {
    $columns['wzor_bieznika'] = __('Wzór bieżnika (model)', 'tyrepol');
    $columns['rozmiar'] = __('Rozmiar', 'tyrepol');
    return $columns;
});
add_action('manage_opona_posts_custom_column', function ($column, $post_id) {
    if (!function_exists('get_field')) return;
    if ($column === 'wzor_bieznika') {
        echo esc_html(get_field('wzor_bieznika', $post_id));
    }
    if ($column === 'rozmiar') {
        echo esc_html(get_field('rozmiar', $post_id));
    }
}, 10, 2);

/**
 * Przypomnienie nad polami przy dodawaniu/edycji opony: wpisy o tym samym „Wzorze bieżnika”
 * i tej samej „Marce” łączą się automatycznie w JEDNĄ kartę produktu w katalogu i JEDNĄ stronę
 * szczegółów z tabelą wszystkich rozmiarów (dokładnie jak w dawnej wersji statycznej) — nie
 * trzeba nic dodatkowo zaznaczać, wystarczy wpisać identyczny „Wzór bieżnika” w każdym wariancie.
 * Pole „Kolejność” (panel „Atrybuty” po prawej) ustala kolejność wierszy w tej tabeli.
 */
add_action('edit_form_after_title', function ($post) {
    if (!$post || $post->post_type !== 'opona') return;
    echo '<div class="notice notice-info inline" style="margin:14px 0 0;padding:10px 14px;">'
        . '<p>' . esc_html__('Jeden wpis = jeden rozmiar. Kilka wpisów z TAKIM SAMYM „Wzorem bieżnika” i TĄ SAMĄ „Marką” (po prawej) automatycznie łączy się w jedną kartę w katalogu i jedną stronę produktu z tabelą wszystkich rozmiarów — nic więcej nie trzeba zaznaczać.', 'tyrepol') . '</p>'
        . '<p>' . esc_html__('Kolejność wierszy w tabeli rozmiarów ustawia pole „Kolejność” w panelu „Atrybuty” po prawej (mniejsza liczba = wyżej).', 'tyrepol') . '</p>'
        . '</div>';
});

/**
 * Duplikowanie opony jednym kliknięciem — szybkie tworzenie kolejnego wariantu (np. innego
 * rozmiaru tego samego modelu): kopiuje tytuł, zdjęcie, WSZYSTKIE pola ACF (w tym „Wzór bieżnika”,
 * żeby kopia od razu należała do tego samego modelu — wystarczy zmienić „Rozmiar”) oraz wszystkie
 * wypełnione „Dodatkowe parametry” (patrz inc/cechy-opony.php), a także taksonomie (marka, oś
 * montażu, sezon, typ pojazdu). Nowa kopia trafia jako szkic, żeby nic nie opublikowało się
 * przypadkiem bez sprawdzenia.
 */
add_filter('post_row_actions', function ($actions, $post) {
    if (!$post || $post->post_type !== 'opona' || !current_user_can('edit_posts')) return $actions;

    $url = wp_nonce_url(
        admin_url('admin.php?action=tyrepol_duplikuj_opone&post=' . $post->ID),
        'tyrepol_duplikuj_opone_' . $post->ID
    );
    $actions['tyrepol_duplikuj'] = '<a href="' . esc_url($url) . '">' . esc_html__('Duplikuj', 'tyrepol') . '</a>';
    return $actions;
}, 10, 2);

add_action('admin_action_tyrepol_duplikuj_opone', function () {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_die(esc_html__('Brak uprawnień do tej operacji.', 'tyrepol'));
    }
    check_admin_referer('tyrepol_duplikuj_opone_' . $post_id);

    $original = get_post($post_id);
    if (!$original || $original->post_type !== 'opona') {
        wp_die(esc_html__('Nie znaleziono opony do duplikowania.', 'tyrepol'));
    }

    $new_id = wp_insert_post([
        'post_title'   => $original->post_title . ' ' . __('(kopia)', 'tyrepol'),
        'post_content' => $original->post_content,
        'post_excerpt' => $original->post_excerpt,
        'post_status'  => 'draft',
        'post_type'    => 'opona',
        'post_author'  => get_current_user_id(),
        'menu_order'   => $original->menu_order,
    ], true);

    if (is_wp_error($new_id)) {
        wp_die(esc_html($new_id->get_error_message()));
    }

    // Kopiujemy WSZYSTKIE meta (pola ACF — Wzór bieżnika/Rozmiar, oraz wartości „Dodatkowych
    // parametrów” z inc/cechy-opony.php, oraz zdjęcie wyróżniające — to też zwykłe post meta).
    foreach (get_post_meta($post_id) as $key => $values) {
        if (in_array($key, ['_edit_lock', '_edit_last'], true)) continue;
        foreach ($values as $value) {
            add_post_meta($new_id, $key, maybe_unserialize($value));
        }
    }

    // Kopiujemy przypisania do taksonomii katalogowych (marka, oś montażu, sezon, typ pojazdu).
    foreach (get_object_taxonomies('opona') as $taxonomy) {
        if ($taxonomy === 'cecha-opony') continue; // to rejestr definicji, nie przypisanie do opony
        $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        if (!is_wp_error($terms) && !empty($terms)) {
            wp_set_object_terms($new_id, $terms, $taxonomy);
        }
    }

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
    exit;
});
