<?php
/**
 * Typ wpisu „Slajd hero” — slajdy karuzeli (Swiper) na Stronie głównej.
 *
 * Dlaczego osobny typ wpisu, a nie pola ACF bezpośrednio na Stronie głównej: ACF Free nie ma
 * pola Repeater, więc „na sztywno” dało się zrobić tylko ograniczoną liczbę slajdów (np. 4).
 * Typ wpisu w WordPressie jest za to z natury nieograniczony — można dodać dowolną liczbę
 * slajdów, dokładnie jak z wpisami na blogu. Obsługa 'page-attributes' daje dodatkowo gotowe,
 * wbudowane w WordPressa pole „Kolejność” (liczba — mniejsza wyżej), więc kolejność slajdów też
 * nie wymaga żadnego dodatkowego pola ACF.
 */

if (!defined('ABSPATH')) exit;

function tyrepol_register_slajd_hero_cpt() {
    register_post_type('slajd_hero', [
        'labels' => [
            'name'               => tyrepol_t('Slajdy hero', 'Hero slides'),
            'singular_name'      => tyrepol_t('Slajd hero', 'Hero slide'),
            'add_new_item'       => tyrepol_t('Dodaj nowy slajd', 'Add new slide'),
            'edit_item'          => tyrepol_t('Edytuj slajd', 'Edit slide'),
            'all_items'          => tyrepol_t('Slajdy hero (Strona główna)', 'Hero slides (Homepage)'),
            'search_items'       => tyrepol_t('Szukaj slajdów', 'Search slides'),
            'not_found'          => tyrepol_t('Nie znaleziono slajdów', 'No slides found'),
            'menu_name'          => tyrepol_t('Slajdy hero', 'Hero slides'),
            'attributes'         => tyrepol_t('Kolejność slajdu', 'Slide order'),
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'show_in_nav_menus'   => false,
        'menu_icon'           => 'dashicons-images-alt2',
        'menu_position'       => 21,
        'supports'            => ['title', 'thumbnail', 'page-attributes'],
        'has_archive'         => false,
        'rewrite'             => false,
    ]);
}
add_action('init', 'tyrepol_register_slajd_hero_cpt');

/**
 * Polylang — rejestrujemy „Slajdy hero” jako TŁUMACZALNE, żeby każdy slajd miał osobną wersję
 * PL i EN (inaczej wszystkie slajdy pokazywałyby się identycznie w obu językach). Wymaga też
 * dopisania 'lang' do zapytania get_posts() w front-page.php, bo get_posts() domyślnie wyłącza
 * automatyczne filtrowanie WordPressa/Polylang po języku (suppress_filters=true).
 */
add_filter('pll_get_post_types', function ($post_types) {
    $post_types['slajd_hero'] = 'slajd_hero';
    return $post_types;
});

/**
 * Przypomnienie o zalecanym rozmiarze zdjęcia — pokazuje się nad polami przy dodawaniu/edycji
 * slajdu, tuż pod tytułem. Zdjęcie ustawia się jako zwykły „Obrazek wyróżniający” (panel po
 * prawej stronie ekranu edycji), więc to nie jest osobne pole ACF.
 */
add_action('edit_form_after_title', function ($post) {
    if (!$post || $post->post_type !== 'slajd_hero') return;
    echo '<div class="notice notice-info inline" style="margin:14px 0 0;padding:10px 14px;">'
        . '<p>' . tyrepol_esc_html('Zdjęcie slajdu ustaw jako „Obrazek wyróżniający” (panel po prawej). Zalecany rozmiar: 1400 × 1000 px (proporcja ok. 1,4:1) — zdjęcie o innych proporcjach zostanie przycięte przez CSS, żeby wypełnić kadr.', 'Set the slide image as the "Featured image" (panel on the right). Recommended size: 1400 × 1000 px (ratio approx. 1.4:1) — an image with different proportions will be cropped by CSS to fill the frame.') . '</p>'
        . '<p>' . tyrepol_esc_html('Kolejność slajdów w karuzeli ustawia pole „Kolejność” w panelu „Atrybuty” po prawej (mniejsza liczba = slajd pokazuje się wcześniej).', 'The slide order in the carousel is set by the "Order" field in the "Attributes" panel on the right (a lower number = the slide appears earlier).') . '</p>'
        . '</div>';
});

/**
 * Kolumny listy admina: miniatura + kolejność, żeby łatwo zobaczyć układ slajdów bez wchodzenia
 * w każdy z osobna.
 */
add_filter('manage_slajd_hero_posts_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['tyrepol_miniatura'] = tyrepol_t('Zdjęcie', 'Image');
        }
    }
    $new['tyrepol_kolejnosc'] = tyrepol_t('Kolejność', 'Order');
    return $new;
});
add_action('manage_slajd_hero_posts_custom_column', function ($column, $post_id) {
    if ($column === 'tyrepol_miniatura') {
        echo has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, [60, 40]) : '—';
    }
    if ($column === 'tyrepol_kolejnosc') {
        echo esc_html(get_post_field('menu_order', $post_id));
    }
}, 10, 2);

/**
 * Domyślne sortowanie listy admina wg pola „Kolejność” — żeby lista od razu odzwierciedlała
 * kolejność slajdów w karuzeli na stronie.
 */
add_filter('request', function ($vars) {
    if (is_admin() && ($vars['post_type'] ?? '') === 'slajd_hero' && empty($vars['orderby'])) {
        $vars['orderby'] = 'menu_order';
        $vars['order'] = 'ASC';
    }
    return $vars;
});
