<?php
/**
 * Cząstka „Galeria zdjęć” (about-gallery).
 * $args: title, desc, items (array: image, podpis, image_fit [cover|contain|auto])
 */
if (!defined('ABSPATH')) exit;
$title = $args['title'] ?? '';
$desc  = $args['desc'] ?? '';
$items = $args['items'] ?? [];
?>
<section class="about-gallery">
  <div class="about-gallery__inner">

    <div class="about-gallery__header reveal">
      <?php if ($title) : ?><h2 class="about-gallery__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if ($desc) : ?><p class="about-gallery__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    </div>

    <div class="about-gallery__grid">
      <?php foreach ($items as $item) :
        if (empty($item['image'])) continue;
        $fit_val = $item['image_fit'] ?? 'cover';
        $fit = $fit_val === 'contain' ? ' about-gallery__img--contain' : '';
        $item_cl = $fit_val === 'auto' ? ' about-gallery__item--auto' : '';
      ?>
      <figure class="about-gallery__item reveal<?php echo $item_cl; ?>">
        <?php echo wp_get_attachment_image($item['image'], 'tyrepol-gallery', false, ['class' => 'about-gallery__img' . $fit]); ?>
        <?php if (!empty($item['podpis'])) : ?><figcaption class="about-gallery__caption"><?php echo esc_html($item['podpis']); ?></figcaption><?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>

  </div>
</section>
