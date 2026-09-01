<?php
/**
 * Cząstka „Własny przycisk CTA” — jeden przycisk z tekstem i adresem wpisanym przez admina
 * w panelu (nie z zaszytym na sztywno „Darmowa wycena”, jak template-parts/section-cta-button.php).
 * Używana w trzech miejscach: nad filtrami w katalogu opon (page-opony.php), pod tekstem w sekcji
 * „Tekst + zdjęcie” elastycznej strony (template-parts/text-block.php) i na dole danych
 * kontaktowych (template-parts/contact.php). Adres obsługuje też „mailto:” i „tel:” — patrz
 * tyrepol_waliduj_adres_cta() w inc/helpers.php.
 * $args: tekst, url, class (dodatkowa klasa CSS na wrapperze — pozycjonowanie w danym bloku)
 */
if (!defined('ABSPATH')) exit;
$tekst = trim((string) ($args['tekst'] ?? ''));
$url   = trim((string) ($args['url'] ?? ''));
$class = trim((string) ($args['class'] ?? ''));
if ($tekst === '' || $url === '') return;
?>
<div class="cta-custom<?php echo $class !== '' ? ' ' . esc_attr($class) : ''; ?>">
  <a class="cta-custom__btn" href="<?php echo esc_url($url); ?>"><?php echo esc_html($tekst); ?></a>
</div>
