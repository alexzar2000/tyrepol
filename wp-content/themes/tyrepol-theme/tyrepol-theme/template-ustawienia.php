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
    <h1 class="legal__title"><?php esc_html_e('Ustawienia motywu', 'tyrepol'); ?></h1>
    <p><?php esc_html_e('Ta strona nie jest przeznaczona do publicznego wyświetlania — służy wyłącznie do przechowywania danych kontaktowych motywu w polach poniżej edytora. Nie usuwaj jej.', 'tyrepol'); ?></p>
  </div>
</section>
<?php get_footer(); ?>
