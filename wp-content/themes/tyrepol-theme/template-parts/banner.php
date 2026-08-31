<?php
/**
 * Cząstka „Baner" — szerokie zdjęcie z opcjonalnym nagłówkiem/tekstem na nim.
 * $args: image (ID załącznika), title, desc.
 * Obecnie używana tylko nad sekcją „Kontakt" (patrz page-kontakt.php + acf-json/group_kontakt_baner.json),
 * ale jako osobna cząstka — łatwo dodać ją też na innych stronach w przyszłości.
 */
if (!defined('ABSPATH')) exit;
$image = $args['image'] ?? null;
$title = $args['title'] ?? '';
$desc  = $args['desc'] ?? '';
if (!$image) return;
?>
<section class="page-banner reveal">
  <?php echo wp_get_attachment_image($image, 'tyrepol-banner', false, ['class' => 'page-banner__img']); ?>
  <?php if ($title || $desc) : ?>
  <div class="page-banner__overlay">
    <div class="page-banner__inner">
      <?php if ($title) : ?><h2 class="page-banner__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if ($desc) : ?><p class="page-banner__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</section>
