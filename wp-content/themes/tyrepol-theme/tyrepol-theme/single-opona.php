<?php
/**
 * Szczegóły pojedynczej opony — a właściwie szczegóły CAŁEGO MODELU. Jeden wpis CPT „Opona” =
 * jeden rozmiar/wariant, ale dokładnie jak w dawnej wersji statycznej, strona produktu pokazuje
 * od razu WSZYSTKIE rozmiary tego samego modelu (ten sam „Wzór bieżnika” + ta sama „Marka”)
 * razem w jednej tabeli — każdy rozmiar to osobny wiersz, ale nadal osobno edytowalny w panelu
 * (własne dane UE dla danego rozmiaru). Zdjęcie i tytuł strony biorą się z PIERWSZEGO wariantu
 * (tego, na który prowadzi karta w katalogu) — patrz $variants niżej.
 */
if (!defined('ABSPATH')) exit;
get_header();

while (have_posts()) : the_post();
    $brand_terms   = get_the_terms(get_the_ID(), 'marka-opony');
    $axle_terms    = get_the_terms(get_the_ID(), 'os-montazu');
    $vehicle_terms = get_the_terms(get_the_ID(), 'typ-pojazdu');
    $brand  = ($brand_terms && !is_wp_error($brand_terms)) ? $brand_terms[0] : null;
    $axle   = ($axle_terms && !is_wp_error($axle_terms)) ? $axle_terms[0] : null;
    $vehicle = ($vehicle_terms && !is_wp_error($vehicle_terms)) ? $vehicle_terms[0] : null;

    $catalog_url = tyrepol_catalog_url();
    $wzor = get_field('wzor_bieznika') ?: get_the_title();

    // Wszystkie wpisy tego samego modelu (ten sam wzór bieżnika + ta sama marka) — to one
    // tworzą razem wiersze tabeli poniżej. Jeśli marka nie jest ustawiona, dopasowanie idzie
    // tylko po wzorze bieżnika (rzadki przypadek, ale nie chcemy przez to ukryć żadnego wiersza).
    $variant_query_args = [
        'post_type'      => 'opona',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'meta_query'     => [
            ['key' => 'wzor_bieznika', 'value' => $wzor, 'compare' => '='],
        ],
    ];
    if ($brand) {
        $variant_query_args['tax_query'] = [
            ['taxonomy' => 'marka-opony', 'field' => 'term_id', 'terms' => $brand->term_id],
        ];
    }
    $variants = get_posts($variant_query_args);
    if (empty($variants)) $variants = [get_post()]; // zabezpieczenie — przynajmniej bieżący wpis
?>

  <nav class="breadcrumb" aria-label="<?php esc_attr_e('Okruszki nawigacyjne', 'tyrepol'); ?>">
    <div class="breadcrumb__inner">
      <ol class="breadcrumb__list">
        <li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo esc_url($catalog_url); ?>"><?php esc_html_e('Wszystkie opony', 'tyrepol'); ?></a></li>
        <?php if ($vehicle) : ?>
        <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
        <li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo esc_url(add_query_arg('vehicle', $vehicle->slug, $catalog_url)); ?>"><?php echo esc_html($vehicle->name); ?></a></li>
        <?php endif; ?>
        <?php if ($brand) : ?>
        <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
        <li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo esc_url(add_query_arg(['vehicle' => $vehicle ? $vehicle->slug : '', 'brand' => $brand->slug], $catalog_url)); ?>"><?php echo esc_html($brand->name); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
        <li class="breadcrumb__item breadcrumb__item--current" aria-current="page"><?php echo esc_html(($brand ? $brand->name . ' ' : '') . $wzor); ?></li>
      </ol>
    </div>
  </nav>

  <section class="tire-detail" id="produkt">
    <div class="tire-detail__inner">
      <div class="tire-detail__layout">

        <div class="tire-detail__media reveal">
          <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['class' => 'tire-detail__img']);
          else : ?>
            <div class="tire-card__placeholder" aria-hidden="true">
              <svg width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3.5"></circle></svg>
            </div>
          <?php endif; ?>
        </div>

        <div class="tire-detail__panel reveal">
          <span class="tire-detail__badge"><?php echo esc_html($wzor); ?></span>

          <div class="tire-detail__table-wrap">
            <table class="tire-detail__table">
              <thead>
                <tr>
                  <th scope="col"><?php esc_html_e('Seria', 'tyrepol'); ?></th>
                  <th scope="col"><?php esc_html_e('Rozmiar', 'tyrepol'); ?></th>
                  <th scope="col"><?php esc_html_e('LI / SR', 'tyrepol'); ?></th>
                  <th scope="col"><?php esc_html_e('Głębokość bieżnika (mm)', 'tyrepol'); ?></th>
                  <th scope="col"><span class="tire-detail__icon" title="<?php esc_attr_e('Zużycie paliwa', 'tyrepol'); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"></path><path d="M3 10h9"></path><path d="M15 6h2a2 2 0 0 1 2 2v3a2 2 0 0 0 2 2v5a2 2 0 0 1-2 2"></path><rect x="6" y="13" width="4" height="4"></rect></svg></span></th>
                  <th scope="col"><span class="tire-detail__icon" title="<?php esc_attr_e('Przyczepność na mokrej nawierzchni', 'tyrepol'); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s7 8.5 7 13a7 7 0 0 1-14 0c0-4.5 7-13 7-13z"></path></svg></span></th>
                  <th scope="col"><span class="tire-detail__icon" title="<?php esc_attr_e('Hałas zewnętrzny (dB)', 'tyrepol'); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5 6 9H2v6h4l5 4V5z"></path><path d="M15.5 8.5a5 5 0 0 1 0 7"></path><path d="M18.5 5.5a9 9 0 0 1 0 13"></path></svg></span></th>
                  <th scope="col"><span class="tire-detail__icon" title="<?php esc_attr_e('Klasa oporu toczenia', 'tyrepol'); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3"></circle><path d="M12 3v2M12 19v2M21 12h-2M5 12H3"></path></svg></span></th>
                  <th scope="col"><span class="tire-detail__ms-badge" title="M+S">M+S</span></th>
                  <th scope="col"><span class="tire-detail__icon" title="3PMSF"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="12" y1="2" x2="12" y2="22"></line><line x1="4" y1="7" x2="20" y2="17"></line><line x1="20" y1="7" x2="4" y2="17"></line></svg></span></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($variants as $variant) :
                  $v_id    = $variant->ID;
                  $v_wzor  = get_field('wzor_bieznika', $v_id) ?: $wzor;
                  $v_current = ((int) $v_id === (int) get_the_ID());
                ?>
                <tr<?php echo $v_current ? ' class="tire-detail__row--current"' : ''; ?>>
                  <td data-label="<?php esc_attr_e('Seria', 'tyrepol'); ?>"><?php echo esc_html($v_wzor); ?></td>
                  <td data-label="<?php esc_attr_e('Rozmiar', 'tyrepol'); ?>"><?php echo esc_html(get_field('rozmiar', $v_id)); ?></td>
                  <td data-label="LI / SR"><?php echo esc_html(get_field('li_sr', $v_id)); ?></td>
                  <td data-label="<?php esc_attr_e('Głębokość bieżnika', 'tyrepol'); ?>"><?php echo esc_html(get_field('glebokosc_biezn', $v_id)); ?></td>
                  <td data-label="<?php esc_attr_e('Zużycie paliwa', 'tyrepol'); ?>"><?php echo esc_html(get_field('zuzycie_paliwa', $v_id)); ?></td>
                  <td data-label="<?php esc_attr_e('Przyczepność na mokrej nawierzchni', 'tyrepol'); ?>"><?php echo esc_html(get_field('przyczepnosc_mokra', $v_id)); ?></td>
                  <td data-label="<?php esc_attr_e('Hałas', 'tyrepol'); ?>"><?php echo esc_html(get_field('halas_db', $v_id)); ?></td>
                  <td data-label="<?php esc_attr_e('Opór toczenia', 'tyrepol'); ?>"><?php echo esc_html(get_field('opor_toczenia', $v_id)); ?></td>
                  <td data-label="M+S" class="tire-detail__check"><?php echo get_field('oznaczenie_ms', $v_id) ? '✓' : '—'; ?></td>
                  <td data-label="3PMSF" class="tire-detail__check"><?php echo get_field('oznaczenie_3pmsf', $v_id) ? '✓' : '—'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="tire-detail__cta-wrap">
            <button class="tire-detail__cta" type="button" data-modal-open="quote-modal"><?php esc_html_e('Darmowa wycena', 'tyrepol'); ?></button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php tyrepol_contact_section(false); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
