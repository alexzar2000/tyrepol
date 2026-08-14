<?php
/**
 * Template Name: Elastyczna strona (sekcje)
 *
 * Uniwersalny szablon strony budowanej z klocków („Elastyczna treść” w ACF) — użyj go dla
 * stron typu „O firmie”, strony marki (np. Saucerman) albo każdej przyszłej strony z podobnym
 * układem: baner, tekst, liczniki, karty, naprzemienne bloki, galeria, CTA, FAQ, kontakt.
 * Kolejność i liczbę sekcji redaktor ustawia sam w panelu — przeciągając klocki.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

<?php while (have_posts()) : the_post(); ?>

  <?php if (have_rows('sekcje_strony')) : while (have_rows('sekcje_strony')) : the_row();

    $layout = get_row_layout();

    switch ($layout) :

      case 'hero':
        $badges = [];
        if (have_rows('odznaki')) while (have_rows('odznaki')) { the_row(); $badges[] = ['tekst' => get_sub_field('tekst')]; }
        get_template_part('template-parts/hero-banner', null, [
            'eyebrow'   => get_sub_field('eyebrow'),
            'title'     => get_sub_field('tytul'),
            'lead'      => get_sub_field('lead'),
            'badges'    => $badges,
            'image'     => get_sub_field('obraz'),
            'image_fit' => get_sub_field('dopasowanie_obrazu') ?: 'cover',
        ]);
        break;

      case 'tekst':
        get_template_part('template-parts/text-block', null, [
            'eyebrow' => get_sub_field('eyebrow'),
            'title'   => get_sub_field('tytul'),
            'body'    => get_sub_field('tresc'),
            'image'   => get_sub_field('obraz'),
        ]);
        break;

      case 'liczniki':
        $items = [];
        if (have_rows('pozycje')) while (have_rows('pozycje')) {
            the_row();
            $items[] = ['ikona' => get_sub_field('ikona'), 'liczba' => get_sub_field('liczba'), 'etykieta' => get_sub_field('etykieta'), 'predkosc' => get_sub_field('predkosc')];
        }
        get_template_part('template-parts/counters', null, ['title' => get_sub_field('tytul'), 'desc' => get_sub_field('opis'), 'items' => $items]);
        break;

      case 'karty':
        $cards = [];
        if (have_rows('karty')) while (have_rows('karty')) {
            the_row();
            $cards[] = ['ikona' => get_sub_field('ikona'), 'tytul' => get_sub_field('tytul'), 'opis' => get_sub_field('opis')];
        }
        get_template_part('template-parts/values-cards', null, ['title' => get_sub_field('tytul'), 'desc' => get_sub_field('opis'), 'cards' => $cards]);
        break;

      case 'split_lista':
        $rows = [];
        if (have_rows('pozycje')) while (have_rows('pozycje')) {
            the_row();
            $rozmiary = [];
            if (have_rows('rozmiary')) while (have_rows('rozmiary')) { the_row(); $rozmiary[] = ['rozmiar' => get_sub_field('rozmiar')]; }
            $rows[] = [
                'eyebrow' => get_sub_field('eyebrow'), 'tytul' => get_sub_field('tytul'), 'tekst' => get_sub_field('tekst'),
                'rozmiary' => $rozmiary, 'link_tekst' => get_sub_field('link_tekst'), 'link_url' => get_sub_field('link_url'),
                'image' => get_sub_field('obraz'), 'reverse' => get_sub_field('odwrocony'),
            ];
        }
        get_template_part('template-parts/split-list', null, ['rows' => $rows]);
        break;

      case 'galeria':
        $items = [];
        if (have_rows('zdjecia')) while (have_rows('zdjecia')) { the_row(); $items[] = ['image' => get_sub_field('obraz'), 'podpis' => get_sub_field('podpis')]; }
        get_template_part('template-parts/gallery', null, ['title' => get_sub_field('tytul'), 'desc' => get_sub_field('opis'), 'items' => $items]);
        break;

      case 'cta':
        $buttons = [];
        if (have_rows('przyciski')) while (have_rows('przyciski')) { the_row(); $buttons[] = ['tekst' => get_sub_field('tekst'), 'url' => get_sub_field('url'), 'styl' => get_sub_field('styl')]; }
        get_template_part('template-parts/cta-block', null, ['title' => get_sub_field('tytul'), 'desc' => get_sub_field('opis'), 'buttons' => $buttons]);
        break;

      case 'faq':
        $items = [];
        if (have_rows('pytania')) while (have_rows('pytania')) { the_row(); $items[] = ['pytanie' => get_sub_field('pytanie'), 'odpowiedz' => get_sub_field('odpowiedz')]; }
        get_template_part('template-parts/faq', null, ['title' => get_sub_field('tytul'), 'desc' => get_sub_field('opis'), 'items' => $items, 'anchor' => 'faq']);
        break;

      case 'przycisk_cta':
        get_template_part('template-parts/section-cta-button', null, ['tekst' => get_sub_field('tekst')]);
        break;

      case 'kontakt':
        tyrepol_contact_section(false);
        break;

    endswitch;

  endwhile; else :
    // Bez skonfigurowanych sekcji -> pokaż zwykłą treść strony (edytor WordPress), żeby strona nigdy nie była pusta.
    the_content();
  endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
