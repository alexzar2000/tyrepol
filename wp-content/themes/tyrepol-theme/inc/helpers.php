<?php
/**
 * Funkcje pomocnicze: odczyt ustawień (ACF Options), biblioteka ikon SVG do wyboru w polach
 * „liczniki” / „karty cech”, oraz obsługa formularzy (kontakt + popup „Darmowa wycena”) przez AJAX
 * — dzięki temu formularze działają dokładnie tak jak w wersji statycznej (bez przeładowania strony),
 * tylko naprawdę wysyłają e-mail zamiast tylko symulować wysyłkę.
 */

if (!defined('ABSPATH')) exit;

/**
 * Bezpieczne pobranie pola ze strony „Ustawienia motywu” (zwykła strona WP, nie ACF Options —
 * patrz inc/ustawienia.php, ACF Free nie ma Options Page).
 */
function tyrepol_opt($field, $default = '') {
    if (!function_exists('get_field')) return $default;
    $page_id = tyrepol_settings_page_id();
    if (!$page_id) return $default;
    $value = get_field($field, $page_id);
    return ($value === null || $value === '') ? $default : $value;
}

/**
 * Zdejmuje pojedynczy, otaczający cały tekst znacznik <p>...</p> — pole WYSIWYG (np.
 * „Tekst zgody RODO”) zawsze zapisuje treść owiniętą w <p>, więc doklejenie czegokolwiek PO
 * takim HTML-u (np. gwiazdki „*” przy checkboxie) ląduje POZA akapitem i łamie się do nowej
 * linijki. Używane tam, gdzie tekst z WYSIWYG ma się znaleźć wewnątrz jednej linijki/etykiety.
 */
function tyrepol_strip_wrapping_p($html) {
    $html = trim((string) $html);
    if (preg_match('/^<p[^>]*>(.*)<\/p>$/is', $html, $m)) {
        return trim($m[1]);
    }
    return $html;
}

/**
 * Zamienia grupę „slotów o stałej liczbie” (np. sekcja_liczniki.licznik_1 … licznik_6) na
 * zwykłą, „odchudzoną” tablicę — pomija sloty, w których redaktor nic nie wpisał.
 * $group     — tablica zwrócona przez get_field() dla pola typu Group (zawiera podpola-sloty).
 * $prefix    — prefiks nazw pól sub-grupy, np. "licznik_" -> licznik_1, licznik_2…
 * $count     — maksymalna liczba slotów zdefiniowana w ACF (patrz acf-json).
 * $required_key — nazwa podpola, którego brak = slot uznajemy za pusty (np. "etykieta", "tytul", "pytanie").
 */
function tyrepol_slots($group, $prefix, $count, $required_key) {
    $items = [];
    if (!is_array($group)) return $items;
    for ($i = 1; $i <= $count; $i++) {
        $row = $group[$prefix . $i] ?? null;
        if (is_array($row) && !empty($row[$required_key])) {
            $items[] = $row;
        }
    }
    return $items;
}

/**
 * Mała biblioteka ikon (te same, które były narysowane na sztywno w statycznej wersji) —
 * w panelu redaktor wybiera ikonę z listy (pole select), a nie wkleja kod SVG.
 */
function tyrepol_icon($key = 'domyslna', $size = 32) {
    $icons = [
        'drogi'      => '<path d="M8 5 2 12l6 7M16 5l6 7-6 7"></path>',
        'magazyn'    => '<path d="M18 8h1a3 3 0 0 1 0 6h-1M2 8h16v6a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4z"></path><path d="M6 2v2M10 2v2M14 2v2"></path>',
        'rozmiar'    => '<path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-4 10.5c.6.5 1 1.3 1 2.1V16h6v-.4c0-.8.4-1.6 1-2.1A6 6 0 0 0 12 3Z"></path>',
        'blokady'    => '<rect x="7" y="8" width="10" height="10" rx="4"></rect><path d="M9 8V6a3 3 0 0 1 6 0v2M2 12h5M17 12h5M4 6l3 3M20 6l-3 3M4 18l3-3M20 18l-3-3"></path>',
        'osie'       => '<polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline>',
        'naczepa'    => '<rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>',
        'rozmiary'   => '<path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>',
        'certyfikat' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path>',
        'cel'        => '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3"></circle><path d="M12 3v3M12 18v3M3 12h3M18 12h3"></path>',
        'zima'       => '<path d="M20 17.58A5 5 0 0 0 18 8h-1.26A8 8 0 1 0 4 16.25"></path><path d="M8 16l-2 3h4l-2 3"></path><path d="M16 16l-2 3h4l-2 3"></path>',
        'dostepnosc' => '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73z"></path><path d="M3.27 6.96 12 12l8.73-5.04"></path><path d="M12 22.08V12"></path>',
        'cena'       => '<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
        'zegar'      => '<circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 16 14"></polyline>',
        'domyslna'   => '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3.5"></circle>',
    ];
    $path = $icons[$key] ?? $icons['domyslna'];
    return '<svg width="' . intval($size) . '" height="' . intval($size) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">' . $path . '</svg>';
}

/** Lista ikon do pola select w ACF (acf-json). */
function tyrepol_icon_choices() {
    return [
        'drogi' => 'Drogi / trasa', 'magazyn' => 'Magazyn', 'rozmiar' => 'Rozmiar / linijka',
        'blokady' => 'Blokada / bezpieczeństwo', 'osie' => 'Osie / warstwy', 'naczepa' => 'Naczepa / ciężarówka',
        'rozmiary' => 'Warianty rozmiarowe', 'certyfikat' => 'Certyfikat', 'cel' => 'Cel / precyzja',
        'zima' => 'Zima / śnieg', 'dostepnosc' => 'Dostępność', 'cena' => 'Cena', 'zegar' => 'Czas', 'domyslna' => 'Domyślna (kółko)',
    ];
}

/**
 * Cząstka „Kontakt” — jedna sekcja użyta w Stronie głównej, Opony, O firmie, Saucerman i Kontakt,
 * zasilana z Ustawienia motywu → Kontakt i social media (żeby dane firmy edytować w jednym miejscu).
 */
function tyrepol_contact_section($top = false) {
    get_template_part('template-parts/contact', null, ['top' => $top]);
}

/**
 * Cząstka „FAQ” — WSPÓLNA dla wszystkich stron (Strona główna, Opony, każda „Elastyczna strona”):
 * treść pytań edytuje się raz w Ustawienia motywu → FAQ, a nie osobno na każdej podstronie —
 * dzięki temu ta sama lista pytań pokazuje się wszędzie automatycznie, bez kopiowania treści.
 * Zwraca true/false — czy sekcja faktycznie się wyrenderowała (przydatne np. do licznika sekcji
 * w template-elastyczna.php).
 */
function tyrepol_faq_section($anchor = 'faq') {
    $items = [];
    for ($i = 1; $i <= 8; $i++) {
        $row = tyrepol_opt('pytanie_' . $i);
        if (!empty($row['pytanie'])) $items[] = $row;
    }
    if (empty($items)) return false;

    get_template_part('template-parts/faq', null, [
        'title'  => tyrepol_opt('faq_tytul', 'FAQ'),
        'desc'   => tyrepol_opt('faq_opis'),
        'items'  => $items,
        'anchor' => $anchor,
    ]);
    return true;
}

/**
 * Obsługa formularzy (kontakt + popup wyceny) przez AJAX — honeypot + nonce, wysyłka wp_mail().
 */
function tyrepol_handle_form_submit() {
    check_ajax_referer('tyrepol_form', 'nonce');

    // Honeypot — pole „website” wypełnione = zgłoszenie bota, udajemy sukces i kończymy.
    if (!empty($_POST['website'])) {
        wp_send_json_success(['message' => __('Dziękujemy.', 'tyrepol')]);
    }

    $type    = sanitize_text_field($_POST['form_type'] ?? 'kontakt');
    $email   = sanitize_email($_POST['email'] ?? '');
    $phone   = sanitize_text_field($_POST['phone'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (!is_email($email)) {
        wp_send_json_error(['message' => __('Podaj poprawny adres e-mail.', 'tyrepol')], 400);
    }

    $to = tyrepol_opt('formularz_odbiorca_email', get_option('admin_email'));
    $lines = [];

    if ($type === 'wycena') {
        $size = sanitize_text_field($_POST['size'] ?? '');
        $qty  = sanitize_text_field($_POST['qty'] ?? '');
        $subject = __('Nowe zapytanie o wycenę — TyrePol', 'tyrepol');
        $lines[] = __('Rozmiar:', 'tyrepol') . ' ' . $size;
        $lines[] = __('Ilość:', 'tyrepol') . ' ' . $qty;
    } else {
        $subject = __('Nowa wiadomość z formularza kontaktowego — TyrePol', 'tyrepol');
    }

    $lines[] = __('E-mail:', 'tyrepol') . ' ' . $email;
    $lines[] = __('Telefon:', 'tyrepol') . ' ' . $phone;
    $lines[] = '';
    $lines[] = $message;

    $sent = wp_mail($to, $subject, implode("\n", $lines), ['Reply-To: ' . $email]);

    if ($sent) {
        wp_send_json_success(['message' => __('Wiadomość została wysłana. Skontaktujemy się wkrótce.', 'tyrepol')]);
    }

    wp_send_json_error(['message' => __('Nie udało się wysłać wiadomości. Spróbuj ponownie lub zadzwoń do nas.', 'tyrepol')], 500);
}
add_action('wp_ajax_tyrepol_send_form', 'tyrepol_handle_form_submit');
add_action('wp_ajax_nopriv_tyrepol_send_form', 'tyrepol_handle_form_submit');

/**
 * URL strony katalogu opon (strona ze slugiem „opony” / szablonem page-opony.php) — używane
 * do budowania linków „Zobacz opony na oś X” i kafelków marek bez zaszywania adresu na sztywno.
 */
function tyrepol_catalog_url() {
    $cached = wp_cache_get('tyrepol_catalog_url', 'tyrepol');
    if ($cached) return $cached;
    $page = get_page_by_path('opony');
    $url = $page ? get_permalink($page) : home_url('/opony/');
    wp_cache_set('tyrepol_catalog_url', $url, 'tyrepol');
    return $url;
}

/**
 * Lista dostępnych rozmiarów opon (do rozwijanej listy w popupie „Darmowa wycena”) —
 * zbierana automatycznie z pola „rozmiar” wpisów CPT „Opona” i cache'owana na 12h,
 * żeby nie odpytywać bazy przy każdym wejściu na stronę.
 */
function tyrepol_get_available_sizes() {
    $cached = get_transient('tyrepol_available_sizes');
    if ($cached !== false) return $cached;

    $sizes = [];
    $posts = get_posts(['post_type' => 'opona', 'posts_per_page' => -1, 'fields' => 'ids', 'post_status' => 'publish']);
    foreach ($posts as $post_id) {
        $size = function_exists('get_field') ? get_field('rozmiar', $post_id) : '';
        if ($size) $sizes[$size] = $size;
    }
    ksort($sizes);
    set_transient('tyrepol_available_sizes', $sizes, 12 * HOUR_IN_SECONDS);
    return $sizes;
}
add_action('save_post_opona', fn() => delete_transient('tyrepol_available_sizes'));

/** Dane przekazywane do JS: URL do admin-ajax.php + nonce, potrzebne do wysyłki formularzy. */
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('tyrepol-script', 'tyrepolForms', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('tyrepol_form'),
    ]);
}, 20);
