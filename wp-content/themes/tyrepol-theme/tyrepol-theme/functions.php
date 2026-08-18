<?php
/**
 * TyrePol — funkcje motywu.
 * Konwersja statycznej wersji HTML na motyw WordPress (ACF Free — bez PRO — + Polylang).
 *
 * Uwaga architektoniczna: ACF Free nie ma pól Repeater / Flexible Content / Options Page
 * (to funkcje płatnej wersji PRO), dlatego zamiast nich motyw używa wyłącznie darmowych typów
 * pól ACF (Group, Tab, Message, Image, Text, WYSIWYG, True/False, Number, Select…) w postaci
 * ustalonej liczby „slotów” (np. do 6 liczników, do 8 pytań FAQ) z przełącznikiem „pokaż” przy
 * każdym — pusty slot po prostu się nie wyświetla. Patrz pliki w /acf-json oraz komentarze
 * w template-elastyczna.php i front-page.php.
 */

if (!defined('ABSPATH')) exit;

define('TYREPOL_VERSION', '1.0.0');
define('TYREPOL_DIR', get_template_directory());
define('TYREPOL_URI', get_template_directory_uri());

/**
 * Podstawowa konfiguracja motywu.
 */
function tyrepol_setup() {
    load_theme_textdomain('tyrepol', TYREPOL_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('automatic-feed-links');

    register_nav_menus([
        'primary' => __('Menu główne (nagłówek)', 'tyrepol'),
        'footer'  => __('Stopka — linki prawne', 'tyrepol'),
    ]);
}
add_action('after_setup_theme', 'tyrepol_setup');

/**
 * Style i skrypty.
 * Uwaga: assets/style.css to oryginalny arkusz stylów ze statycznej wersji — nie zmienialiśmy go,
 * dlatego względne odwołania do obrazków (np. images/hero-tire-1.jpg) nadal działają, o ile
 * zachowany zostanie układ folderów assets/style.css + assets/images/.
 */
function tyrepol_assets() {
    // Wersja pliku = jego data modyfikacji (filemtime), NIE stała „1.0.0”. Dzięki temu przy każdym
    // wgraniu nowej wersji motywu adres pliku (?ver=...) automatycznie się zmienia i przeglądarka
    // (a także serwer/CDN z długim cache — u nas Cache-Control: max-age=2592000, czyli 30 dni)
    // pobiera świeży plik zamiast pokazywać stary z pamięci podręcznej. Bez tego zmiany w CSS/JS
    // mogły być niewidoczne dla odwiedzających nawet po poprawnym wgraniu nowej wersji motywu.
    $style_path  = TYREPOL_DIR . '/assets/style.css';
    $script_path = TYREPOL_DIR . '/assets/script.js';
    $style_ver   = file_exists($style_path) ? filemtime($style_path) : TYREPOL_VERSION;
    $script_ver  = file_exists($script_path) ? filemtime($script_path) : TYREPOL_VERSION;

    wp_enqueue_style('tyrepol-google-font', 'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11');
    wp_enqueue_style('tyrepol-style', TYREPOL_URI . '/assets/style.css', ['swiper'], $style_ver);

    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);
    wp_enqueue_script('tyrepol-script', TYREPOL_URI . '/assets/script.js', ['swiper'], $script_ver, true);

    // Teksty używane bezpośrednio w JS (karty katalogu opon / karty aktualności) — tłumaczone przez
    // standardowy mechanizm WordPress (przełącznik Polylang zmienia język strony -> zmienia się i __()).
    wp_localize_script('tyrepol-script', 'tyrepolI18n', [
        'detailsLink'    => __('Zobacz szczegóły', 'tyrepol'),
        'readMore'       => __('Czytaj więcej', 'tyrepol'),
        'sizesAvailable' => __('Dostępne rozmiary', 'tyrepol'),
    ]);
}
add_action('wp_enqueue_scripts', 'tyrepol_assets');

/**
 * Rejestracja CPT „Opona” + taksonomii katalogu opon.
 */
require TYREPOL_DIR . '/inc/cpt-opona.php';

/**
 * Rejestracja CPT „Slajd hero” — nieograniczona liczba slajdów w karuzeli Strony głównej
 * (zamiast stałej liczby pól ACF — patrz komentarz w pliku).
 */
require TYREPOL_DIR . '/inc/cpt-slajd-hero.php';

/**
 * Rejestracja CPT „Pytanie FAQ” — nieograniczona liczba pytań w sekcji FAQ, wspólnej dla
 * wszystkich stron (zamiast stałej liczby pól ACF — patrz komentarz w pliku).
 */
require TYREPOL_DIR . '/inc/cpt-pytanie-faq.php';

/**
 * Niestandardowy Walker menu — mapuje standardowe menu WP (Wygląd → Menu) na klasy BEM
 * użyte w oryginalnej wersji HTML (header__item, header__dropdown-menu itd.), żeby wygląd
 * menu pozostał identyczny jak w statycznej wersji.
 */
require TYREPOL_DIR . '/inc/nav-walker.php';

/**
 * „Ustawienia motywu” — działa na ACF Free (bez Options Page z ACF PRO): dane firmy/kontakt
 * trzymane są na zwykłej, ukrytej stronie WordPress, którą motyw tworzy sam przy aktywacji.
 */
require TYREPOL_DIR . '/inc/ustawienia.php';

/**
 * Funkcje pomocnicze (m.in. bezpieczne pobieranie ustawień, cząstka kontaktu, zbieranie
 * „slotów” z pól Group zamiast Repeatera — patrz komentarz w pliku).
 */
require TYREPOL_DIR . '/inc/helpers.php';

/**
 * ACF — lokalny JSON: pola z /acf-json synchronizują się automatycznie w panelu
 * (Niestandardowe pola → wskazówka „Dostępna synchronizacja”), nie trzeba nic importować ręcznie.
 * (Domyślna ścieżka ACF to już get_stylesheet_directory() . '/acf-json' — poniższe filtry są
 * tylko dla jasności i na wypadek użycia motywu potomnego.)
 */
add_filter('acf/settings/save_json', function () { return TYREPOL_DIR . '/acf-json'; });
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = TYREPOL_DIR . '/acf-json';
    return $paths;
});

/**
 * Skróć wyciąg (excerpt) w kartach „Aktualności”, żeby pasował do siatki kart.
 */
add_filter('excerpt_length', fn() => 24);
add_filter('excerpt_more', fn() => '…');

/**
 * Rozmiary obrazków dopasowane do siatek kart (opony / aktualności) i galerii.
 */
add_image_size('tyrepol-card', 640, 480, true);
add_image_size('tyrepol-gallery', 900, 700, true);

/**
 * Lista „Aktualności” pokazuje 6 wpisów na stronę (tyle samo, ile w wersji statycznej pokazywał
 * jeden „doładowany” pakiet kart) — kolejne 6 pod przyciskiem „Załaduj więcej aktualności”.
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_home()) {
        $query->set('posts_per_page', 6);
    }
});

/**
 * Uwaga dot. edytora treści: strony zbudowane polami ACF (Strona główna, szablon
 * „Elastyczna strona”) mają w ustawieniach grupy pól włączone „Hide on screen → Content
 * Editor” (patrz pliki w /acf-json), więc domyślny edytor Gutenberga nie miesza się z polami.
 * Strony proste (polityka-*) korzystają ze zwykłego edytora WordPress bez zmian.
 */
