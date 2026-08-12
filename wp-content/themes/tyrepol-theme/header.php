<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="TyrePol — sprzedaż opon i felg. Sprawdź naszą ofertę marek, poradniki i aktualności.">
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  
  <?php wp_head(); ?> 
</head>
<body <?php body_class(); ?>>

  <header class="header">
    <div class="header__inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo">
        <img class="header__logo-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="TyrePol Logo">
      </a>

      <nav class="header__nav" id="header-nav">
        <ul class="header__list">
          <li class="header__item">
            <a class="header__link <?php echo is_front_page() ? 'header__link--active' : ''; ?>" href="<?php echo esc_url(home_url('/')); ?>">Główna 1</a>
          </li>
          <li class="header__item">
            <a class="header__link" href="<?php echo esc_url(home_url('/opony/')); ?>">Opony / Marki</a>
          </li>
          <li class="header__item">
            <a class="header__link" href="<?php echo esc_url(home_url('/saucerman/')); ?>">Saucerman</a>
          </li>
          <li class="header__item">
            <a class="header__link" href="<?php echo esc_url(home_url('/o-firmie/')); ?>">O TyrePol</a>
          </li>
          <li class="header__item header__item--dropdown">
            <a href="#" class="header__link header__dropdown-toggle">
              Baza wiedzy <span class="header__dropdown-arrow">&#9662;</span>
            </a>
            <ul class="header__dropdown-menu">
              <li class="header__dropdown-item"><a class="header__dropdown-link" href="#">Porady</a></li>
              <li class="header__dropdown-item">
                <a class="header__dropdown-link" href="<?php echo esc_url(home_url('/aktualnosci/')); ?>">Aktualności</a>
              </li>
              <li class="header__dropdown-item"><a class="header__dropdown-link" href="#">Testy opon</a></li>
            </ul>
          </li>
          <li class="header__item">
            <a class="header__link" href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a>
          </li>
        </ul>
      </nav>

      <div class="header__actions">
        <div class="header__lang-switch">
          <input id="lang-toggle" class="header__lang-input" type="checkbox" aria-label="Przełącz język PL / EN">
          <label for="lang-toggle" class="header__lang-track"></label>
          <span class="header__lang-text header__lang-text--pl">PL</span>
          <span class="header__lang-text header__lang-text--en">EN</span>
        </div>
        <button id="hamburger" class="header__burger" type="button" aria-label="Otwórz menu" aria-expanded="false" aria-controls="header-nav">
          <span class="header__burger-line"></span>
          <span class="header__burger-line"></span>
          <span class="header__burger-line"></span>
        </button>
      </div>
    </div>
  </header>