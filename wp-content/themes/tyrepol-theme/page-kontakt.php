<?php
/**
 * Template Name: Kontakt
 * Cała strona to jedna, wspólna cząstka „Kontakt” (template-parts/contact.php) w wariancie --top —
 * te same dane co na Stronie głównej / Opony / O firmie, bo wpisuje się je raz w Ustawienia motywu.
 */
if (!defined('ABSPATH')) exit;
get_header();
while (have_posts()) : the_post();
    // WP: baner nad sekcją "Kontakt" — opcjonalny, edytowalny z panelu (patrz pola pod edytorem
    // tej strony i acf-json/group_kontakt_baner.json). Nic się nie pokaże, dopóki admin nie
    // włączy przełącznika "Pokaż baner" i nie doda zdjęcia.
    if (get_field('kontakt_baner_pokaz')) {
        get_template_part('template-parts/banner', null, [
            'image' => get_field('kontakt_baner_zdjecie'),
            'title' => get_field('kontakt_baner_naglowek'),
            'desc'  => get_field('kontakt_baner_tekst'),
        ]);
    }
    tyrepol_contact_section(true);
endwhile;
get_footer();
