<?php get_header(); ?>

  <main>

    <!-- Blok: okruszki nawigacyjne (breadcrumbs) - pozwalają wrócić do wszystkich opon lub do marki -->
    <nav class="breadcrumb" aria-label="Okruszki nawigacyjne">
      <div class="breadcrumb__inner">
        <ol class="breadcrumb__list">
          <li class="breadcrumb__item"><a class="breadcrumb__link" href="opony.html">Wszystkie opony</a></li>
          <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
          <li class="breadcrumb__item"><a class="breadcrumb__link" href="opony.html?vehicle=ciezarowe">Ciężarowe</a></li>
          <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
          <li class="breadcrumb__item"><a class="breadcrumb__link" href="opony.html?vehicle=ciezarowe&amp;brand=saucerman">Saucerman</a></li>
          <li class="breadcrumb__sep" aria-hidden="true">&rsaquo;</li>
          <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">Saucerman SSL122</li>
        </ol>
      </div>
    </nav>

    <!-- Blok: sekcja szczegółów produktu (zdjęcie opony + tabela specyfikacji) -->
    <section class="tire-detail" id="produkt">
      <div class="tire-detail__inner">
        <div class="tire-detail__layout">

          <div class="tire-detail__media reveal">
            <img class="tire-detail__img" src="assets/images/tire-saucerman.png" alt="Opona Saucerman SSL122 - widok z boku" loading="lazy">
          </div>

          <div class="tire-detail__panel reveal">
            <span class="tire-detail__badge">SSL122</span>

            <div class="tire-detail__table-wrap">
              <table class="tire-detail__table">
                <thead>
                  <tr>
                    <th scope="col">Seria</th>
                    <th scope="col">Rozmiary</th>
                    <th scope="col">LI / SR</th>
                    <th scope="col">Głębokość bieżnika (mm)</th>
                    <th scope="col">
                      <span class="tire-detail__icon" title="Zużycie paliwa" aria-label="Zużycie paliwa">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"></path><path d="M3 10h9"></path><path d="M15 6h2a2 2 0 0 1 2 2v3a2 2 0 0 0 2 2v5a2 2 0 0 1-2 2"></path><rect x="6" y="13" width="4" height="4"></rect></svg>
                      </span>
                    </th>
                    <th scope="col">
                      <span class="tire-detail__icon" title="Przyczepność na mokrej nawierzchni" aria-label="Przyczepność na mokrej nawierzchni">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s7 8.5 7 13a7 7 0 0 1-14 0c0-4.5 7-13 7-13z"></path></svg>
                      </span>
                    </th>
                    <th scope="col">
                      <span class="tire-detail__icon" title="Hałas zewnętrzny (dB)" aria-label="Hałas zewnętrzny w decybelach">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5 6 9H2v6h4l5 4V5z"></path><path d="M15.5 8.5a5 5 0 0 1 0 7"></path><path d="M18.5 5.5a9 9 0 0 1 0 13"></path></svg>
                      </span>
                    </th>
                    <th scope="col">
                      <span class="tire-detail__icon" title="Klasa oporu toczenia" aria-label="Klasa oporu toczenia">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3"></circle><path d="M12 3v2M12 19v2M21 12h-2M5 12H3"></path></svg>
                      </span>
                    </th>
                    <th scope="col">
                      <span class="tire-detail__ms-badge" title="Oznaczenie M+S" aria-label="Oznaczenie M+S">M+S</span>
                    </th>
                    <th scope="col">
                      <span class="tire-detail__icon" title="Oznaczenie 3PMSF (zastosowanie zimowe)" aria-label="Oznaczenie 3PMSF - zastosowanie zimowe">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="12" y1="2" x2="12" y2="22"></line><line x1="4" y1="7" x2="20" y2="17"></line><line x1="20" y1="7" x2="4" y2="17"></line><path d="M12 2l-2 2M12 2l2 2M12 22l-2-2M12 22l2-2"></path><path d="M4 7l2.5.5M4 7l.5 2.5M20 17l-2.5-.5M20 17l-.5-2.5"></path><path d="M20 7l-2.5.5M20 7l-.5 2.5M4 17l2.5-.5M4 17l.5-2.5"></path></svg>
                      </span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-label="Seria">MAX DISTANCE S</td>
                    <td data-label="Rozmiary">355/50R22.5</td>
                    <td data-label="LI / SR">156L</td>
                    <td data-label="Głębokość bieżnika (mm)">12.0</td>
                    <td data-label="Zużycie paliwa">C</td>
                    <td data-label="Przyczepność na mokrej nawierzchni">B</td>
                    <td data-label="Hałas zewnętrzny (dB)">73</td>
                    <td data-label="Klasa oporu toczenia">B</td>
                    <td data-label="Oznaczenie M+S" class="tire-detail__check">✓</td>
                    <td data-label="Oznaczenie 3PMSF" class="tire-detail__check">✓</td>
                  </tr>
                  <tr>
                    <td data-label="Seria">MAX DISTANCE S</td>
                    <td data-label="Rozmiary">385/55R22.5</td>
                    <td data-label="LI / SR">160K</td>
                    <td data-label="Głębokość bieżnika (mm)">15.0</td>
                    <td data-label="Zużycie paliwa">C</td>
                    <td data-label="Przyczepność na mokrej nawierzchni">B</td>
                    <td data-label="Hałas zewnętrzny (dB)">73</td>
                    <td data-label="Klasa oporu toczenia">B</td>
                    <td data-label="Oznaczenie M+S" class="tire-detail__check">✓</td>
                    <td data-label="Oznaczenie 3PMSF" class="tire-detail__check">✓</td>
                  </tr>
                  <tr>
                    <td data-label="Seria">MAX DISTANCE T</td>
                    <td data-label="Rozmiary">385/65R22.5</td>
                    <td data-label="LI / SR">164K</td>
                    <td data-label="Głębokość bieżnika (mm)">15.5</td>
                    <td data-label="Zużycie paliwa">B</td>
                    <td data-label="Przyczepność na mokrej nawierzchni">B</td>
                    <td data-label="Hałas zewnętrzny (dB)">70</td>
                    <td data-label="Klasa oporu toczenia">A</td>
                    <td data-label="Oznaczenie M+S" class="tire-detail__check">✓</td>
                    <td data-label="Oznaczenie 3PMSF" class="tire-detail__check">✓</td>
                  </tr>
                  <tr>
                    <td data-label="Seria">MAX DISTANCE T</td>
                    <td data-label="Rozmiary">435/50R19.5</td>
                    <td data-label="LI / SR">164J</td>
                    <td data-label="Głębokość bieżnika (mm)">12.5</td>
                    <td data-label="Zużycie paliwa">B</td>
                    <td data-label="Przyczepność na mokrej nawierzchni">B</td>
                    <td data-label="Hałas zewnętrzny (dB)">70</td>
                    <td data-label="Klasa oporu toczenia">A</td>
                    <td data-label="Oznaczenie M+S" class="tire-detail__check">✓</td>
                    <td data-label="Oznaczenie 3PMSF" class="tire-detail__check">✓</td>
                  </tr>
                  <tr>
                    <td data-label="Seria">MAX DISTANCE T</td>
                    <td data-label="Rozmiary">445/45R19.5</td>
                    <td data-label="LI / SR">164J</td>
                    <td data-label="Głębokość bieżnika (mm)">13.0</td>
                    <td data-label="Zużycie paliwa">B</td>
                    <td data-label="Przyczepność na mokrej nawierzchni">B</td>
                    <td data-label="Hałas zewnętrzny (dB)">70</td>
                    <td data-label="Klasa oporu toczenia">A</td>
                    <td data-label="Oznaczenie M+S" class="tire-detail__check">✓</td>
                    <td data-label="Oznaczenie 3PMSF" class="tire-detail__check">✓</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="tire-detail__cta-wrap">
              <button class="tire-detail__cta" type="button" data-modal-open="product-inquiry-modal">Zapytaj o cenę</button>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- Mobilny stały przycisk zapytania - widoczny tylko, gdy sekcja szczegółów opony jest w widoku -->
    <button class="tire-detail__mobile-cta" id="tire-mobile-cta" type="button" data-modal-open="product-inquiry-modal">Zapytaj o cenę</button>

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