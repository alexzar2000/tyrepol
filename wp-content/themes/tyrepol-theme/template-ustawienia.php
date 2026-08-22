<?php
/**
 * Template Name: Ustawienia motywu (systemowe, nie usuwaj)
 *
 * Ten szablon istnieje wyłącznie po to, żeby pola ACF „Kontakt i social media” miały do czego
 * się przypiąć (lokalizacja: szablon strony) — ID tej strony nie jest znane z góry, bo motyw
 * tworzy ją sam przy pierwszej aktywacji (patrz inc/ustawienia.php). Strona ma status
 * „prywatna”, więc odwiedzający z zewnątrz i tak jej nie zobaczą — ten widok służy tylko na
 * wypadek, gdyby zalogowany administrator otworzył jej podgląd.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>
<section class="legal legal--top">
  <div class="legal__inner">
    <h1 class="legal__title"><?php tyrepol_esc_html_e('Ustawienia motywu', 'Theme settings'); ?></h1>
    <p><?php tyrepol_esc_html_e('Ta strona nie jest przeznaczona do publicznego wyświetlania — służy wyłącznie do przechowywania danych kontaktowych motywu w polach poniżej edytora. Nie usuwaj jej.', 'This page is not meant to be displayed publicly — it only stores the theme\'s contact data in the fields below the editor. Do not delete it.'); ?></p>
  </div>
</section>
<?php get_footer(); ?>
