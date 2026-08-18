// ===== Logika interakcji (BEM) — wersja WordPress =====
// Zmiany względem wersji statycznej są oznaczone komentarzem "WP:".
// Reszta pliku (Swiper, liczniki, FAQ, modal, scrolltop, reveal) jest bez zmian.

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initHero();
  initBrandsCarousel();
  initCounters();
  initCatalog();
  initCatalogFilterJump();
  initReveal();
  initContactForm();
  initScrollTop();
  initFaq();
  initModal();
});

// Przycisk "do góry" - pierścień pokazuje postęp przewijania, przycisk pojawia się stopniowo
function initScrollTop() {
  const btn = document.getElementById('scrolltop');
  const path = document.querySelector('.scrolltop__ring path');
  if (!btn || !path) return;

  const offset = 50;
  const pathLength = path.getTotalLength();

  path.style.strokeDasharray = `${pathLength} ${pathLength}`;
  path.style.strokeDashoffset = pathLength;

  const updateProgress = () => {
    const scroll = window.scrollY;
    const height = document.documentElement.scrollHeight - window.innerHeight;
    const progress = pathLength - (scroll * pathLength / height);
    path.style.strokeDashoffset = progress;

    btn.classList.toggle('scrolltop--visible', scroll > offset);
  };

  updateProgress();
  window.addEventListener('scroll', updateProgress);

  const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

  btn.addEventListener('click', scrollToTop);

  btn.addEventListener('touchstart', (e) => {
    e.preventDefault();
    scrollToTop();
  }, { passive: false });
}

// Slider hero (Swiper.js) - kierunek pionowy, autoplay co 10 sekund, paralaksa treści
function initHero() {
  const heroEl = document.querySelector('.hero__swiper');
  if (!heroEl || typeof Swiper === 'undefined') return;

  const mobileQuery = window.matchMedia('(max-width: 767px)');
  let heroSwiper = null;

  const commonOptions = {
    speed: 1200,
    loop: true,
    allowTouchMove: false,
    autoplay: {
      delay: 10000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.hero__nav-btn--next',
      prevEl: '.hero__nav-btn--prev',
    },
    pagination: {
      el: '.hero__pagination',
      clickable: true,
      renderBullet: (index, className) => (
        `<span class="${className} hero__bullet">
          <svg width="26" height="26" viewBox="0 0 28 28">
            <circle class="hero__bullet-progress" cx="14" cy="14" r="10" fill="none" stroke-width="2"></circle>
            <circle class="hero__bullet-dot" cx="14" cy="14" r="2" stroke-width="3"></circle>
          </svg>
        </span>`
      ),
    },
  };

  function buildHeroSwiper() {
    if (heroSwiper) {
      heroSwiper.destroy(true, true);
      heroSwiper = null;
    }

    heroSwiper = mobileQuery.matches
      ? new Swiper(heroEl, {
        ...commonOptions,
        effect: 'fade',
        fadeEffect: { crossFade: true },
      })
      : new Swiper(heroEl, {
        ...commonOptions,
        direction: 'vertical',
        parallax: true,
      });
  }

  buildHeroSwiper();
  mobileQuery.addEventListener('change', buildHeroSwiper);

  const scrollBtn = document.querySelector('.hero__scroll-btn');
  if (scrollBtn) {
    scrollBtn.addEventListener('click', (e) => {
      const target = document.querySelector(scrollBtn.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    });
  }
}

// Karuzela marek (Swiper.js)
function initBrandsCarousel() {
  const section = document.querySelector('.brands');
  const el = document.querySelector('.brands__swiper');
  if (!section || !el || typeof Swiper === 'undefined') return;

  new Swiper(el, {
    slidesPerView: 3,
    spaceBetween: 12,
    loop: false,
    watchOverflow: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.brands__nav--next',
      prevEl: '.brands__nav--prev',
    },
    pagination: {
      el: '.brands__pagination',
      clickable: true,
    },
    breakpoints: {
      992: { slidesPerView: 5, spaceBetween: 24 },
    },
    on: {
      lock: (swiper) => { section.classList.add('brands--static'); swiper.autoplay.stop(); },
      unlock: (swiper) => { section.classList.remove('brands--static'); swiper.autoplay.start(); },
    },
  });
}

// Liczniki - odliczanie do wartości docelowej, uruchamiane po wejściu sekcji w widok
function initCounters() {
  const sections = document.querySelectorAll('.counters');
  if (!sections.length) return;

  const animate = (el) => {
    const to = parseInt(el.dataset.to, 10) || 0;
    const speed = parseInt(el.dataset.speed, 10) || 1500;
    const start = performance.now();

    const tick = (now) => {
      const progress = Math.min((now - start) / speed, 1);
      const value = Math.floor(progress * to);
      el.textContent = value.toLocaleString('pl-PL');

      if (progress < 1) {
        requestAnimationFrame(tick);
      } else {
        el.textContent = to.toLocaleString('pl-PL');
      }
    };

    requestAnimationFrame(tick);
  };

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.querySelectorAll('.counters__number').forEach((item) => animate(item));
      obs.unobserve(entry.target);
    });
  }, { threshold: 0.4, rootMargin: '0px 0px -15% 0px' });

  sections.forEach((section) => observer.observe(section));
}

// WP: dane katalogu opon wstrzykiwane z page-opony.php (wp_add_inline_script) zamiast tablicy
// zaszytej na sztywno w JS — treść pochodzi teraz z CPT „Opona” w panelu WordPress.
const TIRES = (window.tyrepolCatalog && window.tyrepolCatalog.tires) || [];
const BRAND_LABELS = (window.tyrepolCatalog && window.tyrepolCatalog.brandLabels) || {};
const AXLE_LABELS = (window.tyrepolCatalog && window.tyrepolCatalog.axleLabels) || {};
const VEHICLE_LABELS = (window.tyrepolCatalog && window.tyrepolCatalog.vehicleLabels) || {};
const SEASON_LABELS = (window.tyrepolCatalog && window.tyrepolCatalog.seasonLabels) || {};
const I18N = window.tyrepolI18n || {};

function initBrandTilesCarousel(el) {
  const header = el ? el.closest('.catalog__header') : null;
  if (!el || !header || typeof Swiper === 'undefined') return;

  new Swiper(el, {
    slidesPerView: 2,
    spaceBetween: 16,
    watchOverflow: true,
    navigation: {
      nextEl: '.catalog__brand-nav--next',
      prevEl: '.catalog__brand-nav--prev',
    },
    pagination: {
      el: '.catalog__brand-pagination',
      clickable: true,
    },
    breakpoints: {
      576: { slidesPerView: 3, spaceBetween: 16 },
      992: { slidesPerView: 5, spaceBetween: 24 },
    },
    on: {
      lock: () => header.classList.add('catalog__header--brand-static'),
      unlock: () => header.classList.remove('catalog__header--brand-static'),
    },
  });
}

// Katalog opon - filtrowanie (marka, typ pojazdu, oś, sezon, rozmiar) + doładowywanie kart po 6 sztuk
function initCatalog() {
  const grid = document.getElementById('catalog-grid');
  const form = document.querySelector('.catalog__filters');
  const loadMoreBtn = document.getElementById('catalog-load-more');
  const emptyState = document.getElementById('catalog-empty');
  if (!grid || !form || !loadMoreBtn) return;

  const PAGE_SIZE = 6;
  let visibleCount = PAGE_SIZE;

  const getChecked = (name) => Array.from(form.querySelectorAll(`input[name="${name}"]:checked`)).map((el) => el.value);

  // WP: tire.axle / tire.season / tire.vehicle / tire.sizes to teraz TABLICE (unia wszystkich
  // rozmiarów danego modelu — patrz grupowanie w page-opony.php), stąd dopasowanie przez
  // "includes"/"some" zamiast prostego porównania pojedynczej wartości.
  const matchesFilters = (tire, filters) => {
    if (filters.brands.length && !filters.brands.includes(tire.brand)) return false;
    if (filters.vehicle !== 'all' && !tire.vehicle.includes(filters.vehicle)) return false;
    if (filters.axles.length && !filters.axles.some((a) => tire.axle.includes(a))) return false;
    if (filters.seasons.length && !filters.seasons.some((s) => tire.season.includes(s))) return false;
    if (filters.size && !tire.sizes.includes(filters.size)) return false;
    return true;
  };

  const cardTemplate = (tire) => {
    const brandLabel = BRAND_LABELS[tire.brand] || tire.brand;
    const axleLabel = (tire.axle || []).map((a) => AXLE_LABELS[a] || a).join(', ');
    const vehicleLabel = (tire.vehicle || []).map((v) => VEHICLE_LABELS[v] || v).join(', ');
    const seasonLabel = (tire.season || []).map((s) => SEASON_LABELS[s] || s).join(', ');
    const sizesCount = (tire.sizes || []).length;

    // WP: zdjęcie pochodzi z obrazka wyróżniającego reprezentatywnego wpisu CPT „Opona” danego
    // modelu (tire.image) — jedna karta = jeden model, niezależnie od liczby rozmiarów.
    const mediaMarkup = tire.image
      ? `<img class="tire-card__img" src="${tire.image}" alt="Opona ${brandLabel} ${tire.pattern}" loading="lazy">`
      : `<div class="tire-card__placeholder" aria-hidden="true">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3.5"></circle></svg>
        </div>`;

    return `
      <article class="tire-card">
        <div class="tire-card__media">
          <span class="tire-card__badge">${seasonLabel}</span>
          ${mediaMarkup}
        </div>
        <div class="tire-card__row">
          <span class="tire-card__brand">${brandLabel}</span>
          <span class="tire-card__model">${tire.pattern || ''}</span>
        </div>
        <div class="tire-card__row tire-card__row--specs">
          <span>Oś: <strong>${axleLabel}</strong></span>
          <span>Typ pojazdu: <strong>${vehicleLabel}</strong></span>
        </div>
        ${sizesCount ? `<div class="tire-card__row tire-card__row--specs"><span>${I18N.sizesAvailable || 'Dostępne rozmiary'}: <strong>${sizesCount}</strong></span></div>` : ''}
        <a class="tire-card__link" href="${tire.link || '#'}">${I18N.detailsLink || 'Zobacz szczegóły'}</a>
      </article>`;
  };

  let currentFiltered = [];

  const computeFiltered = () => {
    const vehicleInput = form.querySelector('input[name="vehicle"]:checked');
    const filters = {
      brands: getChecked('brand'),
      vehicle: vehicleInput ? vehicleInput.value : 'all',
      axles: getChecked('axle'),
      seasons: getChecked('season'),
      size: form.querySelector('select[name="size"]').value,
    };

    return TIRES.filter((tire) => matchesFilters(tire, filters));
  };

  const buildCardEl = (tire) => {
    const template = document.createElement('template');
    template.innerHTML = cardTemplate(tire).trim();
    return template.content.firstElementChild;
  };

  const appendCards = (tires) => {
    const newCards = tires.map((tire) => {
      const el = buildCardEl(tire);
      el.classList.add('tire-card--enter');
      grid.appendChild(el);
      return el;
    });

    newCards.forEach((el, i) => {
      el.style.transitionDelay = `${Math.min(i, 8) * 0.06}s`;
    });

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        newCards.forEach((el) => {
          el.classList.remove('tire-card--enter');
          el.addEventListener('transitionend', () => { el.style.transitionDelay = ''; }, { once: true });
        });
      });
    });
  };

  const renderFiltered = () => {
    visibleCount = PAGE_SIZE;
    currentFiltered = computeFiltered();

    grid.innerHTML = '';
    appendCards(currentFiltered.slice(0, visibleCount));

    emptyState.hidden = currentFiltered.length > 0;
    loadMoreBtn.hidden = visibleCount >= currentFiltered.length;
  };

  const appendMore = () => {
    const previousCount = visibleCount;
    visibleCount += PAGE_SIZE;

    appendCards(currentFiltered.slice(previousCount, visibleCount));

    loadMoreBtn.hidden = visibleCount >= currentFiltered.length;
  };

  form.addEventListener('change', renderFiltered);

  form.addEventListener('reset', () => {
    setTimeout(renderFiltered, 0);
  });

  loadMoreBtn.addEventListener('click', appendMore);

  const brandTiles = document.getElementById('catalog-brand-tiles');
  const results = document.querySelector('.catalog__results');

  initBrandTilesCarousel(brandTiles);

  if (brandTiles) {
    const tiles = Array.from(brandTiles.querySelectorAll('.catalog__brand-tile'));
    const brandCheckboxes = Array.from(form.querySelectorAll('input[name="brand"]'));

    tiles.forEach((tile) => {
      tile.addEventListener('click', () => {
        const alreadyActive = tile.classList.contains('catalog__brand-tile--active');
        const brand = alreadyActive ? '' : tile.dataset.brand;

        brandCheckboxes.forEach((checkbox) => {
          checkbox.checked = checkbox.value === brand;
        });

        tiles.forEach((t) => t.classList.toggle('catalog__brand-tile--active', t === tile && !alreadyActive));

        renderFiltered();

        if (results) results.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    form.addEventListener('change', () => {
      const checkedBrands = getChecked('brand');
      const activeBrand = checkedBrands.length === 1 ? checkedBrands[0] : '';
      tiles.forEach((t) => t.classList.toggle('catalog__brand-tile--active', t.dataset.brand === activeBrand));
    });

    form.addEventListener('reset', () => {
      tiles.forEach((t) => t.classList.remove('catalog__brand-tile--active'));
    });
  }

  const applyFiltersFromURL = () => {
    const params = new URLSearchParams(window.location.search);

    const vehicleParam = params.get('vehicle');
    if (vehicleParam) {
      const vehicleInput = form.querySelector(`input[name="vehicle"][value="${vehicleParam}"]`);
      if (vehicleInput) vehicleInput.checked = true;
    }

    // WP: obsługa również parametru "marka" (używanego przez karuzelę marek na Stronie głównej).
    const brandParam = params.get('brand') || params.get('marka');
    if (brandParam) {
      brandParam.split(',').forEach((v) => {
        const input = form.querySelector(`input[name="brand"][value="${v}"]`);
        if (input) input.checked = true;
      });
    }

    ['axle', 'season'].forEach((name) => {
      const value = params.get(name);
      if (!value) return;
      value.split(',').forEach((v) => {
        const input = form.querySelector(`input[name="${name}"][value="${v}"]`);
        if (input) input.checked = true;
      });
    });

    const sizeParam = params.get('size');
    if (sizeParam) {
      const select = form.querySelector('select[name="size"]');
      if (select && Array.from(select.options).some((o) => o.value === sizeParam)) {
        select.value = sizeParam;
      }
    }

    if (brandTiles) {
      const checkedBrands = getChecked('brand');
      const activeBrand = checkedBrands.length === 1 ? checkedBrands[0] : '';
      Array.from(brandTiles.querySelectorAll('.catalog__brand-tile')).forEach((t) => {
        t.classList.toggle('catalog__brand-tile--active', t.dataset.brand === activeBrand);
      });
    }
  };

  applyFiltersFromURL();
  renderFiltered();
}

// Mobilny skrót "Wróć do filtrów"
function initCatalogFilterJump() {
  const btn = document.getElementById('catalog-filter-jump');
  const filters = document.querySelector('.catalog__filters');
  if (!btn || !filters) return;

  const mobileQuery = window.matchMedia('(max-width: 1100px)');

  const updateVisibility = () => {
    if (!mobileQuery.matches) {
      btn.classList.remove('catalog__filter-jump--visible');
      return;
    }

    const isFiltersAboveViewport = filters.getBoundingClientRect().bottom < 0;
    btn.classList.toggle('catalog__filter-jump--visible', isFiltersAboveViewport);
  };

  window.addEventListener('scroll', updateVisibility, { passive: true });
  window.addEventListener('resize', updateVisibility);
  updateVisibility();

  btn.addEventListener('click', () => {
    filters.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
}

// Pojawianie się bloków przy przewijaniu do nich (fade + przesunięcie w górę)
function initReveal() {
  const items = document.querySelectorAll('.reveal');
  if (!items.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('reveal--visible');
      obs.unobserve(entry.target);
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

  items.forEach((item) => observer.observe(item));
}

// WP: wysyłka formularzy przez admin-ajax.php (tyrepolForms.ajaxUrl/nonce, patrz inc/helpers.php)
// zamiast tylko symulowania wysyłki — honeypot działa tak samo jak wcześniej.
async function sendTyrepolForm(form, formType) {
  const honeypot = form.querySelector('[name="website"]');
  if (honeypot && honeypot.value.trim() !== '') {
    return { ok: true, message: '' }; // zgłoszenie bota - udajemy sukces, nic nie wysyłamy
  }

  if (!window.tyrepolForms) {
    return { ok: false, message: 'Formularz jest chwilowo niedostępny.' };
  }

  const data = new FormData(form);
  data.set('action', 'tyrepol_send_form');
  data.set('nonce', window.tyrepolForms.nonce);
  data.set('form_type', formType);

  try {
    const res = await fetch(window.tyrepolForms.ajaxUrl, { method: 'POST', body: data });
    const json = await res.json();
    return { ok: !!json.success, message: (json.data && json.data.message) || '' };
  } catch (err) {
    return { ok: false, message: 'Nie udało się wysłać formularza. Sprawdź połączenie i spróbuj ponownie.' };
  }
}

// Formularz kontaktowy (strona Kontakt / sekcja kontakt) - wysyłka AJAX + komunikat pod formularzem
function initContactForm() {
  const form = document.getElementById('contact-form');
  if (!form) return;

  const status = document.getElementById('contact-form-status');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const submitBtn = form.querySelector('.form__submit');
    if (submitBtn) submitBtn.disabled = true;

    const result = await sendTyrepolForm(form, 'kontakt');

    if (status) {
      status.hidden = false;
      status.textContent = result.message || (result.ok ? 'Wiadomość została wysłana.' : 'Wystąpił błąd.');
      status.classList.toggle('form__status--ok', result.ok);
      status.classList.toggle('form__status--error', !result.ok);
    }

    if (submitBtn) submitBtn.disabled = false;
    if (result.ok) form.reset();
  });
}

// Akordeon FAQ - jedno pytanie otwarte naraz, reszta zamyka się automatycznie
function initFaq() {
  const buttons = document.querySelectorAll('.faq__question');
  if (!buttons.length) return;

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      const wasOpen = button.getAttribute('aria-expanded') === 'true';
      const group = button.closest('.faq__list');
      const groupButtons = group ? group.querySelectorAll('.faq__question') : buttons;

      groupButtons.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));

      if (!wasOpen) {
        button.setAttribute('aria-expanded', 'true');
      }
    });
  });
}

// Popup z formularzem (zapytanie o cenę opony) - otwieranie/zamykanie, ESC, klik w tło,
// wysyłka AJAX, po sukcesie popup zamyka się i pokazuje toast z komunikatem z serwera.
function initModal() {
  const modals = document.querySelectorAll('.modal');
  if (!modals.length) return;

  let lastTrigger = null;

  const openModal = (modal) => {
    modal.classList.add('modal--open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    const firstField = modal.querySelector('select, input, textarea');
    if (firstField) firstField.focus();
  };

  const closeModal = (modal) => {
    modal.classList.remove('modal--open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');
    if (lastTrigger) lastTrigger.focus();
  };

  document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const modal = document.getElementById(trigger.dataset.modalOpen);
      if (!modal) return;
      lastTrigger = trigger;
      openModal(modal);
    });
  });

  modals.forEach((modal) => {
    modal.querySelectorAll('[data-modal-close]').forEach((closer) => {
      closer.addEventListener('click', () => closeModal(modal));
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    const openModalEl = document.querySelector('.modal--open');
    if (openModalEl) closeModal(openModalEl);
  });

  const inquiryForm = document.getElementById('inquiry-form');
  const toast = document.getElementById('inquiry-toast');
  const toastText = document.getElementById('inquiry-toast-text');

  if (inquiryForm) {
    inquiryForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const submitBtn = inquiryForm.querySelector('.form__submit');
      if (submitBtn) submitBtn.disabled = true;

      const result = await sendTyrepolForm(inquiryForm, 'wycena');

      if (submitBtn) submitBtn.disabled = false;

      if (result.ok) {
        const modal = inquiryForm.closest('.modal');
        if (modal) closeModal(modal);
        inquiryForm.reset();

        if (toast) {
          if (toastText && result.message) toastText.textContent = result.message;
          toast.classList.add('toast--visible');
          clearTimeout(toast._hideTimeout);
          toast._hideTimeout = setTimeout(() => toast.classList.remove('toast--visible'), 4000);
        }
      } else if (toast) {
        if (toastText) toastText.textContent = result.message || 'Wystąpił błąd. Spróbuj ponownie.';
        toast.classList.add('toast--visible', 'toast--error');
        clearTimeout(toast._hideTimeout);
        toast._hideTimeout = setTimeout(() => toast.classList.remove('toast--visible', 'toast--error'), 4000);
      }
    });
  }
}

function initHeader() {
  const header = document.querySelector('.header');
  const langSwitch = document.querySelector('.header__lang-switch');
  const langInput = document.getElementById('lang-toggle');
  const burger = document.querySelector('.header__burger');
  const nav = document.querySelector('.header__nav');
  const dropdownItem = document.querySelector('.header__item--dropdown');
  const dropdownToggle = document.querySelector('.header__dropdown-toggle');
  const dropdownMenu = document.querySelector('.header__dropdown-menu');

  // WP: przełącznik języka PL <-> EN - jeśli wtyczka Polylang jest aktywna i strona ma tłumaczenie,
  // suwak przenosi na prawdziwy adres wersji językowej (dane wstrzyknięte w header.php);
  // w przeciwnym razie zachowuje się jak nieaktywny suwak (bez tłumaczenia nie ma dokąd przenieść).
  if (langInput && langSwitch) {
    const plUrl = langSwitch.dataset.langPl;
    const enUrl = langSwitch.dataset.langEn;

    langInput.addEventListener('change', () => {
      if (langInput.disabled) return;
      const target = langInput.checked ? enUrl : plUrl;
      if (target) window.location.href = target;
    });
  }

  if (burger && nav && header) {
    burger.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('header__nav--open');
      burger.classList.toggle('header__burger--active', isOpen);
      burger.setAttribute('aria-expanded', String(isOpen));
      header.classList.toggle('header--dark', isOpen);
      document.body.classList.toggle('no-scroll', isOpen);
    });
  }

  if (dropdownItem && dropdownToggle && dropdownMenu) {
    const isDesktop = () => window.innerWidth > 767;

    dropdownItem.addEventListener('mouseenter', () => {
      if (isDesktop()) dropdownMenu.classList.add('header__dropdown-menu--open');
    });

    dropdownItem.addEventListener('mouseleave', () => {
      if (isDesktop()) dropdownMenu.classList.remove('header__dropdown-menu--open');
    });

    dropdownToggle.addEventListener('click', (e) => {
      if (!isDesktop()) {
        e.preventDefault();
        dropdownMenu.classList.toggle('header__dropdown-menu--open');
      }
    });

    document.addEventListener('click', (e) => {
      if (!isDesktop() && !dropdownItem.contains(e.target)) {
        dropdownMenu.classList.remove('header__dropdown-menu--open');
      }
    });
  }
}
