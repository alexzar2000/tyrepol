<?php
/**
 * Strona opcji ACF — dane firmy, kontakt i social media wpisywane RAZ, a używane wszędzie tam,
 * gdzie w statycznej wersji sekcja „Kontakt” powtarzała się identycznie na kilku podstronach
 * (Główna, O firmie, Saucerman, Opony, Kontakt). Wymaga wtyczki Advanced Custom Fields (PRO
 * ma wbudowane strony opcji; w wersji darmowej ACF trzeba dodatkowo doinstalować darmowy
 * dodatek „ACF: Options Page for ACF Free” — patrz plik INSTRUKCJA.md).
 */

if (!defined('ABSPATH')) exit;

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Ustawienia TyrePol', 'tyrepol'),
        'menu_title' => __('Ustawienia motywu', 'tyrepol'),
        'menu_slug'  => 'tyrepol-ustawienia',
        'capability' => 'edit_theme_options',
        'icon_url'   => 'dashicons-admin-generic',
        'position'   => 2,
        'redirect'   => false,
    ]);

    acf_add_options_sub_page([
        'page_title'  => __('Dane kontaktowe i social media', 'tyrepol'),
        'menu_title'  => __('Kontakt i social media', 'tyrepol'),
        'menu_slug'   => 'tyrepol-kontakt',
        'parent_slug' => 'tyrepol-ustawienia',
    ]);

    acf_add_options_sub_page([
        'page_title'  => __('Formularze (RODO, odbiorca wiadomości)', 'tyrepol'),
        'menu_title'  => __('Formularze', 'tyrepol'),
        'menu_slug'   => 'tyrepol-formularze',
        'parent_slug' => 'tyrepol-ustawienia',
    ]);
}
