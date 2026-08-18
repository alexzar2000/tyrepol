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
            <?php
            // Kolumny stałe — TYLKO Seria (wzór bieżnika) i Rozmiar. Wszystkie pozostałe dane
            // (LI/SR, głębokość bieżnika, etykieta UE, M+S, 3PMSF itd.) to już wyłącznie parametry
            // z rejestru „Cechy opon” (patrz inc/cechy-opony.php) — admin definiuje je sam, w
            // dowolnej liczbie, z wyborem tekst/ikona.
            $fixed_defs = [
                ['label' => __('Seria', 'tyrepol'), 'get' => fn($id) => get_field('wzor_bieznika', $id) ?: $wzor],
                ['label' => __('Rozmiar', 'tyrepol'), 'get' => fn($id) => get_field('rozmiar', $id)],
            ];

            $variant_ids = wp_list_pluck($variants, 'ID');

            // Kolumna trafia do tabeli tylko wtedy, gdy CHOĆ JEDEN wariant ma w niej wartość —
            // puste u wszystkich = kolumna w ogóle się nie pojawia; jeśli wypełniona choć u
            // jednego, pozostałe wiersze w tej kolumnie dostają „—”.
            $columns = [];
            foreach ($fixed_defs as $def) {
                $values = [];
                $has_value = false;
                foreach ($variant_ids as $v_id) {
                    $val = (string) $def['get']($v_id);
                    $values[$v_id] = $val;
                    if ($val !== '') $has_value = true;
                }
                if (!$has_value) continue;
                $columns[] = ['label' => $def['label'], 'type' => 'text', 'icon_id' => null, 'values' => $values];
            }

            // Dołączamy parametry z rejestru „Cechy opon” (ten sam warunek: kolumna tylko jeśli
            // choć jeden wariant ma wypełnioną wartość).
            if (function_exists('tyrepol_get_opona_cechy_kolumny')) {
                foreach (tyrepol_get_opona_cechy_kolumny($variant_ids) as $col) {
                    $columns[] = $col;
                }
            }

            // Legenda pod tabelą — dla każdej kolumny typu „ikona” pokazujemy TĘ SAMĄ ikonę razem
            // z nazwą parametru (zamiast numerków), więc od razu widać co dana ikona oznacza.
            $legend = array_values(array_filter($columns, fn($c) => $c['type'] === 'icon' && !empty($c['icon_id'])));
            ?>
            <table class="tire-detail__table">
              <thead>
                <tr>
                  <?php foreach ($columns as $col) : ?>
                  <th scope="col">
                    <?php if ($col['type'] === 'icon' && !empty($col['icon_id'])) : ?>
                      <span class="tire-detail__icon" title="<?php echo esc_attr($col['label']); ?>">
                        <?php echo wp_get_attachment_image($col['icon_id'], 'thumbnail', false, ['class' => 'tire-detail__icon-img', 'alt' => esc_attr($col['label'])]); ?>
                      </span>
                    <?php else : ?>
                      <?php echo esc_html($col['label']); ?>
                    <?php endif; ?>
                  </th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($variants as $variant) :
                  $v_id = $variant->ID;
                  $v_current = ((int) $v_id === (int) get_the_ID());
                ?>
                <tr<?php echo $v_current ? ' class="tire-detail__row--current"' : ''; ?>>
                  <?php foreach ($columns as $col) :
                    $val = $col['values'][$v_id] ?? '';
                  ?>
                  <td data-label="<?php echo esc_attr($col['label']); ?>"><?php echo esc_html($val !== '' ? $val : '—'); ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php if (!empty($legend)) : ?>
            <ul class="tire-detail__legend">
              <?php foreach ($legend as $col) : ?>
              <li class="tire-detail__legend-item">
                <?php echo wp_get_attachment_image($col['icon_id'], 'thumbnail', false, ['class' => 'tire-detail__legend-icon', 'alt' => '']); ?>
                <span><?php echo esc_html($col['label']); ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
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
