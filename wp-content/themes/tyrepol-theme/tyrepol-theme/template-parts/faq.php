<?php
/**
 * Cząstka „FAQ” — akordeon obsługiwany przez istniejący assets/script.js (initFaq, bez zmian).
 * $args: title, desc, items (array: pytanie, odpowiedz)
 */
if (!defined('ABSPATH')) exit;
$title = $args['title'] ?? '';
$desc  = $args['desc'] ?? '';
$items = $args['items'] ?? [];
$anchor = $args['anchor'] ?? 'faq-' . wp_unique_id();
?>
<section class="faq" id="<?php echo esc_attr($anchor); ?>">
  <div class="faq__inner">

    <div class="faq__header reveal">
      <?php if ($title) : ?><h2 class="faq__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if ($desc) : ?><p class="faq__desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
    </div>

    <div class="faq__list">
      <?php foreach ($items as $i => $item) : if (empty($item['pytanie'])) continue; ?>
      <div class="faq__item reveal">
        <button class="faq__question" id="<?php echo esc_attr($anchor . '-button-' . ($i + 1)); ?>" type="button" aria-expanded="false">
          <span class="faq__question-text"><?php echo esc_html($item['pytanie']); ?></span>
          <span class="faq__icon" aria-hidden="true"></span>
        </button>
        <div class="faq__answer">
          <p class="faq__answer-text"><?php echo esc_html($item['odpowiedz'] ?? ''); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
