<?php
/**
 * Cząstka „Galeria zdjęć” (about-gallery).
 * $args: title, desc, items (array: image, podpis)
 * Zdjęcia renderowane jako tło kafelka (background-image, cover, wyśrodkowane) — zawsze
 * wypełniają cały kafelek i są wycentrowane, niezależnie od proporcji wgranego pliku.
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
        $img_url = wp_get_attachment_image_url($item['image'], 'tyrepol-gallery');
        if (!$img_url) continue;
      ?>
      <figure class="about-gallery__item reveal">
        <span class="about-gallery__media" style="background-image: url('<?php echo esc_url($img_url); ?>');" role="img" aria-label="<?php echo esc_attr($item['podpis'] ?? ''); ?>"></span>
        <?php if (!empty($item['podpis'])) : ?><figcaption class="about-gallery__caption"><?php echo esc_html($item['podpis']); ?></figcaption><?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>

  </div>
</section>
