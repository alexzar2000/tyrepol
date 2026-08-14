<?php
/**
 * Cząstka „Liczniki” (counters) — animacja odliczania obsługiwana przez istniejący assets/script.js
 * (funkcja initCounters, bez zmian).
 * $args: title, desc, items (array: ikona, liczba, etykieta, predkosc)
 */
if (!defined('ABSPATH')) exit;
$title = $args['title'] ?? '';
$desc  = $args['desc'] ?? '';
$items = $args['items'] ?? [];
?>
<section class="counters">
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
        <?php echo tyrepol_icon($item['ikona'] ?? 'domyslna'); ?>
        <h3 class="counters__number" data-to="<?php echo esc_attr($to); ?>" data-speed="<?php echo esc_attr($speed); ?>">0</h3>
        <p class="counters__label"><?php echo esc_html($item['etykieta'] ?? ''); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
