<?php
/**
 * Template Name: Elastyczna strona (sekcje)
 *
 * Uniwersalny szablon strony budowanej z gotowych sekcji (pola ACF, zakładki w edytorze) —
 * użyj go dla stron typu „O firmie”, strony marki (np. Saucerman) albo każdej przyszłej strony
 * z podobnym układem: baner, tekst, liczniki, karty cech / naprzemienne bloki, galeria, CTA, FAQ, kontakt.
 *
 * Uwaga (ACF Free — bez PRO): kolejność większości sekcji jest STAŁA (patrz opis grupy pól
 * w acf-json/group_elastyczna.json) — ACF Free nie ma pola „Elastyczna treść” do przeciągania
 * klocków. Jedyne dwie sekcje, którymi można zamienić kolejność, to „Karty cech” i „Naprzemienne
 * bloki” — decyduje pole „Kolejność” w każdej z nich. Sekcję, której strona nie potrzebuje,
 * wystarczy zostawić wyłączoną („Pokaż tę sekcję” = nie) — wtedy po prostu się nie wyświetli.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

<?php while (have_posts()) : the_post(); ?>

  <?php
  $ma_sekcje = false;

  // 1) Baner (hero)
  $sec = get_field('sekcja_hero');
  if (!empty($sec['pokaz']) && !empty($sec['tytul'])) :
    $ma_sekcje = true;
    $badges = [];
    if (!empty($sec['odznaki_tekst'])) {
        foreach (explode(',', $sec['odznaki_tekst']) as $b) {
            $b = trim($b);
            if ($b !== '') $badges[] = ['tekst' => $b];
        }
    }
    get_template_part('template-parts/hero-banner', null, [
        'eyebrow'   => $sec['eyebrow'] ?? '',
        'title'     => $sec['tytul'] ?? '',
        'lead'      => $sec['lead'] ?? '',
        'badges'    => $badges,
        'image'     => $sec['obraz'] ?? null,
        'image_fit' => $sec['dopasowanie_obrazu'] ?: 'cover',
    ]);
  endif;

  // 2) Tekst + zdjęcie
  $sec = get_field('sekcja_tekst');
  if (!empty($sec['pokaz']) && !empty($sec['tytul'])) :
    $ma_sekcje = true;
    get_template_part('template-parts/text-block', null, [
        'eyebrow' => $sec['eyebrow'] ?? '',
        'title'   => $sec['tytul'] ?? '',
        'body'    => $sec['tresc'] ?? '',
        'image'   => $sec['obraz'] ?? null,
    ]);
  endif;

  // 3) Liczniki (do 6 stałych slotów zamiast Repeatera)
  $sec = get_field('sekcja_liczniki');
  if (!empty($sec['pokaz'])) :
    $items = [];
    for ($i = 1; $i <= 6; $i++) {
        $row = $sec['licznik_' . $i] ?? null;
        if (!empty($row['etykieta'])) $items[] = $row;
    }
    if (!empty($items)) :
      $ma_sekcje = true;
      get_template_part('template-parts/counters', null, ['title' => $sec['tytul'] ?? '', 'desc' => $sec['opis'] ?? '', 'items' => $items]);
    endif;
  endif;

  // 4) Przycisk CTA #1 (między sekcjami)
  $sec = get_field('cta_miedzysekcyjny_1');
  if (!empty($sec['pokaz']) && !empty($sec['tekst'])) :
    $ma_sekcje = true;
    get_template_part('template-parts/section-cta-button', null, ['tekst' => $sec['tekst']]);
  endif;

  // 5) „Karty cech” i „Naprzemienne bloki” — kolejność ustala pole „kolejność” (mniejsza liczba = wyżej)
  $karty = get_field('sekcja_karty');
  $split = get_field('sekcja_split');

  $blok_karty = null;
  if (!empty($karty['pokaz'])) {
      $cards = [];
      for ($i = 1; $i <= 6; $i++) {
          $row = $karty['karta_' . $i] ?? null;
          if (!empty($row['tytul'])) $cards[] = $row;
      }
      if (!empty($cards)) {
          $blok_karty = ['kolejnosc' => (int) ($karty['kolejnosc'] ?? 10), 'cards' => $cards, 'title' => $karty['tytul'] ?? '', 'desc' => $karty['opis'] ?? ''];
      }
  }

  $blok_split = null;
  if (!empty($split['pokaz'])) {
      $rows = [];
      for ($i = 1; $i <= 4; $i++) {
          $blok = $split['blok_' . $i] ?? null;
          if (empty($blok['tytul'])) continue;
          $rozmiary = [];
          if (!empty($blok['rozmiary_tekst'])) {
              foreach (explode(',', $blok['rozmiary_tekst']) as $r) {
                  $r = trim($r);
                  if ($r !== '') $rozmiary[] = ['rozmiar' => $r];
              }
          }
          $rows[] = [
              'eyebrow'    => $blok['eyebrow'] ?? '',
              'tytul'      => $blok['tytul'] ?? '',
              'tekst'      => $blok['tekst'] ?? '',
              'rozmiary'   => $rozmiary,
              'link_tekst' => $blok['link_tekst'] ?? '',
              'link_url'   => $blok['link_url'] ?? '',
              'image'      => $blok['obraz'] ?? null,
              'reverse'    => !empty($blok['odwrocony']),
          ];
      }
      if (!empty($rows)) {
          $blok_split = ['kolejnosc' => (int) ($split['kolejnosc'] ?? 20), 'rows' => $rows];
      }
  }

  $bloki_karty_split = array_filter([$blok_karty, $blok_split]);
  usort($bloki_karty_split, function ($a, $b) { return $a['kolejnosc'] <=> $b['kolejnosc']; });

  foreach ($bloki_karty_split as $blok) :
    $ma_sekcje = true;
    if (isset($blok['cards'])) :
      get_template_part('template-parts/values-cards', null, ['title' => $blok['title'], 'desc' => $blok['desc'], 'cards' => $blok['cards']]);
    else :
      get_template_part('template-parts/split-list', null, ['rows' => $blok['rows']]);
    endif;
  endforeach;

  // 6) Przycisk CTA #2 (między sekcjami)
  $sec = get_field('cta_miedzysekcyjny_2');
  if (!empty($sec['pokaz']) && !empty($sec['tekst'])) :
    $ma_sekcje = true;
    get_template_part('template-parts/section-cta-button', null, ['tekst' => $sec['tekst']]);
  endif;

  // 7) Galeria (do 8 stałych slotów zamiast Repeatera)
  $sec = get_field('sekcja_galeria');
  if (!empty($sec['pokaz'])) :
    $items = [];
    for ($i = 1; $i <= 8; $i++) {
        $row = $sec['zdjecie_' . $i] ?? null;
        if (!empty($row['obraz'])) $items[] = $row;
    }
    if (!empty($items)) :
      $ma_sekcje = true;
      get_template_part('template-parts/gallery', null, ['title' => $sec['tytul'] ?? '', 'desc' => $sec['opis'] ?? '', 'items' => $items]);
    endif;
  endif;

  // 8) CTA końcowe (bez repeatera — maks. 2 stałe przyciski)
  $sec = get_field('sekcja_cta');
  if (!empty($sec['pokaz'])) :
    $buttons = [];
    if (!empty($sec['przycisk_1_tekst'])) $buttons[] = ['tekst' => $sec['przycisk_1_tekst'], 'url' => $sec['przycisk_1_url'] ?? '#', 'styl' => 'primary'];
    if (!empty($sec['przycisk_2_tekst'])) $buttons[] = ['tekst' => $sec['przycisk_2_tekst'], 'url' => $sec['przycisk_2_url'] ?? '#', 'styl' => 'outline'];
    if (!empty($sec['tytul']) || !empty($buttons)) :
      $ma_sekcje = true;
      get_template_part('template-parts/cta-block', null, ['title' => $sec['tytul'] ?? '', 'desc' => $sec['opis'] ?? '', 'buttons' => $buttons]);
    endif;
  endif;

  // 9) FAQ (do 8 stałych slotów zamiast Repeatera)
  $sec = get_field('sekcja_faq');
  if (!empty($sec['pokaz'])) :
    $items = [];
    for ($i = 1; $i <= 8; $i++) {
        $row = $sec['pytanie_' . $i] ?? null;
        if (!empty($row['pytanie'])) $items[] = $row;
    }
    if (!empty($items)) :
      $ma_sekcje = true;
      get_template_part('template-parts/faq', null, ['title' => $sec['tytul'] ?: 'FAQ', 'desc' => $sec['opis'] ?? '', 'items' => $items, 'anchor' => 'faq']);
    endif;
  endif;

  // 10) Kontakt (dane z „Ustawienia motywu”)
  $sec = get_field('sekcja_kontakt');
  if (!empty($sec['pokaz'])) :
    $ma_sekcje = true;
    tyrepol_contact_section(false);
  endif;

  // Bez skonfigurowanych sekcji -> pokaż zwykłą treść strony (edytor WordPress), żeby strona nigdy nie była pusta.
  if (!$ma_sekcje) : ?>
    <section class="legal legal--top">
      <div class="legal__inner legal__content">
        <?php the_content(); ?>
      </div>
    </section>
  <?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
