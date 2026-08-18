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
        'supports'           => ['title', 'thumbnail'],
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
 * Etykieta wagi/rozmiaru w liście admina — ułatwia rozpoznanie wpisu na liście „Wszystkie opony”.
 */
add_filter('manage_opona_posts_columns', function ($columns) {
    $columns['rozmiar'] = __('Rozmiar', 'tyrepol');
    return $columns;
});
add_action('manage_opona_posts_custom_column', function ($column, $post_id) {
    if ($column === 'rozmiar' && function_exists('get_field')) {
        echo esc_html(get_field('rozmiar', $post_id));
    }
}, 10, 2);
