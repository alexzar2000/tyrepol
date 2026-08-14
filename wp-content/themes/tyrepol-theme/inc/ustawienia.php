<?php
/**
 * „Ustawienia motywu” bez ACF PRO: ACF Free nie ma funkcji Options Page, więc zamiast niej
 * motyw sam tworzy (przy pierwszej aktywacji) zwykłą, ukrytą stronę WordPress o adresie
 * /ustawienia-motywu/ (status „prywatna” — widoczna tylko dla zalogowanych administratorów,
 * nigdy w menu ani w wynikach wyszukiwania) i do NIEJ podpina pola ACF (dane firmy, kontakt,
 * social media, RODO). Nie trzeba żadnej dodatkowej wtyczki.
 */

if (!defined('ABSPATH')) exit;

/**
 * Tworzy stronę „Ustawienia motywu”, jeśli jeszcze nie istnieje (uruchamiane raz, przy aktywacji
 * motywu — i dodatkowo sprawdzane przy każdym wejściu do panelu na wypadek gdyby strona została
 * przypadkiem usunięta).
 */
function tyrepol_maybe_create_settings_page() {
    $id = (int) get_option('tyrepol_settings_page_id');
    if ($id && get_post($id) && get_post($id)->post_type === 'page') return $id;

    $existing = get_page_by_path('ustawienia-motywu');
    if ($existing) {
        update_option('tyrepol_settings_page_id', $existing->ID);
        return $existing->ID;
    }

    $id = wp_insert_post([
        'post_title'   => __('Ustawienia motywu (nie usuwaj)', 'tyrepol'),
        'post_name'    => 'ustawienia-motywu',
        'post_type'    => 'page',
        'post_status'  => 'private',
        'post_content' => '',
        // Szablon strony musi być ustawiony, żeby pola ACF (patrz group_kontakt_options.json,
        // location: page_template) wiedziały, że mają się tu pokazać — ID tej strony nie jest
        // znane z góry (tworzy się dynamicznie), więc lokalizacja pól celuje w szablon, nie w ID.
        'meta_input'   => ['_wp_page_template' => 'template-ustawienia.php'],
    ]);

    if ($id && !is_wp_error($id)) {
        update_option('tyrepol_settings_page_id', $id);
        return $id;
    }
    return 0;
}
add_action('after_switch_theme', 'tyrepol_maybe_create_settings_page');
add_action('admin_init', 'tyrepol_maybe_create_settings_page');

/**
 * Skrót w menu panelu prowadzący prosto do edycji strony „Ustawienia motywu” — żeby nie trzeba
 * było jej szukać na liście Stron (ma status „prywatna”, więc normalnie jest mniej widoczna).
 */
add_action('admin_menu', function () {
    $id = (int) get_option('tyrepol_settings_page_id');
    if (!$id) return;

    add_menu_page(
        __('Ustawienia motywu', 'tyrepol'),
        __('Ustawienia motywu', 'tyrepol'),
        'edit_theme_options',
        'tyrepol-ustawienia',
        function () use ($id) {
            wp_safe_redirect(admin_url('post.php?post=' . $id . '&action=edit'));
            exit;
        },
        'dashicons-admin-generic',
        61
    );
});

/** ID strony „Ustawienia motywu” — używane przez tyrepol_opt() i pola ACF z location „ta strona”. */
function tyrepol_settings_page_id() {
    $id = (int) get_option('tyrepol_settings_page_id');
    return $id ?: tyrepol_maybe_create_settings_page();
}
