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
            // Ikony stałych kolumn (te same SVG, co wcześniej — tylko teraz w zmiennych, żeby dało
            // się je złożyć w tablicę $fixed_defs poniżej).
            $fuel_icon    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"></path><path d="M3 10h9"></path><path d="M15 6h2a2 2 0 0 1 2 2v3a2 2 0 0 0 2 2v5a2 2 0 0 1-2 2"></path><rect x="6" y="13" width="4" height="4"></rect></svg>';
            $wet_icon     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s7 8.5 7 13a7 7 0 0 1-14 0c0-4.5 7-13 7-13z"></path></svg>';
            $noise_icon   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5 6 9H2v6h4l5 4V5z"></path><path d="M15.5 8.5a5 5 0 0 1 0 7"></path><path d="M18.5 5.5a9 9 0 0 1 0 13"></path></svg>';
            $rolling_icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3"></circle><path d="M12 3v2M12 19v2M21 12h-2M5 12H3"></path></svg>';
            $snow_icon    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="12" y1="2" x2="12" y2="22"></line><line x1="4" y1="7" x2="20" y2="17"></line><line x1="20" y1="7" x2="4" y2="17"></line></svg>';

            // Definicje stałych kolumn — 'get' pobiera wartość dla danego ID wariantu, 'check'
            // oznacza kolumny wyświetlane jako kolorowy „✓ / —” (jak dotychczas przy M+S/3PMSF).
            $fixed_defs = [
                ['label' => __('Seria', 'tyrepol'), 'type' => 'text', 'get' => fn($id) => get_field('wzor_bieznika', $id) ?: $wzor],
                ['label' => __('Rozmiar', 'tyrepol'), 'type' => 'text', 'get' => fn($id) => get_field('rozmiar', $id)],
                ['label' => 'LI / SR', 'type' => 'text', 'get' => fn($id) => get_field('li_sr', $id)],
                ['label' => __('Głębokość bieżnika (mm)', 'tyrepol'), 'type' => 'text', 'get' => fn($id) => get_field('glebokosc_biezn', $id)],
                ['label' => __('Zużycie paliwa', 'tyrepol'), 'type' => 'icon', 'icon_svg' => $fuel_icon, 'get' => fn($id) => get_field('zuzycie_paliwa', $id)],
                ['label' => __('Przyczepność na mokrej nawierzchni', 'tyrepol'), 'type' => 'icon', 'icon_svg' => $wet_icon, 'get' => fn($id) => get_field('przyczepnosc_mokra', $id)],
                ['label' => __('Hałas zewnętrzny (dB)', 'tyrepol'), 'type' => 'icon', 'icon_svg' => $noise_icon, 'get' => fn($id) => get_field('halas_db', $id)],
                ['label' => __('Klasa oporu toczenia', 'tyrepol'), 'type' => 'icon', 'icon_svg' => $rolling_icon, 'get' => fn($id) => get_field('opor_toczenia', $id)],
                ['label' => 'M+S', 'type' => 'badge', 'check' => true, 'get' => fn($id) => get_field('oznaczenie_ms', $id) ? '✓' : ''],
                ['label' => '3PMSF', 'type' => 'icon', 'icon_svg' => $snow_icon, 'check' => true, 'get' => fn($id) => get_field('oznaczenie_3pmsf', $id) ? '✓' : ''],
            ];

            $variant_ids = wp_list_pluck($variants, 'ID');

            // Kolumna trafia do tabeli tylko wtedy, gdy CHOĆ JEDEN wariant ma w niej wartość —
            // dzięki temu np. brak wypełnionego „Hałasu” u WSZYSTKICH wariantów usuwa całą kolumnę,
            // a jeśli wypełniony jest choć u jednego, reszta wierszy w tej kolumnie dostaje „—”.
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
                $columns[] = [
                    'label'    => $def['label'],
                    'type'     => $def['type'],
                    'icon_svg' => $def['icon_svg'] ?? null,
                    'icon_id'  => null,
                    'check'    => !empty($def['check']),
                    'values'   => $values,
                ];
            }

            // Dołączamy dodatkowe parametry z rejestru „Cechy opon” (nieograniczona liczba —
            // patrz inc/cechy-opony.php). Ten sam warunek: kolumna tylko jeśli choć jeden wariant
            // ma wypełnioną wartość.
            if (function_exists('tyrepol_get_opona_cechy_kolumny')) {
                foreach (tyrepol_get_opona_cechy_kolumny($variant_ids) as $col) {
                    $col['check'] = false;
                    $columns[] = $col;
                }
            }

            // Numerujemy kolumny typu „ikona” do legendy pod tabelą (fixed + z rejestru — wspólna
            // numeracja w kolejności, w jakiej kolumny faktycznie występują w tabeli).
            $legend = [];
            $marks = ['¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'];
            foreach ($columns as &$col) {
                if ($col['type'] === 'icon') {
                    $mark = $marks[count($legend)] ?? ('(' . (count($legend) + 1) . ')');
                    $col['mark'] = $mark;
                    $legend[] = $mark . ' ' . $col['label'];
                }
            }
            unset($col);
            ?>
            <table class="tire-detail__table">
              <thead>
                <tr>
                  <?php foreach ($columns as $col) : ?>
                  <th scope="col">
                    <?php if ($col['type'] === 'icon') : ?>
                      <span class="tire-detail__icon" title="<?php echo esc_attr($col['label']); ?>">
                        <?php if (!empty($col['icon_svg'])) : ?>
                          <?php echo $col['icon_svg']; ?>
                        <?php elseif (!empty($col['icon_id'])) : ?>
                          <?php echo wp_get_attachment_image($col['icon_id'], 'thumbnail', false, ['class' => 'tire-detail__icon-img']); ?>
                        <?php endif; ?>
                      </span><sup class="tire-detail__mark"><?php echo esc_html($col['mark']); ?></sup>
                    <?php elseif ($col['type'] === 'badge') : ?>
                      <span class="tire-detail__ms-badge" title="<?php echo esc_attr($col['label']); ?>"><?php echo esc_html($col['label']); ?></span>
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
                  <td data-label="<?php echo esc_attr($col['label']); ?>"<?php echo $col['check'] ? ' class="tire-detail__check"' : ''; ?>><?php echo esc_html($val !== '' ? $val : '—'); ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php if (!empty($legend)) : ?>
            <p class="tire-detail__legend"><?php echo esc_html(implode('   ', $legend)); ?></p>
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
