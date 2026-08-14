<?php
/**
 * Cząstka „Naprzemienne bloki tekst + zdjęcie” (about-split) — np. zakres produktowy wg osi montażu.
 * $args: rows (array: eyebrow, tytul, tekst, rozmiary[], link_tekst, link_url, image, image_fit, reverse)
 */
if (!defined('ABSPATH')) exit;
$rows = $args['rows'] ?? [];
?>
<section class="about-split">
  <div class="about-split__inner">
    <?php foreach ($rows as $row) :
      $row_class = 'about-split__row reveal' . (!empty($row['reverse']) ? ' about-split__row--reverse' : '');
      $fit = (($row['image_fit'] ?? 'cover') === 'contain') ? ' about-split__img--contain' : '';
    ?>
    <div class="<?php echo esc_attr($row_class); ?>">
      <?php if (!empty($row['image'])) : ?>
      <div class="about-split__media">
        <?php echo wp_get_attachment_image($row['image'], 'large', false, ['class' => 'about-split__img' . $fit]); ?>
      </div>
      <?php endif; ?>
      <div class="about-split__content">
        <?php if (!empty($row['eyebrow'])) : ?><span class="about__eyebrow"><?php echo esc_html($row['eyebrow']); ?></span><?php endif; ?>
        <h2 class="about-split__title"><?php echo esc_html($row['tytul'] ?? ''); ?></h2>
        <?php if (!empty($row['tekst'])) : ?><p class="about-split__text"><?php echo esc_html($row['tekst']); ?></p><?php endif; ?>
        <?php if (!empty($row['rozmiary'])) : ?>
        <ul class="about-split__sizes">
          <?php foreach ($row['rozmiary'] as $r) : if (empty($r['rozmiar'])) continue; ?>
            <li class="about-split__size"><?php echo esc_html($r['rozmiar']); ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <?php if (!empty($row['link_url'])) : ?>
        <a class="about-split__link" href="<?php echo esc_url($row['link_url']); ?>"><?php echo esc_html($row['link_tekst'] ?: __('Zobacz ofertę', 'tyrepol')); ?>
          <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 3l5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
