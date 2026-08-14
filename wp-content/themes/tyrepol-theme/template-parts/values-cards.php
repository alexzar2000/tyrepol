<?php
/**
 * Cząstka „Karty cech” (about-values) — np. „Dlaczego opony Saucerman” / „Dlaczego TyrePol”.
 * $args: title, desc, cards (array: ikona, tytul, opis)
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
        <span class="about-values__icon" aria-hidden="true"><?php echo tyrepol_icon($card['ikona'] ?? 'domyslna', 24); ?></span>
        <h3 class="about-values__card-title"><?php echo esc_html($card['tytul'] ?? ''); ?></h3>
        <p class="about-values__card-text"><?php echo esc_html($card['opis'] ?? ''); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
