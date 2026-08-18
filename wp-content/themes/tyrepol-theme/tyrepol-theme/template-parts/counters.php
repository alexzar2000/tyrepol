<?php
/**
 * Cząstka „Liczniki” (counters) — animacja odliczania obsługiwana przez istniejący assets/script.js
 * (funkcja initCounters, bez zmian).
 * $args: title, desc, items (array: ikona_obraz [ID załącznika], liczba, etykieta, predkosc), bg (ID załącznika, opcjonalnie)
 * Ikonka to małe zdjęcie wgrywane przez redaktora — wyświetla się w stałym kwadracie i skaluje
 * (object-fit: contain), więc dowolny rozmiar wgranego pliku wygląda poprawnie.
 * Tło sekcji: jeśli redaktor wgrał własne zdjęcie (pole „Tło sekcji”), użyj go zamiast
 * domyślnego tła zdefiniowanego w assets/style.css.
 */
if (!defined('ABSPATH')) exit;
$title   = $args['title'] ?? '';
$desc    = $args['desc'] ?? '';
$items   = $args['items'] ?? [];
$bg_id   = $args['bg'] ?? null;
$bg_url  = $bg_id ? wp_get_attachment_image_url($bg_id, 'full') : '';
$bg_style = $bg_url ? ' style="background-image: url(' . esc_url($bg_url) . ');"' : '';
?>
<section class="counters"<?php echo $bg_style; ?>>
  <div class="counters__inner">

    <div class="counters__header reveal">
      <?php if ($title) : ?><h2 class="counters__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if ($desc) : ?><p class="counters__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    </div>

    <div class="counters__grid">
      <?php foreach ($items as $item) :
        $to = intval($item['liczba'] ?? 0);
        $speed = intval($item['predkosc'] ?? 1500) ?: 1500;
      ?>
      <div class="counters__item reveal">
        <span class="counters__icon">
          <?php if (!empty($item['ikona_obraz'])) : ?>
            <?php echo wp_get_attachment_image($item['ikona_obraz'], 'thumbnail', false, ['class' => 'counters__icon-img', 'alt' => '', 'loading' => 'lazy']); ?>
          <?php else : ?>
            <?php echo tyrepol_icon('domyslna'); ?>
          <?php endif; ?>
        </span>
        <h3 class="counters__number" data-to="<?php echo esc_attr($to); ?>" data-speed="<?php echo esc_attr($speed); ?>">0</h3>
        <p class="counters__label"><?php echo esc_html($item['etykieta'] ?? ''); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
