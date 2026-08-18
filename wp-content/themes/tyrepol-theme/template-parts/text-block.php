<?php
/**
 * Cząstka „Tekst + zdjęcie” (about) — np. „O marce” / „Kim jesteśmy”.
 * $args: eyebrow, title, body (HTML z WYSIWYG), image (ID załącznika), image_fit (cover|contain)
 */
if (!defined('ABSPATH')) exit;
$eyebrow = $args['eyebrow'] ?? '';
$title   = $args['title'] ?? '';
$body    = $args['body'] ?? '';
$image   = $args['image'] ?? null;
$fit     = ($args['image_fit'] ?? 'cover') === 'contain' ? ' about__img--contain' : '';
?>
<section class="about">
  <div class="about__inner">
    <?php if ($image) : ?>
    <div class="about__media reveal">
      <?php echo wp_get_attachment_image($image, 'large', false, ['class' => 'about__img' . $fit]); ?>
    </div>
    <?php endif; ?>
    <div class="about__content reveal">
      <?php if ($eyebrow) : ?><span class="about__eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
      <h2 class="about__title"><?php echo esc_html($title); ?></h2>
      <div class="about__body"><?php echo wp_kses_post($body); ?></div>
    </div>
  </div>
</section>
