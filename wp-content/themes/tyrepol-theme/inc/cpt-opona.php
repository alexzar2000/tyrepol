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
            'name'               => tyrepol_t('Opony', 'Tyres'),
            'singular_name'      => tyrepol_t('Opona', 'Tyre'),
            'add_new_item'       => tyrepol_t('Dodaj nową oponę', 'Add new tyre'),
            'edit_item'          => tyrepol_t('Edytuj oponę', 'Edit tyre'),
            'all_items'          => tyrepol_t('Wszystkie opony', 'All tyres'),
            'search_items'       => tyrepol_t('Szukaj opon', 'Search tyres'),
            'not_found'          => tyrepol_t('Nie znaleziono opon', 'No tyres found'),
            'menu_name'          => tyrepol_t('Katalog opon', 'Tyre catalogue'),
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
            'label'  => tyrepol_t('Marka', 'Brand'),
            'plural' => tyrepol_t('Marki', 'Brands'),
            'terms'  => [
                'saucerman' => tyrepol_t('Saucerman', 'Saucerman'),
                'falken'    => tyrepol_t('Falken', 'Falken'),
            ],
        ],
        'os-montazu' => [
            'label'  => tyrepol_t('Oś montażu', 'Axle position'),
            'plural' => tyrepol_t('Osie montażu', 'Axle positions'),
            'terms'  => [
                'steer'   => tyrepol_t('Steer (kierowana)', 'Steer'),
                'drive'   => tyrepol_t('Drive (napędowa)', 'Drive'),
                'trailer' => tyrepol_t('Trailer (naczepy)', 'Trailer'),
                'on-off'  => tyrepol_t('On/Off (uniwersalna)', 'On/Off (universal)'),
                'winter'  => tyrepol_t('Winter (zimowa)', 'Winter'),
                'a-p'     => tyrepol_t('A/P', 'A/P'),
            ],
        ],
        'sezon-opony' => [
            'label'  => tyrepol_t('Sezon', 'Season'),
            'plural' => tyrepol_t('Sezony', 'Seasons'),
            'terms'  => [
                'lato'       => tyrepol_t('Lato', 'Summer'),
                'zima'       => tyrepol_t('Zima', 'Winter'),
                'caloroczne' => tyrepol_t('Całoroczne', 'All-season'),
            ],
        ],
        'typ-pojazdu' => [
            'label'  => tyrepol_t('Typ pojazdu', 'Vehicle type'),
            'plural' => tyrepol_t('Typy pojazdów', 'Vehicle types'),
            'terms'  => [
                'ciezarowe' => tyrepol_t('Ciężarowe', 'Trucks'),
                'dostawcze' => tyrepol_t('Dostawcze', 'Vans'),
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
            'name'          => tyrepol_t('Cechy opon', 'Tyre features'),
            'singular_name' => tyrepol_t('Cecha opony', 'Tyre feature'),
            'add_new_item'  => tyrepol_t('Dodaj nowy parametr', 'Add new parameter'),
            'edit_item'     => tyrepol_t('Edytuj parametr', 'Edit parameter'),
            'search_items'  => tyrepol_t('Szukaj parametrów', 'Search parameters'),
            'not_found'     => tyrepol_t('Nie znaleziono parametrów', 'No parameters found'),
            'menu_name'     => tyrepol_t('Cechy opon', 'Tyre features'),
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
 * Polylang — rejestrujemy CPT „Opona” i jej taksonomie katalogowe jako TŁUMACZALNE, żeby
 * działało od razu po aktywacji wtyczki, bez ręcznego zaznaczania w Języki → Ustawienia →
 * Typy treści (Polylang bez tego traktowałby wszystkie opony jako „bez przypisanego języka”,
 * wspólne dla PL i EN — a nam zależy na osobnej wersji angielskiej każdego wpisu).
 */
add_filter('pll_get_post_types', function ($post_types) {
    $post_types['opona'] = 'opona';
    return $post_types;
});
add_filter('pll_get_taxonomies', function ($taxonomies) {
    foreach (['marka-opony', 'os-montazu', 'sezon-opony', 'typ-pojazdu', 'cecha-opony'] as $tax) {
        $taxonomies[$tax] = $tax;
    }
    return $taxonomies;
});

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
    $columns['wzor_bieznika'] = tyrepol_t('Wzór bieżnika (model)', 'Tread pattern (model)');
    $columns['rozmiar'] = tyrepol_t('Rozmiar', 'Size');
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
        . '<p>' . tyrepol_esc_html('Jeden wpis = jeden rozmiar. Kilka wpisów z TAKIM SAMYM „Wzorem bieżnika” i TĄ SAMĄ „Marką” (po prawej) automatycznie łączy się w jedną kartę w katalogu i jedną stronę produktu z tabelą wszystkich rozmiarów — nic więcej nie trzeba zaznaczać.', 'One entry = one size. Several entries with the SAME "Tread pattern" and the SAME "Brand" (on the right) automatically merge into one catalogue card and one product page with a table of all sizes — nothing else needs to be set.') . '</p>'
        . '<p>' . tyrepol_esc_html('Kolejność wierszy w tabeli rozmiarów ustawia pole „Kolejność” w panelu „Atrybuty” po prawej (mniejsza liczba = wyżej).', 'The row order in the size table is set by the "Order" field in the "Attributes" panel on the right (a lower number = higher up).') . '</p>'
        . '</div>';

    if (!function_exists('pll_get_post_language')) {
        echo '<div class="notice notice-warning inline" style="margin:10px 0 0;padding:10px 14px;">'
            . '<p>' . tyrepol_esc_html('Wtyczka Polylang nie jest aktywna — wersja angielska NIE utworzy się automatycznie po zapisaniu tej opony. Zainstaluj i aktywuj Polylang (patrz punkt 1 i 8 instrukcji), żeby to zadziałało.', 'The Polylang plugin is not active — the English version will NOT be created automatically when you save this tyre. Install and activate Polylang (see points 1 and 8 of the instructions) for this to work.') . '</p>'
            . '</div>';
    }
});

/**
 * Duplikowanie opony jednym kliknięciem — szybkie tworzenie kolejnego wariantu (np. innego
 * rozmiaru tego samego modelu): kopiuje tytuł, zdjęcie, WSZYSTKIE pola ACF (w tym „Wzór bieżnika”,
 * żeby kopia od razu należała do tego samego modelu — wystarczy zmienić „Rozmiar”) oraz wszystkie
 * wypełnione „Dodatkowe parametry” (patrz inc/cechy-opony.php), a także taksonomie (marka, oś
 * montażu, sezon, typ pojazdu). Nowa kopia trafia jako szkic, żeby nic nie opublikowało się
 * przypadkiem bez sprawdzenia.
 */
/**
 * Kopiuje WSZYSTKIE dane jednej opony na drugą (już istniejący, pusty wpis „opona”) — wspólna
 * funkcja używana zarówno przy ręcznym duplikowaniu (patrz niżej), jak i przy automatycznym
 * tworzeniu wersji angielskiej (patrz tyrepol_auto_utworz_tlumaczenie_en niżej). Kopiuje WSZYSTKIE
 * pola ACF (w tym „Wzór bieżnika”/„Rozmiar”), wartości „Dodatkowych parametrów” z rejestru „Cechy
 * opon” (patrz inc/cechy-opony.php) oraz zdjęcie wyróżniające (to też zwykłe post meta), a także
 * taksonomie katalogowe (marka, oś montażu, sezon, typ pojazdu) — BEZ „cecha-opony”, bo to rejestr
 * definicji parametrów, a nie przypisanie do konkretnej opony.
 */
function tyrepol_kopiuj_dane_opony($z_id, $do_id) {
    foreach (get_post_meta($z_id) as $key => $values) {
        if (in_array($key, ['_edit_lock', '_edit_last'], true)) continue;
        foreach ($values as $value) {
            add_post_meta($do_id, $key, maybe_unserialize($value));
        }
    }

    foreach (get_object_taxonomies('opona') as $taxonomy) {
        if ($taxonomy === 'cecha-opony') continue;
        $terms = wp_get_object_terms($z_id, $taxonomy, ['fields' => 'ids']);
        if (!is_wp_error($terms) && !empty($terms)) {
            wp_set_object_terms($do_id, $terms, $taxonomy);
        }
    }
}

/**
 * Duplikowanie opony jednym kliknięciem — szybkie tworzenie kolejnego wariantu (np. innego
 * rozmiaru tego samego modelu). Nowa kopia trafia jako szkic, żeby nic nie opublikowało się
 * przypadkiem bez sprawdzenia.
 */
add_filter('post_row_actions', function ($actions, $post) {
    if (!$post || $post->post_type !== 'opona' || !current_user_can('edit_posts')) return $actions;

    $url = wp_nonce_url(
        admin_url('admin.php?action=tyrepol_duplikuj_opone&post=' . $post->ID),
        'tyrepol_duplikuj_opone_' . $post->ID
    );
    $actions['tyrepol_duplikuj'] = '<a href="' . esc_url($url) . '">' . tyrepol_esc_html('Duplikuj', 'Duplicate') . '</a>';
    return $actions;
}, 10, 2);

add_action('admin_action_tyrepol_duplikuj_opone', function () {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_die(tyrepol_esc_html('Brak uprawnień do tej operacji.', 'You don\'t have permission to perform this action.'));
    }
    check_admin_referer('tyrepol_duplikuj_opone_' . $post_id);

    $original = get_post($post_id);
    if (!$original || $original->post_type !== 'opona') {
        wp_die(tyrepol_esc_html('Nie znaleziono opony do duplikowania.', 'Tyre to duplicate not found.'));
    }

    $new_id = wp_insert_post([
        'post_title'   => $original->post_title . ' ' . tyrepol_t('(kopia)', '(copy)'),
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

    tyrepol_kopiuj_dane_opony($post_id, $new_id);

    // Duplikat to zwykła kopia PO POLSKU (nie tłumaczenie) — jeśli Polylang jest aktywny, zapisz
    // mu ten sam język co oryginał, inaczej automat niżej mógłby potraktować go jako osobny wpis
    // bez języka i spróbować dorobić mu jeszcze wersję angielską.
    if (function_exists('pll_get_post_language') && function_exists('pll_set_post_language')) {
        $lang = pll_get_post_language($post_id);
        if ($lang) pll_set_post_language($new_id, $lang);
    }

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
    exit;
});

/**
 * Polylang — automatyczne tworzenie wersji angielskiej przy pierwszym zapisaniu opony po polsku,
 * żeby NIE trzeba było klikać „+” przy fladze EN w panelu „Języki”. Wymaga aktywnej wtyczki
 * Polylang (bez niej funkcje pll_* nie istnieją i ten kod nic nie robi — bezpiecznie się wyłącza).
 *
 * Jak to działa: gdy zapisujesz oponę i nie ma ona jeszcze przypisanego języka, zakładamy że to
 * PL (domyślny język motywu) i od razu tworzymy jej kopię — dokładnie tymi samymi danymi co
 * PL (tytuł, wzór bieżnika, rozmiar, wszystkie dodatkowe parametry, zdjęcie, marka/oś/sezon/typ) —
 * oznaczoną jako angielska i połączoną z polską jako tłumaczenie. Kopia trafia jako SZKIC, więc
 * nic nie opublikuje się nieprzetłumaczone — wystarczy ją otworzyć (przycisk przy fladze EN w
 * panelu „Języki” przy edycji polskiej opony) i podmienić teksty na angielskie.
 * Uruchamia się TYLKO RAZ na wpis (jeśli angielska wersja już istnieje — np. usunięto powiązanie
 * albo utworzono ją ręcznie — nic więcej się nie dzieje).
 */
add_action('save_post_opona', function ($post_id) {
    static $tworzenie_w_toku = false;
    if ($tworzenie_w_toku) return; // zabezpieczenie przed nieskończoną pętlą (patrz niżej)

    if (
        !function_exists('pll_get_post_language')
        || !function_exists('pll_set_post_language')
        || !function_exists('pll_save_post_translations')
        || !function_exists('pll_get_post')
    ) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $lang = pll_get_post_language($post_id);
    if (!$lang) {
        $lang = function_exists('pll_default_language') ? (pll_default_language() ?: 'pl') : 'pl';
        pll_set_post_language($post_id, $lang);
    }
    if ($lang !== 'pl') return; // klonujemy tylko PL -> EN, nigdy w drugą stronę

    if (pll_get_post($post_id, 'en')) return; // wersja angielska już istnieje — nic nie rób

    $original = get_post($post_id);
    if (!$original) return;

    // Blokujemy ponowne wejście na czas tworzenia kopii — wp_insert_post() niżej synchronicznie
    // odpala ten sam hook dla NOWEGO wpisu (bez tego doszłoby do nieskończonej pętli klonowania).
    $tworzenie_w_toku = true;

    $en_id = wp_insert_post([
        'post_title'   => $original->post_title,
        'post_content' => $original->post_content,
        'post_excerpt' => $original->post_excerpt,
        'post_status'  => 'draft',
        'post_type'    => 'opona',
        'post_author'  => $original->post_author,
        'menu_order'   => $original->menu_order,
    ], true);

    if (!is_wp_error($en_id)) {
        tyrepol_kopiuj_dane_opony($post_id, $en_id);
        pll_set_post_language($en_id, 'en');
        pll_save_post_translations(['pl' => $post_id, 'en' => $en_id]);
    }

    $tworzenie_w_toku = false;
}, 20);
