<?php
/**
 * Cząstka „Tekst + zdjęcie” (about) — np. „O marce” / „Kim jesteśmy”.
 * $args: eyebrow, title, body (HTML z WYSIWYG), image (ID załącznika), cta_tekst, cta_url
 *   (opcjonalny własny przycisk pod tekstem — patrz template-parts/cta-custom-button.php)
 * Zdjęcie zawsze na pełną szerokość bloku, z automatyczną wysokością wg własnych proporcji —
 * bez kadrowania i bez rozciągania (patrz .about__media / .about__img w assets/style.css).
 */
if (!defined('ABSPATH')) exit;
$eyebrow  = $args['eyebrow'] ?? '';
$title    = $args['title'] ?? '';
$body     = $args['body'] ?? '';
$image    = $args['image'] ?? null;
$cta_tekst = $args['cta_tekst'] ?? '';
$cta_url   = $args['cta_url'] ?? '';
?>
<section class="about">
  <div class="about__inner">
    <?php if ($image) : ?>
    <div class="about__media reveal">
      <?php echo wp_get_attachment_image($image, 'large', false, ['class' => 'about__img']); ?>
    </div>
    <?php endif; ?>
    <div class="about__content reveal">
      <?php if ($eyebrow) : ?><span class="about__eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
      <h2 class="about__title"><?php echo esc_html($title); ?></h2>
      <div class="about__body"><?php echo wp_kses_post($body); ?></div>
      <?php get_template_part('template-parts/cta-custom-button', null, ['tekst' => $cta_tekst, 'url' => $cta_url, 'class' => 'about__cta']); ?>
    </div>
  </div>
</section>
