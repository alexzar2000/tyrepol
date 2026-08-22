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

      <form class="modal__form" id="inquiry-form" method="post">
        <input type="hidden" name="form_type" value="wycena">
        <div class="form__honeypot" aria-hidden="true">
          <label for="inquiry-website"><?php tyrepol_esc_html_e('Strona internetowa', 'Website'); ?></label>
          <input type="text" id="inquiry-website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form__group">
          <label class="form__label" for="inquiry-size"><?php tyrepol_esc_html_e('Interesujący rozmiar', 'Size you\'re interested in'); ?> *</label>
          <select class="form__input" id="inquiry-size" name="size" required>
            <option value="" selected disabled><?php tyrepol_esc_html_e('Wybierz rozmiar', 'Choose a size'); ?></option>
            <?php foreach (tyrepol_get_available_sizes() as $size) : ?>
              <option value="<?php echo esc_attr($size); ?>"><?php echo esc_html($size); ?></option>
            <?php endforeach; ?>
            <option value="inny"><?php tyrepol_esc_html_e('Inny rozmiar / nie wiem', 'Other size / not sure'); ?></option>
          </select>
        </div>

        <div class="form__group">
          <label class="form__label" for="inquiry-qty"><?php tyrepol_esc_html_e('Ilość sztuk', 'Quantity'); ?></label>
          <input class="form__input" type="number" id="inquiry-qty" name="qty" min="1" placeholder="<?php tyrepol_esc_attr_e('np. 4', 'e.g. 4'); ?>">
        </div>

        <div class="form__group">
          <label class="form__label" for="inquiry-email"><?php tyrepol_esc_html_e('E-mail', 'Email'); ?> *</label>
          <input class="form__input" type="email" id="inquiry-email" name="email" required>
        </div>

        <div class="form__group">
          <label class="form__label" for="inquiry-phone"><?php tyrepol_esc_html_e('Telefon', 'Phone'); ?></label>
          <input class="form__input" type="tel" id="inquiry-phone" name="phone">
        </div>

        <div class="form__group">
          <label class="form__label" for="inquiry-message"><?php tyrepol_esc_html_e('Wiadomość', 'Message'); ?></label>
          <textarea class="form__textarea form__textarea--small" id="inquiry-message" name="message" rows="3"></textarea>
        </div>

        <div class="form__group form__group--checkbox">
          <input class="form__checkbox" type="checkbox" id="inquiry-rodo" name="rodo" required>
          <label class="form__checkbox-label" for="inquiry-rodo"><?php echo wp_kses_post(tyrepol_strip_wrapping_p(tyrepol_opt('tekst_zgody_rodo', tyrepol_t('Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłane zapytanie, zgodnie z Polityką prywatności.', 'I agree to the processing of my personal data in order to receive a reply to my enquiry, in accordance with the Privacy Policy.')))); ?> *</label>
        </div>

        <button class="form__submit" type="submit"><?php tyrepol_esc_html_e('Wyślij zapytanie', 'Send enquiry'); ?></button>
      </form>
    </div>
  </div>

  <div class="toast" id="inquiry-toast" role="status" aria-live="polite">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
    <span id="inquiry-toast-text"><?php tyrepol_esc_html_e('Zapytanie zostało wysłane. Skontaktujemy się wkrótce.', 'Your enquiry has been sent. We\'ll be in touch soon.'); ?></span>
  </div>

  <?php wp_footer(); ?>
</body>
</html>
