<?php
/**
 * Cząstka „Baner (about-hero)” — używana jako pierwsza sekcja szablonu „Elastyczna strona”
 * (np. baner marki Saucerman albo baner strony O firmie).
 * $args: eyebrow, title, lead, badges (array tekstów), image (ID załącznika), image_fit (cover|contain|auto)
 */
if (!defined('ABSPATH')) exit;
$eyebrow  = $args['eyebrow'] ?? '';
$title    = $args['title'] ?? '';
$lead     = $args['lead'] ?? '';
$badges   = $args['badges'] ?? [];
$image    = $args['image'] ?? null;
$fit_val  = $args['image_fit'] ?? 'cover';
$fit      = $fit_val === 'contain' ? ' about-hero__img--contain' : '';
$media_cl = $fit_val === 'auto' ? ' about-hero__media--auto' : '';
?>
<section class="about-hero about-hero--top">
  <div class="about-hero__inner">
    <div class="about-hero__content reveal">
      <?php if ($eyebrow) : ?><span class="about-hero__eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
      <h1 class="about-hero__title"><?php echo esc_html($title); ?></h1>
      <?php if ($lead) : ?><p class="about-hero__lead"><?php echo esc_html($lead); ?></p><?php endif; ?>
      <?php if (!empty($badges)) : ?>
      <ul class="about-hero__badges">
        <?php foreach ($badges as $b) : if (empty($b['tekst'])) continue; ?>
          <li class="about-hero__badge"><?php echo esc_html($b['tekst']); ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
    <?php if ($image) : ?>
    <div class="about-hero__media about-hero__media--product reveal<?php echo $media_cl; ?>">
      <?php echo wp_get_attachment_image($image, 'large', false, ['class' => 'about-hero__img' . $fit, 'loading' => 'eager']); ?>
    </div>
    <?php endif; ?>
  </div>
</section>
