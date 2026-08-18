<?php
/**
 * Strona główna — zawartość edytowalna polami ACF (grupa „Strona główna”, patrz acf-json).
 * WordPress użyje tego pliku automatycznie dla strony ustawionej w Ustawienia → Czytanie
 * jako „Strona główna wyświetla: Stronę statyczną”.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

<?php while (have_posts()) : the_post(); ?>

  <section class="hero" id="hero">
    <div class="hero__swiper swiper">
      <div class="swiper-wrapper">
        <?php
        // WP: nieograniczona liczba slajdów — osobny typ wpisu „Slajd hero” (Slajdy hero w menu
        // panelu) zamiast stałej liczby pól ACF, żeby można było dodać ich dowolnie dużo.
        // Kolejność ustala pole „Kolejność” (page-attributes, wbudowane w WordPressa).
        $hero_slides = get_posts([
            'post_type'      => 'slajd_hero',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);
        foreach ($hero_slides as $slide) :
          $tytul = get_the_title($slide);
          if ($tytul === '') continue;
          $opis      = get_field('opis', $slide->ID);
          $link_tekst = get_field('link_tekst', $slide->ID);
          $link_url   = get_field('link_url', $slide->ID);
        ?>
        <div class="hero__slide swiper-slide">
          <div class="hero__image-wrap">
            <?php echo get_the_post_thumbnail($slide->ID, 'full', ['class' => 'hero__image', 'loading' => 'eager']); ?>
          </div>
          <div class="hero__content">
            <h1 class="hero__title" data-swiper-parallax-y="-120" data-swiper-parallax-duration="1200"><?php echo esc_html($tytul); ?></h1>
            <p class="hero__desc" data-swiper-parallax-y="-160" data-swiper-parallax-duration="1400"><?php echo esc_html($opis); ?></p>
            <?php if (!empty($link_url)) : ?>
            <div class="hero__link-wrap" data-swiper-parallax-y="-200" data-swiper-parallax-duration="1500">
              <a class="hero__link" href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($link_tekst ?: __('Sprawdź ofertę', 'tyrepol')); ?>
                <svg class="hero__link-icon" width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                  <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                    <circle class="hero__link-circle" cx="16" cy="16" r="15.12"></circle>
                    <path d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                  </g>
                </svg>
              </a>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="hero__scroll">
      <a class="hero__scroll-btn" href="#faq" aria-label="<?php esc_attr_e('Przewiń w dół', 'tyrepol'); ?>">
        <span class="hero__scroll-fill" aria-hidden="true"></span>
        <svg class="hero__scroll-icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M2 6l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </a>
    </div>

    <div class="hero__nav-pag">
      <div class="hero__nav">
        <button class="hero__nav-btn hero__nav-btn--prev" type="button" aria-label="<?php esc_attr_e('Poprzedni slajd', 'tyrepol'); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 2v12M8 2L3 7M8 2l5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>
        <button class="hero__nav-btn hero__nav-btn--next" type="button" aria-label="<?php esc_attr_e('Następny slajd', 'tyrepol'); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 14V2M8 14l-5-5M8 14l5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>
      </div>
      <div class="hero__pagination swiper-pagination"></div>
    </div>
  </section>

  <section class="brands" id="marki-carousel">
    <div class="brands__inner">

      <div class="brands__header reveal">
        <h2 class="brands__title"><?php echo esc_html(get_field('marki_naglowek') ?: __('Marki w naszej ofercie', 'tyrepol')); ?></h2>
        <p class="brands__desc"><?php echo esc_html(get_field('marki_opis')); ?></p>
      </div>

      <div class="brands__carousel reveal">
        <button class="brands__nav brands__nav--prev" type="button" aria-label="<?php esc_attr_e('Poprzedni slajd', 'tyrepol'); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 2 4 8l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>

        <div class="brands__swiper swiper">
          <div class="swiper-wrapper">
            <?php
            // Marki pobierane bezpośrednio z taksonomii „Marka” (Opony → Marki) — logo ustawia się
            // raz, edytując dany termin (pole „Logo marki”), a nie osobno na stronie głównej.
            $brand_terms = get_terms(['taxonomy' => 'marka-opony', 'hide_empty' => false]);
            foreach ($brand_terms as $term) :
              $logo = function_exists('get_field') ? get_field('logo', 'marka-opony_' . $term->term_id) : null;
              if (!$logo) continue;
              $marka_url = add_query_arg('marka', $term->slug, tyrepol_catalog_url());
            ?>
            <a class="brands__slide swiper-slide" href="<?php echo esc_url($marka_url); ?>">
              <span class="brands__logo-box">
                <?php echo wp_get_attachment_image($logo, 'medium', false, ['class' => 'brands__logo', 'alt' => sprintf(__('Logo marki %s', 'tyrepol'), $term->name)]); ?>
              </span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <button class="brands__nav brands__nav--next" type="button" aria-label="<?php esc_attr_e('Następny slajd', 'tyrepol'); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 2l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>
      </div>

      <div class="brands__pagination swiper-pagination"></div>

    </div>
  </section>

  <?php get_template_part('template-parts/section-cta-button', null, ['tekst' => get_field('cta_miedzysekcyjne_tekst') ?: __('Darmowa wycena', 'tyrepol')]); ?>

  <?php
  // WP: 6 stałych slotów liczników (zamiast Repeatera) — puste (bez podpisu) się pomijają.
  $counter_items = [];
  for ($i = 1; $i <= 6; $i++) {
      $row = get_field('licznik_' . $i);
      if (!empty($row['etykieta'])) $counter_items[] = $row;
  }
  get_template_part('template-parts/counters', null, ['title' => get_field('liczniki_tytul'), 'desc' => get_field('liczniki_opis'), 'items' => $counter_items, 'bg' => get_field('liczniki_tlo')]);
  ?>

  <?php
  // FAQ jest teraz WSPÓLNE dla wszystkich stron — edytuje się raz w Ustawienia motywu → FAQ,
  // a treść pociąga się automatycznie tutaj i na każdej innej stronie (patrz tyrepol_faq_section()).
  tyrepol_faq_section('faq');
  ?>

  <?php tyrepol_contact_section(false); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
