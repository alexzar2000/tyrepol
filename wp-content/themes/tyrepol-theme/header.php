<?php
/**
 * Nagłówek — identyczny HTML/CSS jak w statycznej wersji, tylko menu i przełącznik języka
 * są teraz prawdziwe (Wygląd → Menu, wtyczka Polylang), a nie zaszyte na sztywno w kodzie.
 */
if (!defined('ABSPATH')) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <header class="header">
    <div class="header__inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo">
        <?php if (has_custom_logo()) :
            $logo_id = get_theme_mod('custom_logo');
            echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'header__logo-img']);
        else : ?>
          <img class="header__logo-img" src="<?php echo esc_url(TYREPOL_URI . '/assets/images/logo.png'); ?>" alt="<?php tyrepol_esc_attr_e('TyrePol Logo', 'TyrePol Logo'); ?>">
        <?php endif; ?>
      </a>

      <nav class="header__nav" id="header-nav">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '<ul class="header__list">%3$s</ul>',
                'walker'         => new TyrePol_Nav_Walker(),
            ]);
        } else {
            tyrepol_fallback_menu();
        }
        ?>
      </nav>

      <div class="header__actions">
        <?php
        // Strona pojedynczej opony to SZCZEGÓLNY przypadek: CPT „opona” nie jest zarządzany przez
        // Polylang (patrz inc/cpt-opona.php), więc pll_the_languages() nic by tu nie znalazł i
        // przełącznik prowadziłby donikąd (albo na stronę główną — stąd wrażenie „wyrzuca ze
        // strony”). Budujemy więc PL/EN adresy TEJ SAMEJ opony ręcznie, przez tyrepol_opona_permalink().
        if (is_singular('opona')) {
            $show_lang_switch = true;
            $current_lang = tyrepol_current_lang();
            $lang_pl_url = tyrepol_opona_permalink(get_the_ID(), 'pl');
            $lang_en_url = tyrepol_opona_permalink(get_the_ID(), 'en');
        } else {
            $show_lang_switch = function_exists('pll_the_languages');
            $languages = $show_lang_switch ? pll_the_languages(['raw' => 1, 'hide_if_empty' => 0]) : [];
            $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'pl';
            $lang_en_url = '';
            $lang_pl_url = '';
            foreach ($languages as $lang) {
                if ($lang['slug'] === 'en') $lang_en_url = $lang['url'];
                if ($lang['slug'] === 'pl') $lang_pl_url = $lang['url'];
            }
        }
        ?>
        <div class="header__lang-switch" data-lang-pl="<?php echo esc_url($lang_pl_url); ?>" data-lang-en="<?php echo esc_url($lang_en_url); ?>">
          <input id="lang-toggle" class="header__lang-input" type="checkbox" aria-label="<?php tyrepol_esc_attr_e('Przełącz język PL / EN', 'Switch language PL / EN'); ?>" <?php checked($current_lang, 'en'); ?> <?php disabled(!$show_lang_switch || !$lang_en_url || !$lang_pl_url); ?>>
          <label for="lang-toggle" class="header__lang-track"></label>
          <span class="header__lang-text header__lang-text--pl">PL</span>
          <span class="header__lang-text header__lang-text--en">EN</span>
        </div>
        <button id="hamburger" class="header__burger" type="button" aria-label="<?php tyrepol_esc_attr_e('Otwórz menu', 'Open menu'); ?>" aria-expanded="false" aria-controls="header-nav">
          <span class="header__burger-line"></span>
          <span class="header__burger-line"></span>
          <span class="header__burger-line"></span>
        </button>
      </div>
    </div>
  </header>

  <main>
