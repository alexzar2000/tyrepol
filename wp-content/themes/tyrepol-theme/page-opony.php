<?php
/**
 * Template Name: Katalog opon
 *
 * Filtrowana siatka opon (marka / typ pojazdu / oś / sezon / rozmiar). Filtrowanie po stronie
 * przeglądarki wykonuje ta sama logika co w wersji statycznej (assets/script.js → initCatalog),
 * tylko dane wejściowe (TIRES) są teraz pobierane z bazy WordPress (CPT „Opona”), a nie zaszyte
 * na sztywno w pliku JS — dzięki temu każdą oponę da się dodać/edytować w panelu.
 *
 * Przypisz ten szablon stronie o adresie /opony/ (Atrybuty strony → Szablon).
 */
if (!defined('ABSPATH')) exit;
get_header();

// Pobranie wszystkich opublikowanych opon + ich pól, przygotowane w formacie identycznym
// z dawną tablicą TIRES z assets/script.js (żeby front-end (filtrowanie w JS) działał bez zmian).
$tires = [];
$opony = get_posts(['post_type' => 'opona', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);

foreach ($opony as $opona) {
    $brand_terms   = get_the_terms($opona->ID, 'marka-opony');
    $axle_terms    = get_the_terms($opona->ID, 'os-montazu');
    $season_terms  = get_the_terms($opona->ID, 'sezon-opony');
    $vehicle_terms = get_the_terms($opona->ID, 'typ-pojazdu');

    $tires[] = [
        'id'      => $opona->ID,
        'brand'   => $brand_terms && !is_wp_error($brand_terms) ? $brand_terms[0]->slug : '',
        'axle'    => $axle_terms && !is_wp_error($axle_terms) ? $axle_terms[0]->slug : '',
        'season'  => $season_terms && !is_wp_error($season_terms) ? $season_terms[0]->slug : '',
        'vehicle' => $vehicle_terms && !is_wp_error($vehicle_terms) ? $vehicle_terms[0]->slug : '',
        'pattern' => get_field('wzor_bieznika', $opona->ID),
        'size'    => get_field('rozmiar', $opona->ID),
        'image'   => get_the_post_thumbnail_url($opona->ID, 'tyrepol-card') ?: null,
        'link'    => get_permalink($opona->ID),
    ];
}

$brand_terms_all   = get_terms(['taxonomy' => 'marka-opony', 'hide_empty' => false]);
$axle_terms_all    = get_terms(['taxonomy' => 'os-montazu', 'hide_empty' => false]);
$season_terms_all  = get_terms(['taxonomy' => 'sezon-opony', 'hide_empty' => false]);
$vehicle_terms_all = get_terms(['taxonomy' => 'typ-pojazdu', 'hide_empty' => false]);

$brand_labels = $axle_labels = $season_labels = $vehicle_labels = [];
foreach ($brand_terms_all as $t) $brand_labels[$t->slug] = $t->name;
foreach ($axle_terms_all as $t) $axle_labels[$t->slug] = $t->name;
foreach ($season_terms_all as $t) $season_labels[$t->slug] = $t->name;
foreach ($vehicle_terms_all as $t) $vehicle_labels[$t->slug] = $t->name;

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
    <?php esc_html_e('Wróć do filtrów', 'tyrepol'); ?>
  </button>

  <?php while (have_posts()) : the_post(); ?>

  <section class="catalog catalog--top" id="katalog">
    <div class="catalog__inner">

      <div class="catalog__header">
        <h1 class="catalog__title"><?php echo esc_html(get_field('katalog_naglowek') ?: get_the_title()); ?></h1>
        <p class="catalog__desc"><?php echo esc_html(get_field('katalog_opis')); ?></p>

        <?php if (!empty($brand_terms_all)) : ?>
        <div class="catalog__brand-carousel">
          <button class="catalog__brand-nav catalog__brand-nav--prev" type="button" aria-label="<?php esc_attr_e('Poprzednia marka', 'tyrepol'); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 2 4 8l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>

          <div class="catalog__brand-swiper swiper" id="catalog-brand-tiles">
            <div class="swiper-wrapper">
              <?php foreach ($brand_terms_all as $term) :
                $logo = function_exists('get_field') ? get_field('logo', 'marka-opony_' . $term->term_id) : null;
              ?>
              <div class="swiper-slide">
                <button class="catalog__brand-tile" type="button" data-brand="<?php echo esc_attr($term->slug); ?>">
                  <?php if ($logo) : echo wp_get_attachment_image($logo, 'medium', false, ['class' => 'catalog__brand-tile-logo', 'alt' => sprintf(__('Logo marki %s', 'tyrepol'), $term->name)]);
                  else : ?><span class="catalog__brand-tile-logo"><?php echo esc_html($term->name); ?></span><?php endif; ?>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <button class="catalog__brand-nav catalog__brand-nav--next" type="button" aria-label="<?php esc_attr_e('Następna marka', 'tyrepol'); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 2l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <div class="catalog__brand-pagination swiper-pagination"></div>
        <?php endif; ?>
      </div>

      <div class="catalog__layout">

        <form class="catalog__filters" method="get">

          <div class="filter__group">
            <h3 class="filter__title"><?php esc_html_e('Marka', 'tyrepol'); ?></h3>
            <?php foreach ($brand_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="brand" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html($t->name); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php esc_html_e('Typ pojazdu', 'tyrepol'); ?></h3>
            <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="all" checked> <?php esc_html_e('Wszystkie', 'tyrepol'); ?></label>
            <?php foreach ($vehicle_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html($t->name); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php esc_html_e('Oś', 'tyrepol'); ?></h3>
            <?php foreach ($axle_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html($t->name); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php esc_html_e('Sezon', 'tyrepol'); ?></h3>
            <?php foreach ($season_terms_all as $t) : ?>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="season" value="<?php echo esc_attr($t->slug); ?>"> <?php echo esc_html($t->name); ?></label>
            <?php endforeach; ?>
          </div>

          <div class="filter__group">
            <h3 class="filter__title"><?php esc_html_e('Rozmiar', 'tyrepol'); ?></h3>
            <select class="filter__select" name="size" aria-label="<?php esc_attr_e('Rozmiar opony', 'tyrepol'); ?>">
              <option value=""><?php esc_html_e('Wszystkie rozmiary', 'tyrepol'); ?></option>
              <?php foreach ($all_sizes as $size) : ?>
                <option value="<?php echo esc_attr($size); ?>"><?php echo esc_html($size); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button class="filter__submit" type="reset"><?php esc_html_e('Wyczyść filtry', 'tyrepol'); ?></button>
        </form>

        <div class="catalog__results">
          <div class="catalog__grid" id="catalog-grid"></div>
          <p class="catalog__empty" id="catalog-empty" hidden><?php esc_html_e('Brak opon spełniających wybrane kryteria.', 'tyrepol'); ?></p>
          <div class="catalog__actions">
            <button class="catalog__load-more" id="catalog-load-more" type="button"><?php esc_html_e('Załaduj więcej opon', 'tyrepol'); ?></button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php
  $faq_items = [];
  if (have_rows('faq_pytania')) : while (have_rows('faq_pytania')) : the_row();
    $faq_items[] = ['pytanie' => get_sub_field('pytanie'), 'odpowiedz' => get_sub_field('odpowiedz')];
  endwhile; endif;
  get_template_part('template-parts/faq', null, ['title' => get_field('faq_tytul') ?: 'FAQ', 'desc' => get_field('faq_opis'), 'items' => $faq_items, 'anchor' => 'faq']);
  ?>

  <?php tyrepol_contact_section(false); ?>

  <?php endwhile; ?>

<?php get_footer(); ?>
