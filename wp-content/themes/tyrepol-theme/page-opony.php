<?php get_header(); ?>

  <!-- Element: mobilny skrót do filtrów - stały pasek pod hederem, pojawia się po zjechaniu poza filtry -->
  <button class="catalog__filter-jump" id="catalog-filter-jump" type="button">
    <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M2 10l6-6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    Wróć do filtrów
  </button>

  <main>
    <!-- Blok: katalog opon (filtry + siatka produktów) -->
    <section class="catalog catalog--top" id="katalog">
      <div class="catalog__inner">

        <div class="catalog__header reveal">
          <h1 class="catalog__title">Marki, które oferujemy</h1>
          <p class="catalog__desc">Współpracujemy z czołowymi producentami opon — wybierz markę, aby zobaczyć jej pełną ofertę.</p>
          <!-- Kafelki marek - klik filtruje siatkę poniżej; przy więcej niż 5 markach zamienia się w karuzelę (Swiper.js) -->
          <div class="catalog__brand-carousel">
            <button class="catalog__brand-nav catalog__brand-nav--prev" type="button" aria-label="Poprzednia marka">
              <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 2 4 8l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>

            <div class="catalog__brand-swiper swiper" id="catalog-brand-tiles">
              <div class="swiper-wrapper">

                <div class="swiper-slide">
                  <button class="catalog__brand-tile" type="button" data-brand="saucerman">
                    <img class="catalog__brand-tile-logo" src="assets/images/brands/saucerman.png" alt="Logo marki Saucerman" loading="lazy">
                  </button>
                </div>

                <div class="swiper-slide">
                  <button class="catalog__brand-tile" type="button" data-brand="falken">
                    <img class="catalog__brand-tile-logo" src="assets/images/brands/falken.svg" alt="Logo marki Falken" loading="lazy">
                  </button>
                </div>

              </div>
            </div>

            <button class="catalog__brand-nav catalog__brand-nav--next" type="button" aria-label="Następna marka">
              <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 2l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
          </div>

          <div class="catalog__brand-pagination swiper-pagination"></div>
        </div>

        <div class="catalog__layout">

          <form class="catalog__filters reveal" action="#" method="get">

            <div class="filter__group">
              <h3 class="filter__title">Marka</h3>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="brand" value="saucerman"> Saucerman</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="brand" value="falken"> Falken</label>
            </div>

            <div class="filter__group">
              <h3 class="filter__title">Typ pojazdu</h3>
              <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="all" checked> Wszystkie</label>
              <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="ciezarowe"> Ciężarowe</label>
              <label class="filter__option"><input class="filter__radio" type="radio" name="vehicle" value="dostawcze"> Dostawcze</label>
            </div>

            <div class="filter__group">
              <h3 class="filter__title">Oś</h3>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="steer"> Steer</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="drive"> Drive</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="trailer"> Trailer</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="on-off"> On/Off</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="winter"> Winter</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="axle" value="a-p"> A/P</label>
            </div>

            <div class="filter__group">
              <h3 class="filter__title">Sezon</h3>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="season" value="zima"> Zima</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="season" value="lato"> Lato</label>
              <label class="filter__option"><input class="filter__checkbox" type="checkbox" name="season" value="caloroczne"> Całoroczne</label>
            </div>

            <div class="filter__group">
              <h3 class="filter__title">Rozmiar</h3>
              <select class="filter__select" name="size" aria-label="Rozmiar opony">
                <option value="">Wszystkie rozmiary</option>
                <option value="295/60R22.5">295/60R22.5</option>
                <option value="315/60R22.5">315/60R22.5</option>
                <option value="315/70R22.5">315/70R22.5</option>
                <option value="315/80R22.5">315/80R22.5</option>
                <option value="355/50R22.5">355/50R22.5</option>
                <option value="385/55R22.5">385/55R22.5</option>
                <option value="385/65R22.5">385/65R22.5</option>
                <option value="435/50R19.5">435/50R19.5</option>
                <option value="445/45R19.5">445/45R19.5</option>
                <option value="13R22.5">13R22.5</option>
              </select>
            </div>

            <button class="filter__submit" type="reset">Wyczyść filtry</button>
          </form>

          <div class="catalog__results">

            <div class="catalog__grid" id="catalog-grid"></div>

            <p class="catalog__empty" id="catalog-empty" hidden>Brak opon spełniających wybrane kryteria.</p>

            <div class="catalog__actions">
              <button class="catalog__load-more" id="catalog-load-more" type="button">Załaduj więcej opon</button>
            </div>

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