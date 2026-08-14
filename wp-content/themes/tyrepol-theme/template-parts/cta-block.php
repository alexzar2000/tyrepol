<?php
/**
 * Cząstka „CTA” (about-cta) — nagłówek + opis + 1-2 przyciski.
 * $args: title, desc, buttons (array: tekst, url, styl [primary|outline])
 */
if (!defined('ABSPATH')) exit;
$title   = $args['title'] ?? '';
$desc    = $args['desc'] ?? '';
$buttons = $args['buttons'] ?? [];
?>
<section class="about-cta">
  <div class="about-cta__inner reveal">
    <?php if ($title) : ?><h2 class="about-cta__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
    <?php if ($desc) : ?><p class="about-cta__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    <?php if (!empty($buttons)) : ?>
    <div class="about-cta__actions">
      <?php foreach ($buttons as $i => $btn) :
        if (empty($btn['tekst'])) continue;
        $style = ($btn['styl'] ?? ($i === 0 ? 'primary' : 'outline')) === 'outline' ? 'outline' : 'primary';
      ?>
        <a class="about-cta__btn about-cta__btn--<?php echo esc_attr($style); ?>" href="<?php echo esc_url($btn['url'] ?? '#'); ?>"><?php echo esc_html($btn['tekst']); ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
