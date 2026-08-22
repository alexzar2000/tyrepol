<?php
/**
 * Cząstka „Kontakt” — dane firmy pobierane z Ustawienia motywu → Kontakt i social media.
 * $args['top'] = true -> wariant --top (gdy sekcja jest jedyną/główną treścią strony, np. Kontakt).
 */
if (!defined('ABSPATH')) exit;
$top = !empty($args['top']);
$section_class = 'contact' . ($top ? ' contact--top' : '');

$socials = [
    'facebook'  => ['url' => tyrepol_opt('social_facebook'), 'label' => 'Facebook', 'icon' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>'],
    'instagram' => ['url' => tyrepol_opt('social_instagram'), 'label' => 'Instagram', 'icon' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>'],
    'linkedin'  => ['url' => tyrepol_opt('social_linkedin'), 'label' => 'LinkedIn', 'icon' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle>'],
    'youtube'   => ['url' => tyrepol_opt('social_youtube'), 'label' => 'YouTube', 'icon' => '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>'],
];
?>
<section class="<?php echo esc_attr($section_class); ?>" id="kontakt">
  <div class="contact__inner">

    <div class="contact__header reveal">
      <h2 class="contact__title"><?php echo esc_html(tyrepol_opt('kontakt_naglowek', tyrepol_t('Kontakt', 'Contact'))); ?></h2>
      <p class="contact__desc"><?php echo esc_html(tyrepol_opt('kontakt_opis', tyrepol_t('Masz pytanie dotyczące opon, felg lub oferty? Napisz do nas lub odwiedź nas osobiście — chętnie pomożemy dobrać najlepsze rozwiązanie.', 'Have a question about tyres, rims or our offer? Write to us or visit us in person — we\'ll be happy to help you choose the best solution.'))); ?></p>
    </div>

    <?php if ($map = tyrepol_opt('mapa_embed_url')) : ?>
    <div class="contact__map reveal">
      <iframe src="<?php echo esc_url($map); ?>" width="600" height="450" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="<?php tyrepol_esc_attr_e('Mapa — lokalizacja firmy', 'Map — company location'); ?>"></iframe>
    </div>
    <?php endif; ?>

    <div class="contact__grid">

      <div class="contact__info reveal">
        <h2 class="contact__info-heading"><?php tyrepol_esc_html_e('Skontaktuj się z nami', 'Get in touch'); ?></h2>
        <p class="contact__info-lead"><?php echo esc_html(tyrepol_opt('kontakt_lead', tyrepol_t('Zapraszamy do kontaktu telefonicznego, mailowego lub osobistej wizyty w naszej siedzibie.', 'Feel free to contact us by phone, email, or visit our office in person.'))); ?></p>

        <div class="contact__info-block">
          <h3 class="contact__info-title"><?php echo esc_html(tyrepol_opt('firma_nazwa', 'TyrePol')); ?></h3>
          <ul class="contact__info-list">
            <?php for ($i = 1; $i <= 4; $i++) : $linia = tyrepol_opt('firma_adres_linia_' . $i); if (!$linia) continue; ?>
              <li class="contact__info-item"><?php echo esc_html($linia); ?></li>
            <?php endfor; ?>
          </ul>
        </div>

        <?php
        $biuro_linie = [];
        for ($i = 1; $i <= 3; $i++) { $l = tyrepol_opt('biuro_adres_linia_' . $i); if ($l) $biuro_linie[] = $l; }
        if (tyrepol_opt('biuro_naglowek') || $biuro_linie) : ?>
        <div class="contact__info-block">
          <h3 class="contact__info-title"><?php echo esc_html(tyrepol_opt('biuro_naglowek', tyrepol_t('Biuro / Magazyn', 'Office / Warehouse'))); ?></h3>
          <ul class="contact__info-list">
            <?php foreach ($biuro_linie as $linia) : ?>
              <li class="contact__info-item"><?php echo esc_html($linia); ?></li>
            <?php endforeach; ?>
            <?php if ($tel = tyrepol_opt('telefon')) : ?>
            <li class="contact__info-item contact__info-item--icon">
              <span class="contact__info-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span>
              <a class="contact__info-link" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $tel)); ?>"><?php echo esc_html($tel); ?></a>
            </li>
            <?php endif; ?>
            <?php if ($mail = tyrepol_opt('email')) : ?>
            <li class="contact__info-item contact__info-item--icon">
              <span class="contact__info-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m2 6 10 7 10-7"></path></svg></span>
              <a class="contact__info-link" href="mailto:<?php echo esc_attr($mail); ?>"><?php echo esc_html($mail); ?></a>
            </li>
            <?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>

        <ul class="contact__social">
          <?php foreach ($socials as $s) : if (empty($s['url'])) continue; ?>
          <li class="contact__social-item">
            <a class="contact__social-link" href="<?php echo esc_url($s['url']); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($s['label']); ?>">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $s['icon']; ?></svg>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <form class="contact__form reveal" id="contact-form" method="post">
        <input type="hidden" name="form_type" value="kontakt">
        <div class="form__honeypot" aria-hidden="true">
          <label for="contact-website"><?php tyrepol_esc_html_e('Strona internetowa', 'Website'); ?></label>
          <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form__group">
          <label class="form__label" for="contact-email"><?php tyrepol_esc_html_e('E-mail', 'Email'); ?> *</label>
          <input class="form__input" type="email" id="contact-email" name="email" required>
        </div>

        <div class="form__group">
          <label class="form__label" for="contact-phone"><?php tyrepol_esc_html_e('Telefon', 'Phone'); ?></label>
          <input class="form__input" type="tel" id="contact-phone" name="phone">
        </div>

        <div class="form__group">
          <label class="form__label" for="contact-message"><?php tyrepol_esc_html_e('Wiadomość', 'Message'); ?></label>
          <textarea class="form__textarea" id="contact-message" name="message" rows="5"></textarea>
        </div>

        <div class="form__group form__group--checkbox">
          <input class="form__checkbox" type="checkbox" id="contact-rodo" name="rodo" required>
          <label class="form__checkbox-label" for="contact-rodo"><?php echo wp_kses_post(tyrepol_strip_wrapping_p(tyrepol_opt('tekst_zgody_rodo', tyrepol_t('Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłane zapytanie, zgodnie z Polityką prywatności.', 'I agree to the processing of my personal data in order to receive a reply to my enquiry, in accordance with the Privacy Policy.')))); ?> *</label>
        </div>

        <p class="form__status" id="contact-form-status" role="status" aria-live="polite" hidden></p>

        <button class="form__submit" type="submit"><?php tyrepol_esc_html_e('Wyślij', 'Send'); ?></button>
      </form>

    </div>

  </div>
</section>
