<?php get_header(); ?>

  <main>
    <section class="hero" id="hero">
      <div class="hero__swiper swiper">
        <div class="swiper-wrapper">

          <!-- Слайд 1 (Локальна картинка) -->
          <div class="hero__slide swiper-slide">
            <div class="hero__image-wrap">
              <img class="hero__image" src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-tire-1.jpg" alt="Opony na każdą porę roku — TyrePol" loading="eager">
            </div>
            <div class="hero__content">
              <h1 class="hero__title" data-swiper-parallax-y="-120" data-swiper-parallax-duration="1200">Opony na każdą porę roku</h1>
              <p class="hero__desc" data-swiper-parallax-y="-160" data-swiper-parallax-duration="1400">Szeroki wybór opon letnich, zimowych i całorocznych renomowanych marek — dobierzemy rozmiar idealny do Twojego samochodu.</p>
              <div class="hero__link-wrap" data-swiper-parallax-y="-200" data-swiper-parallax-duration="1500">
                <a class="hero__link" href="opony.html">Sprawdź ofertę
                  <svg class="hero__link-icon" width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                      <circle class="hero__link-circle" cx="16" cy="16" r="15.12"></circle>
                      <path d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                  </svg>
                </a>
              </div>
            </div>
          </div>

          <!-- Слайд 2 (Зовнішня картинка Unsplash) -->
          <div class="hero__slide swiper-slide">
            <div class="hero__image-wrap">
              <img class="hero__image" src="https://images.unsplash.com/photo-1773161959734-ca27a48a028b?w=1400&h=1000&fit=crop&auto=format&q=80" alt="Profesjonalny montaż i wyważanie kół — TyrePol" loading="lazy">
            </div>
            <div class="hero__content">
              <h1 class="hero__title" data-swiper-parallax-y="-120" data-swiper-parallax-duration="1200">Profesjonalny montaż i wyważanie</h1>
              <p class="hero__desc" data-swiper-parallax-y="-160" data-swiper-parallax-duration="1400">Nowoczesny sprzęt i doświadczona ekipa — szybka wymiana opon bez utraty jakości i bezpieczeństwa jazdy.</p>
              <div class="hero__link-wrap" data-swiper-parallax-y="-200" data-swiper-parallax-duration="1500">
                <a class="hero__link" href="#kontakt">Umów termin
                  <svg class="hero__link-icon" width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                      <circle class="hero__link-circle" cx="16" cy="16" r="15.12"></circle>
                      <path d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                  </svg>
                </a>
              </div>
            </div>
          </div>

          <!-- Слайд 3 (Зовнішня картинка Unsplash) -->
          <div class="hero__slide swiper-slide">
            <div class="hero__image-wrap">
              <img class="hero__image" src="https://images.unsplash.com/photo-1765679244141-1e5ca661d39e?w=1400&h=1000&fit=crop&auto=format&q=80" alt="Felgi i akcesoria — TyrePol" loading="lazy">
            </div>
            <div class="hero__content">
              <h1 class="hero__title" data-swiper-parallax-y="-120" data-swiper-parallax-duration="1200">Felgi i akcesoria w jednym miejscu</h1>
              <p class="hero__desc" data-swiper-parallax-y="-160" data-swiper-parallax-duration="1400">Felgi stalowe i aluminiowe, czujniki ciśnienia, opony do przechowania — kompleksowa obsługa Twojego auta.</p>
              <div class="hero__link-wrap" data-swiper-parallax-y="-200" data-swiper-parallax-duration="1500">
                <a class="hero__link" href="marki.html">Zobacz marki
                  <svg class="hero__link-icon" width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                      <circle class="hero__link-circle" cx="16" cy="16" r="15.12"></circle>
                      <path d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                  </svg>
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Arrow Down -->
      <div class="hero__scroll">
        <a class="hero__scroll-btn" href="#faq" aria-label="Przewiń w dół">
          <span class="hero__scroll-fill" aria-hidden="true"></span>
          <svg class="hero__scroll-icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M2 6l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </a>
      </div>

      <!-- Navigation/Pagination -->
      <div class="hero__nav-pag">
        <div class="hero__nav">
          <button class="hero__nav-btn hero__nav-btn--prev" type="button" aria-label="Poprzedni slajd">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 2v12M8 2L3 7M8 2l5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
          <button class="hero__nav-btn hero__nav-btn--next" type="button" aria-label="Następny slajd">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 14V2M8 14l-5-5M8 14l5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <div class="hero__pagination swiper-pagination"></div>
      </div>
    </section>

    <!-- Blok: karuzela marek (Swiper.js, autoplay co 5s) -->
    <section class="brands" id="marki-carousel">
      <div class="brands__inner">

        <div class="brands__header reveal">
          <h2 class="brands__title">Marki nagłówek</h2>
          <p class="brands__desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.</p>
        </div>

        <div class="brands__carousel reveal">
          <button class="brands__nav brands__nav--prev" type="button" aria-label="Poprzedni slajd">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 2 4 8l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>

          <div class="brands__swiper swiper">
            <div class="swiper-wrapper">

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

              <div class="brands__slide swiper-slide">
                <div class="brands__placeholder" aria-hidden="true">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                </div>
              </div>

            </div>
          </div>

          <button class="brands__nav brands__nav--next" type="button" aria-label="Następny slajd">
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 2l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>

        <div class="brands__pagination swiper-pagination"></div>

      </div>
    </section>

    <!-- Blok: liczniki (animacja odliczania po wejściu w widok) -->
    <section class="counters" id="liczniki">
      <div class="counters__inner">

        <div class="counters__header reveal">
          <h2 class="counters__title">Liczniki nagłówek</h2>
          <p class="counters__desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.</p>
        </div>

        <div class="counters__grid">

          <div class="counters__item reveal">
            <svg class="counters__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M8 5 2 12l6 7M16 5l6 7-6 7"></path></svg>
            <h3 class="counters__number" data-to="300" data-speed="3500">0</h3>
            <p class="counters__label">Tekst przykładowy</p>
          </div>

          <div class="counters__item reveal">
            <svg class="counters__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M18 8h1a3 3 0 0 1 0 6h-1M2 8h16v6a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4z"></path><path d="M6 2v2M10 2v2M14 2v2"></path></svg>
            <h3 class="counters__number" data-to="1700" data-speed="3500">0</h3>
            <p class="counters__label">Tekst przykładowy</p>
          </div>

          <div class="counters__item reveal">
            <svg class="counters__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-4 10.5c.6.5 1 1.3 1 2.1V16h6v-.4c0-.8.4-1.6 1-2.1A6 6 0 0 0 12 3Z"></path></svg>
            <h3 class="counters__number" data-to="11900" data-speed="3500">0</h3>
            <p class="counters__label">Tekst przykładowy</p>
          </div>

          <div class="counters__item reveal">
            <svg class="counters__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="7" y="8" width="10" height="10" rx="4"></rect><path d="M9 8V6a3 3 0 0 1 6 0v2M2 12h5M17 12h5M4 6l3 3M20 6l-3 3M4 18l3-3M20 18l-3-3"></path></svg>
            <h3 class="counters__number" data-to="157" data-speed="3500">0</h3>
            <p class="counters__label">Tekst przykładowy</p>
          </div>

        </div>

      </div>
    </section>

    <!-- Blok: sekcja FAQ -->
    <section class="faq" id="faq">
      <div class="faq__inner">

        <div class="faq__header reveal">
          <h2 class="faq__title">FAQ</h2>
          <p class="faq__desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.</p>
        </div>

        <div class="faq__list">

          <div class="faq__item reveal">
            <button class="faq__question" id="faq-button-1" type="button" aria-expanded="false">
              <span class="faq__question-text">Pytanie tekst tutaj</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>
            <div class="faq__answer">
              <p class="faq__answer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
            </div>
          </div>

          <div class="faq__item reveal">
            <button class="faq__question" id="faq-button-2" type="button" aria-expanded="false">
              <span class="faq__question-text">Pytanie tekst tutaj</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>
            <div class="faq__answer">
              <p class="faq__answer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
            </div>
          </div>

          <div class="faq__item reveal">
            <button class="faq__question" id="faq-button-3" type="button" aria-expanded="false">
              <span class="faq__question-text">Pytanie tekst tutaj</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>
            <div class="faq__answer">
              <p class="faq__answer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
            </div>
          </div>

          <div class="faq__item reveal">
            <button class="faq__question" id="faq-button-4" type="button" aria-expanded="false">
              <span class="faq__question-text">Pytanie tekst tutaj</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>
            <div class="faq__answer">
              <p class="faq__answer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
            </div>
          </div>

          <div class="faq__item reveal">
            <button class="faq__question" id="faq-button-5" type="button" aria-expanded="false">
              <span class="faq__question-text">Pytanie tekst tutaj</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>
            <div class="faq__answer">
              <p class="faq__answer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- Blok: sekcja kontakt -->
    <section class="contact" id="kontakt">
      <div class="contact__inner">

        <div class="contact__header reveal">
          <h2 class="contact__title">Kontakt</h2>
          <p class="contact__desc">Masz pytanie dotyczące opon, felg lub oferty? Napisz do nas lub odwiedź nas osobiście — chętnie pomożemy dobrać najlepsze rozwiązanie.</p>
        </div>

        <div class="contact__map reveal">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d29311.096042981095!2d21.901285014789057!3d50.10737804194027!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473ce31ee259cda7%3A0x40d9feb81341c52c!2sTyrePol%20Sp.%20z%20o.%20o.!5e0!3m2!1spl!2spl!4v1786009207388!5m2!1spl!2spl"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin" title="Mapa — lokalizacja TyrePol"></iframe>
        </div>

        <div class="contact__grid">

          <div class="contact__info reveal">
            <h2 class="contact__info-heading">Skontaktuj się z nami</h2>
            <p class="contact__info-lead">Zapraszamy do kontaktu telefonicznego, mailowego lub osobistej wizyty w naszej siedzibie.</p>

            <div class="contact__info-block">
              <h3 class="contact__info-title">TyrePol Sp. z o.o.</h3>
              <ul class="contact__info-list">
                <li class="contact__info-item">35-210 Rzeszów, ul. Instalatorów 3</li>
                <li class="contact__info-item">NIP: 5170365648</li>
                <li class="contact__info-item">KRS: 0000508648</li>
              </ul>
            </div>

            <div class="contact__info-block">
              <h3 class="contact__info-title">Biuro / Magazyn</h3>
              <ul class="contact__info-list">
                <li class="contact__info-item">Lipie 7P</li>
                <li class="contact__info-item">36-060 Głogów Młp.</li>
                <li class="contact__info-item contact__info-item--icon">
                  <span class="contact__info-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                  </span>
                  <a class="contact__info-link" href="tel:+48533355110">+48 533 355 110</a>
                </li>
                <li class="contact__info-item contact__info-item--icon">
                  <span class="contact__info-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m2 6 10 7 10-7"></path></svg>
                  </span>
                  <a class="contact__info-link" href="mailto:biuro@tyrepol.pl">biuro@tyrepol.pl</a>
                </li>
              </ul>
            </div>

            <ul class="contact__social">
              <li class="contact__social-item">
                <a class="contact__social-link" href="#" aria-label="Facebook TyrePol">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                </a>
              </li>
              <li class="contact__social-item">
                <a class="contact__social-link" href="#" aria-label="Instagram TyrePol">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
              </li>
              <li class="contact__social-item">
                <a class="contact__social-link" href="#" aria-label="LinkedIn TyrePol">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                </a>
              </li>
              <li class="contact__social-item">
                <a class="contact__social-link" href="#" aria-label="YouTube TyrePol">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                </a>
              </li>
            </ul>
          </div>

          <form class="contact__form reveal" id="contact-form" action="#" method="post">
            <!-- Honeypot - pole-pułapka dla botów, ukryte przed użytkownikiem -->
            <div class="form__honeypot" aria-hidden="true">
              <label for="contact-website">Strona internetowa</label>
              <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form__group">
              <label class="form__label" for="contact-email">E-mail *</label>
              <input class="form__input" type="email" id="contact-email" name="email" required>
            </div>

            <div class="form__group">
              <label class="form__label" for="contact-phone">Telefon</label>
              <input class="form__input" type="tel" id="contact-phone" name="phone">
            </div>

            <div class="form__group">
              <label class="form__label" for="contact-message">Wiadomość</label>
              <textarea class="form__textarea" id="contact-message" name="message" rows="5"></textarea>
            </div>

            <div class="form__group form__group--checkbox">
              <input class="form__checkbox" type="checkbox" id="contact-rodo" name="rodo" required>
              <label class="form__checkbox-label" for="contact-rodo">Wyrażam zgodę na przetwarzanie moich danych osobowych przez TyrePol Sp. z o.o. w celu udzielenia odpowiedzi na przesłane zapytanie, zgodnie z <a href="#">Polityką prywatności</a>. *</label>
            </div>

            <button class="form__submit" type="submit">Wyślij</button>
          </form>

        </div>

      </div>
    </section>
    
  </main>

<?php get_footer(); ?>