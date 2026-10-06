<?php
/**
 * Stopka + elementy globalne (przycisk „do góry”, mobilny przycisk wyceny, popup wyceny,
 * komunikat potwierdzający) — identyczne na każdej podstronie, tak jak w wersji statycznej.
 */
if (!defined('ABSPATH')) exit;
?>
  </main>

  <footer class="footer">
    <div class="footer__inner">
      <nav class="footer__links">
        <?php
        if (has_nav_menu('footer')) {
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'link_before'    => '',
                'depth'          => 1,
                'walker'         => new class extends Walker_Nav_Menu {
                    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                        $output .= '<a class="footer__link" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
                    }
                    public function end_el(&$output, $item, $depth = 0, $args = null) {}
                },
            ]);
        } else {
            $privacy = get_page_by_path('polityka-prywatnosci');
            $cookies = get_page_by_path('polityka-cookies');
            if ($privacy) printf('<a class="footer__link" href="%s">%s</a>', esc_url(get_permalink($privacy)), tyrepol_esc_html('Polityka prywatności', 'Privacy Policy'));
            if ($cookies) printf('<a class="footer__link" href="%s">%s</a>', esc_url(get_permalink($cookies)), tyrepol_esc_html('Polityka cookies', 'Cookie Policy'));
        }
        ?>
      </nav>
      <p class="footer__copy">&copy; <?php echo esc_html(date('Y')); ?> <?php echo esc_html(tyrepol_opt('firma_nazwa', 'TyrePol')); ?>. <?php tyrepol_esc_html_e('Wszelkie prawa zastrzeżone.', 'All rights reserved.'); ?></p>
    </div>
  </footer>

  <button id="scrolltop" class="scrolltop" type="button" aria-label="<?php tyrepol_esc_attr_e('Przewiń do góry strony', 'Scroll to top'); ?>">
    <svg class="scrolltop__ring" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
    <span class="scrolltop__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"></path></svg>
    </span>
  </button>

  <button class="mobile-quote-cta" type="button" data-modal-open="quote-modal"><?php tyrepol_esc_html_e('Darmowa wycena', 'Free quote'); ?></button>

  <div class="modal" id="quote-modal" aria-hidden="true">
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
      <button class="modal__close" type="button" data-modal-close aria-label="<?php tyrepol_esc_attr_e('Zamknij okno', 'Close window'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>

      <h2 class="modal__title" id="quote-modal-title"><?php tyrepol_esc_html_e('Darmowa wycena', 'Free quote'); ?></h2>
      <p class="modal__desc"><?php tyrepol_esc_html_e('Podaj kilka informacji, a przygotujemy dla Ciebie bezpłatną wycenę.', 'Give us a few details and we\'ll prepare a free quote for you.'); ?></p>

      <?php
      // WP: formularz „Darmowa wycena” z wtyczki Contact Form 7 (szukany po tytule, patrz
      // tyrepol_cf7_form() w inc/helpers.php). Lista rozmiarów w polu [select* tyre-size]
      // jest uzupełniana automatycznie z bazy opon (filtr wpcf7_form_tag).
      echo tyrepol_cf7_form(tyrepol_t('Darmowa wycena', 'Free quote'), 'Darmowa wycena', 'modal__form');
      ?>
    </div>
  </div>

  <!-- WP: lightbox ze zdjęciem opony — po kliknięciu na zdjęcie na stronie produktu (patrz
       .tire-detail__img-trigger w single-opona.php) otwiera się to okno z dużym zdjęciem;
       zamyka się krzyżykiem, klikiem w tło albo klawiszem Esc — dokładnie tak samo jak popup
       „Darmowa wycena” wyżej (ten sam mechanizm w initModal(), patrz assets/script.js). -->
  <div class="modal modal--lightbox" id="image-lightbox" aria-hidden="true">
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__dialog modal__dialog--lightbox" role="dialog" aria-modal="true" aria-label="<?php tyrepol_esc_attr_e('Powiększone zdjęcie opony', 'Enlarged tyre photo'); ?>">
      <button class="modal__close modal__close--lightbox" type="button" data-modal-close aria-label="<?php tyrepol_esc_attr_e('Zamknij okno', 'Close window'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
      <img class="modal__lightbox-img" data-lightbox-img src="" alt="">
    </div>
  </div>

  <div class="toast" id="inquiry-toast" role="status" aria-live="polite">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
    <span id="inquiry-toast-text"><?php tyrepol_esc_html_e('Zapytanie zostało wysłane. Skontaktujemy się wkrótce.', 'Your enquiry has been sent. We\'ll be in touch soon.'); ?></span>
  </div>

  <?php wp_footer(); ?>
</body>
</html>
