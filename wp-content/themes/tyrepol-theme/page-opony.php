<?php
/**
 * Template Name: Katalog opon
 *
 * Filtrowana siatka opon (marka / typ pojazdu / oś / sezon / rozmiar). Filtrowanie po stronie
 * przeglądarki wykonuje ta sama logika co w wersji statycznej (assets/script.js → initCatalog),
 * tylko dane wejściowe (TIRES) są teraz pobierane z bazy WordPress (CPT „Opona”), a nie zaszyte
 * na sztywno w pliku JS — dzięki temu każdą oponę da się dodać/edytować w panelu. Wpisy są
 * grupowane wg modelu (marka + wzór bieżnika), więc niezależnie od tego ile rozmiarów danego
 * modelu jest w bazie, w siatce pokazuje się jedna karta — dokładnie jak w wersji statycznej.
 *
 * Przypisz ten szablon stronie o adresie /opony/ (Atrybuty strony → Szablon).
 */
if (!defined('ABSPATH')) exit;
get_header();

// Pobranie wszystkich opublikowanych opon i pogrupowanie ich wg modelu (marka + wzór bieżnika) —
// każdy wpis CPT „Opona” to jeden ROZMIAR, ale w katalogu (i w dawnej wersji statycznej) jeden
// MODEL pokazuje się jako JEDNA karta, niezależnie od tego ile ma rozmiarów w bazie. Kliknięcie
// karty prowadzi do strony pierwszego (reprezentatywnego) rozmiaru — sama strona szczegółów
// (single-opona.php) doszukuje się tam wszystkich pozostałych rozmiarów tego samego modelu
// i pokazuje je razem w jednej tabeli (patrz komentarz w single-opona.php).
$groups = [];
// Opony są WSPÓLNE dla obu wersji językowych (nie są tłumaczalne przez Polylang — patrz komentarz
// w inc/cpt-opona.php), więc pokazujemy TE SAME opublikowane opony niezależnie od języka strony —
// bez filtrowania po 'lang'. Etykiety kolumn/kategorii tłumaczą się same (tyrepol_term_label()).
$opony = get_posts(['post_type' => 'opona', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);

foreach ($opony as $opona) {
    $brand_terms   = get_the_terms($opona->ID, 'marka-opony');
    $axle_terms    = get_the_terms($opona->ID, 'os-montazu');
    $season_terms  = get_the_terms($opona->ID, 'sezon-opony');
    $vehicle_terms = get_the_terms($opona->ID, 'typ-pojazdu');

    $brand_slug = $brand_terms && !is_wp_error($brand_terms) ? $brand_terms[0]->slug : '';
    $pattern    = get_field('wzor_bieznika', $opona->ID) ?: get_the_title($opona->ID);
    $size       = get_field('rozmiar', $opona->ID);
    $key        = $brand_slug . '||' . $pattern;

    if (!isset($groups[$key])) {
        $groups[$key] = [
            'id'      => $opona->ID,
            'brand'   => $brand_slug,
            'axle'    => [],
            'season'  => [],
            'vehicle' => [],
            'pattern' => $pattern,
            'sizes'   => [],
            'image'   => get_the_post_thumbnail_url($opona->ID, 'tyrepol-card') ?: null,
            'link'    => get_permalink($opona->ID),
        ];
    }

    // Oś/sezon/typ pojazdu zbieramy z WSZYSTKICH rozmiarów modelu (unia) — dzięki temu filtry
    // działają poprawnie nawet gdyby poszczególne rozmiary miały inne przypisane terminy.
    if ($axle_terms && !is_wp_error($axle_terms)) {
        foreach ($axle_terms as $t) { $groups[$key]['axle'][$t->slug] = true; }
    }
    if ($season_terms && !is_wp_error($season_terms)) {
        foreach ($season_terms as $t) { $groups[$key]['season'][$t->slug] = true; }
    }
    if ($vehicle_terms && !is_wp_error($vehicle_terms)) {
        foreach ($vehicle_terms as $t) { $groups[$key]['vehicle'][$t->slug] = true; }
    }
    if ($size) { $groups[$key]['sizes'][$size] = true; }
}

$tires = [];
foreach ($groups as $group) {
    $group['axle']    = array_values(array_keys($group['axle']));
    $group['season']  = array_values(array_keys($group['season']));
    $group['vehicle'] = array_values(array_keys($group['vehicle']));
    $group['sizes']   = array_values(array_keys($group['sizes']));
    $tires[] = $group;
}

$brand_terms_all   = get_terms(['taxonomy' => 'marka-opony', 'hide_empty' => false]);
$axle_terms_all    = get_terms(['taxonomy' => 'os-montazu', 'hide_empty' => false]);
$season_terms_all  = get_terms(['taxonomy' => 'sezon-opony', 'hide_empty' => false]);
$vehicle_terms_all = get_terms(['taxonomy' => 'typ-pojazdu', 'hide_empty' => false]);

$brand_labels = $axle_labels = $season_labels = $vehicle_labels = [];
foreach ($brand_terms_all as $t) $brand_labels[$t->slug] = $t->name; // nazwy marek te same w PL/EN
foreach ($axle_terms_all as $t) $axle_labels[$t->slug] = tyrepol_term_label($t);
foreach ($season_terms_all as $t) $season_labels[$t->slug] = tyrepol_term_label($t);
foreach ($vehicle_terms_all as $t) $vehicle_labels[$t->slug] = tyrepol_term_label($t);

$all_sizes = array_values(tyrepol_get_available_sizes());

// Dane dla assets/script.js (initCatalog) — patrz zmodyfikowany plik, sekcja "Dane z WordPressa".
wp_add_inline_script('tyrepol-script', 'window.tyrepolCatalog = ' . wp_json_encode([
    'tires'         => $tires,
    'brandLabels'   => $brand_labels,
    'axleLabels'    => $axle_labels,
    'seasonLabels'  => $season_labels,
    'vehicleLabels' => $vehicle_labels,
]) . ';', 'before');
?>

  <button class="catalog__filter-jump" id="catalog-filter-jump" type="button">
    <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M2 10l6-6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    <?php tyrepol_esc_html_e('Wróć do filtrów', 'Back to filters'); ?>
  </button>

  <?php while (have_posts()) : the_post(); ?>

  <section class="catalog catalog--top" id="katalog">
    <div class="catalog__inner">

      <div class="catalog__header">
        <h1 class="catalog__title"><?php echo esc_html(get_field('katalog_naglowek') ?: get_the_title()); ?></h1>
        <p class="catalog__desc"><?php echo esc_html(get_field('katalog_opis')); ?></p>

        <?php if (!empty($brand_terms_all)) : ?>
        <div class="catalog__brand-carousel">
          <button class="catalog__brand-nav catalog__brand-nav--prev" type="button" aria-label="<?php tyrepol_esc_attr_e('Poprzednia marka', 'Previous brand'); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 2 4 8l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>

          <div class="catalog__brand-swiper swiper" id="catalog-brand-tiles">
            <div class="swiper-wrapper">
              <?php foreach ($brand_terms_all as $term) :
                $logo = function_exists('get_field') ? get_field('logo', 'marka-opony_' . $term->term_id) : null;
              ?>
              <div class="swiper-slide">
                <button class="catalog__brand-tile" type="button" data-brand="<?php echo esc_attr($term->slug); ?>">
                  <?php if ($logo) : echo wp_get_attachment_image($logo, 'medium', false, ['class' => 'catalog__brand-tile-logo', 'alt' => sprintf(tyrepol_t('Logo marki %s', '%s brand logo'), $term->name)]);
                  else : ?><span class="catalog__brand-tile-logo"><?php echo esc_html($term->name); ?></span><?php endif; ?>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <button class="catalog__brand-nav catalog__brand-nav--next" type="button" aria-label="<?php tyrepol_esc_attr_e('Następna marka', 'Next brand'); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 2l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <div class="catalog__brand-pagination swiper-pagination"></div>
        <?php endif; ?>
      </div>

      <div class="catalog__layout">

        <form class="catalog__filters" method="get">

          <div class="filter__group">
            <h3 class="filter__title"><?php tyrepol_esc_html_e('Marka', 'Brand'); ?></h3>
            <?php foreach ($brand_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="brand" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html($t->name); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php tyrepol_esc_html_e('Typ pojazdu', 'Vehicle type'); ?></h3>
            <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="all" checked> <?php tyrepol_esc_html_e('Wszystkie', 'All'); ?></label>
            <?php foreach ($vehicle_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html(tyrepol_term_label($t)); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php tyrepol_esc_html_e('Oś', 'Axle'); ?></h3>
            <?php foreach ($axle_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html(tyrepol_term_label($t)); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php tyrepol_esc_html_e('Sezon', 'Season'); ?></h3>
            <?php foreach ($season_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="season" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html(tyrepol_term_label($t)); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php tyrepol_esc_html_e('Rozmiar', 'Size'); ?></h3>
            <select class="filter__select" name="size" aria-label="<?php tyrepol_esc_attr_e('Rozmiar opony', 'Tyre size'); ?>">
              <option value=""><?php tyrepol_esc_html_e('Wszystkie rozmiary', 'All sizes'); ?></option>
              <?php foreach ($all_sizes as $size) : ?>
                <option value="<?php echo esc_attr($size); ?>"><?php echo esc_html($size); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button class="filter__submit" type="reset"><?php tyrepol_esc_html_e('Wyczyść filtry', 'Clear filters'); ?></button>
        </form>

        <div class="catalog__results">
          <div class="catalog__grid" id="catalog-grid"></div>
          <p class="catalog__empty" id="catalog-empty" hidden><?php tyrepol_esc_html_e('Brak opon spełniających wybrane kryteria.', 'No tyres match the selected criteria.'); ?></p>
          <div class="catalog__actions">
            <button class="catalog__load-more" id="catalog-load-more" type="button"><?php tyrepol_esc_html_e('Załaduj więcej opon', 'Load more tyres'); ?></button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php
  // FAQ jest teraz WSPÓLNE dla wszystkich stron — edytuje się raz w Ustawienia motywu → FAQ.
  tyrepol_faq_section('faq');
  ?>

  <?php tyrepol_contact_section(false); ?>

  <?php endwhile; ?>

<?php get_footer(); ?>
