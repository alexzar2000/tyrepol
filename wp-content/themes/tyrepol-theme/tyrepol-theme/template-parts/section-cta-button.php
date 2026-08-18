<?php
/**
 * Cząstka „Przycisk CTA między sekcjami” (section-cta) — zawsze otwiera popup „Darmowa wycena”,
 * dokładnie tak jak w wersji statycznej.
 * $args: tekst
 */
if (!defined('ABSPATH')) exit;
$tekst = $args['tekst'] ?? __('Darmowa wycena', 'tyrepol');
?>
<div class="section-cta">
  <button class="section-cta__btn" type="button" data-modal-open="quote-modal"><?php echo esc_html($tekst); ?></button>
</div>
