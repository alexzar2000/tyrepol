// ===== Ładowanie hedera i logika interakcji (BEM) =====

document.addEventListener('DOMContentLoaded', () => {
  // initLoader(); // loader wyłączony

  const placeholder = document.getElementById('header-placeholder');

  // Strony z komponentem hedera ładowanym dynamicznie
  if (placeholder) {
    fetch('components/header.html')
      .then((res) => res.text())
      .then((html) => {
        placeholder.innerHTML = html;
        initHeader();
      })
      .catch((err) => console.error('Nie udało się załadować hedera:', err));
    return;
  }

  // Strony z hederem wpisanym bezpośrednio w HTML (np. index.html)
  initHeader();

  initHero();
  initBrandsCarousel();
  initCounters();
  initReveal();
  initContactForm();
  initScrollTop();
  initFaq();
});

// Loader startowy - kręcące się koło i licznik procentów do momentu pełnego załadowania strony
function initLoader() {
  const loader = document.getElementById('loader');
  const countEl = loader ? loader.querySelector('.loader__count') : null;
  if (!loader || !countEl) return;

  document.body.classList.add('loader-active');

  let current = 0;
  let target = 0;

  // Symulowany postęp, dopóki strona faktycznie się nie załaduje
  const simInterval = setInterval(() => {
    if (target < 90) {
      target = Math.min(90, target + Math.random() * 10);
    }
  }, 250);

  function finishLoader() {
    document.body.classList.remove('loader-active');
    loader.classList.add('loader--done');
    setTimeout(() => loader.remove(), 650);
  }

  function tick() {
    current += (target - current) * 0.1 + 0.15;
    if (current >= 100) current = 100;
    countEl.textContent = Math.floor(current) + '%';

    if (current < 100) {
      requestAnimationFrame(tick);
    } else {
      finishLoader();
    }
  }

  window.addEventListener('load', () => {
    clearInterval(simInterval);
    target = 100;
  });

  requestAnimationFrame(tick);
}

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

  // Na mobile pierwsze dotknięcie podczas bezwładnego przewijania samo je zatrzymuje
  // i "click" nie zawsze wystrzeliwuje - obsługa "touchstart" naprawia przewijanie za pierwszym razem
  btn.addEventListener('touchstart', (e) => {
    e.preventDefault();
    scrollToTop();
  }, { passive: false });
}

// Slider hero (Swiper.js) - kierunek pionowy, autoplay co 10 sekund, paralaksa treści
function initHero() {
  const heroEl = document.querySelector('.hero__swiper');
  if (!heroEl || typeof Swiper === 'undefined') return;

  // Swiper nie umożliwia zmiany "effect" przez breakpoints w locie, więc na mobile/desktop
  // tworzymy osobne instancje: mobile - fade (przenikanie), desktop - slide pionowy z paralaksą
  const mobileQuery = window.matchMedia('(max-width: 767px)');
  let heroSwiper = null;

  const commonOptions = {
    speed: 1200,
    loop: true,
    allowTouchMove: false, // blokada swipe'a palcem - nie koliduje ze scrollem strony
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

  // Strzałka "przewiń niżej" - płynne przewinięcie do sekcji FAQ
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

// Dopełnia karuzelę duplikatami slajdów od początku, żeby ostatnia grupa
// zawsze była pełna (3 na mobile, 5 na desktop) i nie było pustej "dziury" na końcu
function padSlidesToFillGroups(wrapper, viewsList) {
  const slides = Array.from(wrapper.children);
  const total = slides.length;
  if (!total) return;

  const gcd = (a, b) => (b === 0 ? a : gcd(b, a % b));
  const lcm = viewsList.reduce((a, b) => (a * b) / gcd(a, b), 1);
  const needed = Math.ceil(total / lcm) * lcm - total;

  for (let i = 0; i < needed; i++) {
    wrapper.appendChild(slides[i % total].cloneNode(true));
  }
}

// Karuzela marek (Swiper.js) - zdjęcia zmieniają się automatycznie co 5 sekund
function initBrandsCarousel() {
  const el = document.querySelector('.brands__swiper');
  if (!el || typeof Swiper === 'undefined') return;

  const wrapper = el.querySelector('.swiper-wrapper');
  if (wrapper) padSlidesToFillGroups(wrapper, [3, 5]); // 3 slidesPerView na mobile, 5 na desktop

  new Swiper(el, {
    slidesPerView: 3,
    slidesPerGroup: 3,
    spaceBetween: 12,
    loop: true,
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
      992: { slidesPerView: 5, slidesPerGroup: 2, spaceBetween: 24 },
    },
  });
}

// Liczniki - odliczanie do wartości docelowej, uruchamiane po wejściu sekcji w widok
function initCounters() {
  const section = document.querySelector('.counters');
  const items = document.querySelectorAll('.counters__number');
  if (!section || !items.length) return;

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

  // Start dopiero, gdy blok jest naprawdę w widoku (nie tuż po dotarciu do jego krawędzi)
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      items.forEach((item) => animate(item));
      obs.unobserve(section);
    });
  }, { threshold: 0.4, rootMargin: '0px 0px -15% 0px' });

  observer.observe(section);
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

// Ochrona formularza kontaktowego przed spamem (honeypot)
function initContactForm() {
  const form = document.getElementById('contact-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const honeypot = form.querySelector('[name="website"]');

    // Pole wypełnione -> zgłoszenie od bota, nie wysyłamy formularza
    if (honeypot && honeypot.value.trim() !== '') {
      e.preventDefault();
      form.reset();
      return;
    }

    // Pole puste -> formularz obsługiwany normalnie
  });
}

// Akordeon FAQ - jedno pytanie otwarte naraz, reszta zamyka się automatycznie
function initFaq() {
  const buttons = document.querySelectorAll('.faq__question');
  if (!buttons.length) return;

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      const wasOpen = button.getAttribute('aria-expanded') === 'true';

      buttons.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));

      if (!wasOpen) {
        button.setAttribute('aria-expanded', 'true');
      }
    });
  });
}

function initHeader() {
  const header = document.querySelector('.header');
  const langInput = document.getElementById('lang-toggle');
  const burger = document.querySelector('.header__burger');
  const nav = document.querySelector('.header__nav');
  const dropdownItem = document.querySelector('.header__item--dropdown');
  const dropdownToggle = document.querySelector('.header__dropdown-toggle');
  const dropdownMenu = document.querySelector('.header__dropdown-menu');

  // Podświetlenie aktywnej strony w menu
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.header__link, .header__dropdown-link').forEach((link) => {
    const href = link.getAttribute('href');
    if (href && href !== '#' && href === currentPage) {
      link.classList.add(link.classList.contains('header__dropdown-link') ? 'header__dropdown-link--active' : 'header__link--active');
    }
  });

  // Przełącznik języka PL <-> EN (suwak)
  if (langInput) {
    langInput.addEventListener('change', () => {
      document.documentElement.lang = langInput.checked ? 'en' : 'pl';
    });
  }

  // Menu mobilne (hamburger) - pełnoekranowy overlay
  if (burger && nav && header) {
    burger.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('header__nav--open');
      burger.classList.toggle('header__burger--active', isOpen);
      burger.setAttribute('aria-expanded', String(isOpen));
      header.classList.toggle('header--dark', isOpen);
      document.body.classList.toggle('no-scroll', isOpen);
    });
  }

  // Rozwijane menu "Baza wiedzy" - desktop: hover, mobile: klik
  if (dropdownItem && dropdownToggle && dropdownMenu) {
    const isDesktop = () => window.innerWidth > 767;

    dropdownItem.addEventListener('mouseenter', () => {
      if (isDesktop()) dropdownMenu.classList.add('header__dropdown-menu--open');
    });

    dropdownItem.addEventListener('mouseleave', () => {
      if (isDesktop()) dropdownMenu.classList.remove('header__dropdown-menu--open');
    });

    dropdownToggle.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isDesktop()) {
        dropdownMenu.classList.toggle('header__dropdown-menu--open');
      }
    });

    // Zamknięcie menu po kliknięciu poza nim (mobile)
    document.addEventListener('click', (e) => {
      if (!isDesktop() && !dropdownItem.contains(e.target)) {
        dropdownMenu.classList.remove('header__dropdown-menu--open');
      }
    });
  }
}
