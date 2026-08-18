<?php
/**
 * Cząstka „Karty cech” (about-values) — np. „Dlaczego opony Saucerman” / „Dlaczego TyrePol”.
 * $args: title, desc, cards (array: ikona_obraz [ID załącznika], tytul, opis)
 * Ikonka to małe zdjęcie wgrywane przez redaktora — wyświetla się w stałym kółku i skaluje
 * (object-fit: contain), więc dowolny rozmiar wgranego pliku wygląda poprawnie. Brak wgranej
 * ikonki -> pokazuje się domyślna ikonka z motywu (kółko), żeby karta nigdy nie była pusta.
 */
if (!defined('ABSPATH')) exit;
$title = $args['title'] ?? '';
$desc  = $args['desc'] ?? '';
$cards = $args['cards'] ?? [];
?>
<section class="about-values">
  <div class="about-values__inner">

    <div class="about-values__header reveal">
      <?php if ($title) : ?><h2 class="about-values__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if ($desc) : ?><p class="about-values__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    </div>

    <div class="about-values__grid">
      <?php foreach ($cards as $card) : ?>
      <div class="about-values__card reveal">
        <span class="about-values__icon">
          <?php if (!empty($card['ikona_obraz'])) : ?>
            <?php echo wp_get_attachment_image($card['ikona_obraz'], 'thumbnail', false, ['class' => 'about-values__icon-img', 'alt' => '', 'loading' => 'lazy']); ?>
          <?php else : ?>
            <?php echo tyrepol_icon('domyslna', 24); ?>
          <?php endif; ?>
        </span>
        <h3 class="about-values__card-title"><?php echo esc_html($card['tytul'] ?? ''); ?></h3>
        <p class="about-values__card-text"><?php echo esc_html($card['opis'] ?? ''); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
