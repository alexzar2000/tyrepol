<?php
/**
 * Typ wpisu „Pytanie FAQ” — pytania i odpowiedzi sekcji FAQ, WSPÓLNEJ dla wszystkich stron
 * (Strona główna, Opony, każda „Elastyczna strona”). Tak jak „Slajdy hero”, to osobny typ wpisu
 * zamiast stałej liczby pól ACF — żeby dało się dodać dowolnie dużo pytań, bez żadnego limitu.
 *
 * Tytuł wpisu = treść pytania. Zwykła treść wpisu (edytor pod tytułem) = treść odpowiedzi —
 * czysty tekst (bez pogrubień, linków itd. — dokładnie tak samo jak wcześniejsze pole tekstowe),
 * żeby wygląd FAQ na stronie pozostał identyczny niezależnie od tego, czy ktoś przez pomyłkę
 * doda formatowanie w edytorze. Obsługa 'page-attributes' daje wbudowane w WordPressa pole
 * „Kolejność” do ustawienia kolejności pytań (bez dodatkowego pola ACF).
 */

if (!defined('ABSPATH')) exit;

function tyrepol_register_pytanie_faq_cpt() {
    register_post_type('pytanie_faq', [
        'labels' => [
            'name'               => tyrepol_t('Pytania FAQ', 'FAQ questions'),
            'singular_name'      => tyrepol_t('Pytanie FAQ', 'FAQ question'),
            'add_new_item'       => tyrepol_t('Dodaj nowe pytanie', 'Add new question'),
            'edit_item'          => tyrepol_t('Edytuj pytanie', 'Edit question'),
            'all_items'          => tyrepol_t('Pytania FAQ (wspólne dla wszystkich stron)', 'FAQ questions (shared across all pages)'),
            'search_items'       => tyrepol_t('Szukaj pytań', 'Search questions'),
            'not_found'          => tyrepol_t('Nie znaleziono pytań', 'No questions found'),
            'menu_name'          => tyrepol_t('Pytania FAQ', 'FAQ questions'),
            'attributes'         => tyrepol_t('Kolejność pytania', 'Question order'),
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'show_in_nav_menus'   => false,
        'menu_icon'           => 'dashicons-editor-help',
        'menu_position'       => 22,
        'supports'            => ['title', 'editor', 'page-attributes'],
        'has_archive'         => false,
        'rewrite'             => false,
    ]);
}
add_action('init', 'tyrepol_register_pytanie_faq_cpt');

/**
 * Polylang — rejestrujemy „Pytania FAQ” jako TŁUMACZALNE, żeby każde pytanie miało osobną wersję
 * PL i EN. Wymaga też dopisania 'lang' do zapytania get_posts() w tyrepol_faq_section()
 * (inc/helpers.php), bo get_posts() domyślnie wyłącza automatyczne filtrowanie po języku.
 */
add_filter('pll_get_post_types', function ($post_types) {
    $post_types['pytanie_faq'] = 'pytanie_faq';
    return $post_types;
});

/**
 * Podpowiedź nad edytorem — Tytuł to pytanie, treść to odpowiedź (zwykły tekst).
 */
add_action('edit_form_after_title', function ($post) {
    if (!$post || $post->post_type !== 'pytanie_faq') return;
    echo '<div class="notice notice-info inline" style="margin:14px 0 0;padding:10px 14px;">'
        . '<p>' . tyrepol_esc_html('Tytuł u góry = treść pytania. Pole poniżej (edytor) = treść odpowiedzi — wpisz zwykły tekst, bez pogrubień/linków (formatowanie i tak nie pokaże się na stronie).', 'The title above = the question text. The field below (editor) = the answer text — enter plain text, without bold/links (formatting won\'t show on the page anyway).') . '</p>'
        . '<p>' . tyrepol_esc_html('Kolejność pytań na stronie ustawia pole „Kolejność” w panelu „Atrybuty” po prawej (mniejsza liczba = pytanie pokazuje się wyżej).', 'The question order on the page is set by the "Order" field in the "Attributes" panel on the right (a lower number = the question appears higher).') . '</p>'
        . '</div>';
});

/**
 * Kolumna z kolejnością na liście admina.
 */
add_filter('manage_pytanie_faq_posts_columns', function ($columns) {
    $columns['tyrepol_kolejnosc'] = tyrepol_t('Kolejność', 'Order');
    return $columns;
});
add_action('manage_pytanie_faq_posts_custom_column', function ($column, $post_id) {
    if ($column === 'tyrepol_kolejnosc') {
        echo esc_html(get_post_field('menu_order', $post_id));
    }
}, 10, 2);

/**
 * Domyślne sortowanie listy admina wg pola „Kolejność” — żeby lista od razu odzwierciedlała
 * kolejność pytań na stronie.
 */
add_filter('request', function ($vars) {
    if (is_admin() && ($vars['post_type'] ?? '') === 'pytanie_faq' && empty($vars['orderby'])) {
        $vars['orderby'] = 'menu_order';
        $vars['order'] = 'ASC';
    }
    return $vars;
});
