<?php
/**
 * Template Name: Kontakt
 * Cała strona to jedna, wspólna cząstka „Kontakt” (template-parts/contact.php) w wariancie --top —
 * te same dane co na Stronie głównej / Opony / O firmie, bo wpisuje się je raz w Ustawienia motywu.
 */
if (!defined('ABSPATH')) exit;
get_header();
while (have_posts()) : the_post();
    tyrepol_contact_section(true);
endwhile;
get_footer();
