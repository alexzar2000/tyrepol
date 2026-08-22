<?php
/**
 * Typ wpisu „Opona” (jeden wpis = jeden rozmiar/wariant opony w katalogu) + taksonomie
 * używane do filtrowania katalogu (marka, oś montażu, sezon, typ pojazdu) — odpowiednik
 * dawnej tablicy TIRES zaszytej w assets/script.js, tylko że teraz edytowalny w panelu.
 */

if (!defined('ABSPATH')) exit;

function tyrepol_register_opona_cpt() {

    register_post_type('opona', [
        'labels' => [
            'name'               => tyrepol_t('Opony', 'Tyres'),
            'singular_name'      => tyrepol_t('Opona', 'Tyre'),
            'add_new_item'       => tyrepol_t('Dodaj nową oponę', 'Add new tyre'),
            'edit_item'          => tyrepol_t('Edytuj oponę', 'Edit tyre'),
            'all_items'          => tyrepol_t('Wszystkie opony', 'All tyres'),
            'search_items'       => tyrepol_t('Szukaj opon', 'Search tyres'),
            'not_found'          => tyrepol_t('Nie znaleziono opon', 'No tyres found'),
            'menu_name'          => tyrepol_t('Katalog opon', 'Tyre catalogue'),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-marker',
        // 'page-attributes' daje wbudowane w WordPressa pole „Kolejność” — ustala kolejność
        // wierszy w tabeli rozmiarów na stronie produktu (patrz single-opona.php), gdy kilka
        // wpisów ma ten sam „Wzór bieżnika” + „Markę” (czyli jest tym samym modelem opony).
        'supports'           => ['title', 'thumbnail', 'page-attributes'],
        'has_archive'        => false, // archiwum zastępuje dedykowana strona opony.html (page-opony.php)
        'rewrite'            => ['slug' => 'opony', 'with_front' => false],
        'show_in_menu'       => true,
    ]);

    $taxonomies = [
        'marka-opony' => [
            'label'  => tyrepol_t('Marka', 'Brand'),
            'plural' => tyrepol_t('Marki', 'Brands'),
            'terms'  => [
                'saucerman' => tyrepol_t('Saucerman', 'Saucerman'),
                'falken'    => tyrepol_t('Falken', 'Falken'),
            ],
        ],
        'os-montazu' => [
            'label'  => tyrepol_t('Oś montażu', 'Axle position'),
            'plural' => tyrepol_t('Osie montażu', 'Axle positions'),
            'terms'  => [
                'steer'   => tyrepol_t('Steer (kierowana)', 'Steer'),
                'drive'   => tyrepol_t('Drive (napędowa)', 'Drive'),
                'trailer' => tyrepol_t('Trailer (naczepy)', 'Trailer'),
                'on-off'  => tyrepol_t('On/Off (uniwersalna)', 'On/Off (universal)'),
                'winter'  => tyrepol_t('Winter (zimowa)', 'Winter'),
                'a-p'     => tyrepol_t('A/P', 'A/P'),
            ],
        ],
        'sezon-opony' => [
            'label'  => tyrepol_t('Sezon', 'Season'),
            'plural' => tyrepol_t('Sezony', 'Seasons'),
            'terms'  => [
                'lato'       => tyrepol_t('Lato', 'Summer'),
                'zima'       => tyrepol_t('Zima', 'Winter'),
                'caloroczne' => tyrepol_t('Całoroczne', 'All-season'),
            ],
        ],
        'typ-pojazdu' => [
            'label'  => tyrepol_t('Typ pojazdu', 'Vehicle type'),
            'plural' => tyrepol_t('Typy pojazdów', 'Vehicle types'),
            'terms'  => [
                'ciezarowe' => tyrepol_t('Ciężarowe', 'Trucks'),
                'dostawcze' => tyrepol_t('Dostawcze', 'Vans'),
            ],
        ],
    ];

    foreach ($taxonomies as $slug => $tax) {
        register_taxonomy($slug, ['opona'], [
            'labels' => [
                'name'          => $tax['plural'],
                'singular_name' => $tax['label'],
            ],
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => ['slug' => $slug],
        ]);
    }

    // Zapamiętujemy listę taksonomii + ich domyślne terminy, żeby jednorazowo utworzyć je
    // po aktywacji motywu (patrz tyrepol_maybe_seed_taxonomies poniżej).
    set_transient('tyrepol_taxonomies_definition', $taxonomies, DAY_IN_SECONDS);

    // Taksonomia „Cechy opon” — REJESTR dodatkowych parametrów (poza pkt. wyżej), NIEOGRANICZONY
    // co do liczby (bez seedowania domyślnych terminów — admin dodaje własne w Opony → Cechy opon).
    // Sama nazwa/ikona/sposób wyświetlania siedzi na terminie (patrz acf-json/group_cecha_opony.json),
    // a wartość dla konkretnej opony wpisuje się w osobnym boksie na ekranie edycji wpisu
    // (patrz inc/cechy-opony.php) — dzięki temu nazwa i ikona parametru są spójne dla wszystkich
    // opon naraz, zamiast wpisywać je osobno przy każdej sztuce.
    register_taxonomy('cecha-opony', ['opona'], [
        'labels' => [
            'name'          => tyrepol_t('Cechy opon', 'Tyre features'),
            'singular_name' => tyrepol_t('Cecha opony', 'Tyre feature'),
            'add_new_item'  => tyrepol_t('Dodaj nowy parametr', 'Add new parameter'),
            'edit_item'     => tyrepol_t('Edytuj parametr', 'Edit parameter'),
            'search_items'  => tyrepol_t('Szukaj parametrów', 'Search parameters'),
            'not_found'     => tyrepol_t('Nie znaleziono parametrów', 'No parameters found'),
            'menu_name'     => tyrepol_t('Cechy opon', 'Tyre features'),
        ],
        'hierarchical'      => false,
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => false,
        'show_in_rest'      => true,
        'rewrite'           => false,
    ]);
}
add_action('init', 'tyrepol_register_opona_cpt');

/**
 * Polylang — CPT „Opona” CELOWO NIE jest już rejestrowany jako tłumaczalny (podobnie jak
 * taksonomie katalogowe niżej) — to WSPÓLNY, jeden zestaw opon dla obu wersji językowych strony,
 * bez osobnej kopii EN dla każdej opony. Wcześniej każda opublikowana opona PL dostawała
 * automatycznie kopię EN (jako szkic do ręcznego przetłumaczenia) — ale opona ma tylko DWA pola
 * tekstowe („Wzór bieżnika” i „Rozmiar”), a to są kody/oznaczenia identyczne w obu językach
 * (np. „SSL122”, „315/70R22.5”) — nic tam faktycznie nie trzeba tłumaczyć. Wszystko, co
 * rzeczywiście różni się między językami (nazwy kategorii marka/oś/sezon/typ pojazdu, nazwy
 * i wartości parametrów z „Cech opon”) obsługują osobne pola „nazwa_en” na terminach — patrz
 * acf-json/group_kategorie_en.json, acf-json/group_cecha_opony.json i tyrepol_term_label() /
 * tyrepol_get_opona_cechy_kolumny() — więc dublowanie samej opony jest zbędne, wystarczy dodać ją
 * RAZ i ona pokaże się identycznie w obu wersjach strony (z automatycznie przetłumaczonymi
 * etykietami kolumn i kategorii). Taksonomie katalogowe (marka, oś montażu, sezon, typ pojazdu,
 * cechy opon) z tego samego powodu też NIE są tłumaczalne przez Polylang — kiedyś próbowaliśmy to
 * zrobić, ale powodowało to duplikowanie się terminów (np. dwa razy „Ciężarowe” — jeden z sufiksem
 * „-pl”) za każdym razem, gdy automat kopiował oponę na angielską wersję.
 */

/**
 * Skoro opona nie jest zarządzana przez Polylang, jej adres domyślnie NIE ma prefiksu /en/
 * (zawsze /opony/nazwa/, niezależnie od tego, w jakiej wersji językowej ktoś ją otwiera) — a bez
 * tego prefiksu WordPress/Polylang nie ma jak rozpoznać, że odwiedzający chce wersję angielską
 * (patrz tyrepol_current_lang() w functions.php). Dlatego dodajemy WŁASNĄ, dodatkową regułę
 * przepisywania adresów: /en/opony/nazwa/ prowadzi do TEGO SAMEGO wpisu co /opony/nazwa/, tylko
 * z ustawioną zmienną zapytania „tyrepol_view_lang=en” — dzięki temu strona wie, że ma pokazać
 * angielskie etykiety, mimo że to wciąż jeden, wspólny wpis „opona” (bez osobnej kopii EN).
 */
add_action('init', function () {
    add_rewrite_rule('^en/opony/([^/]+)/?$', 'index.php?opona=$matches[1]&tyrepol_view_lang=en', 'top');
});
add_filter('query_vars', function ($vars) {
    $vars[] = 'tyrepol_view_lang';
    return $vars;
});

/**
 * Reguła przepisywania adresów wyżej zaczyna działać dopiero PO odświeżeniu reguł WordPressa
 * (normalnie trzeba by wejść w Ustawienia → Bezpośrednie odnośniki i kliknąć „Zapisz zmiany”) —
 * robimy to automatycznie, JEDNORAZOWO po wdrożeniu tej zmiany, żeby Bob nie musiał o tym pamiętać.
 */
add_action('init', function () {
    if (get_option('tyrepol_rewrite_flushed_opona_en') === '1') return;
    flush_rewrite_rules();
    update_option('tyrepol_rewrite_flushed_opona_en', '1');
}, 999);

/**
 * Adres pojedynczej opony w konkretnej wersji językowej — patrz reguła przepisywania adresów
 * wyżej. Używane wszędzie tam, gdzie w motywie linkuje się do strony konkretnej opony (karty
 * w katalogu, przełącznik języka w nagłówku na stronie opony), żeby link prowadził do właściwej
 * wersji (PL bez prefiksu, EN z prefiksem /en/), zamiast zawsze do domyślnej polskiej.
 */
function tyrepol_opona_permalink($post_id, $lang = null) {
    if ($lang === null) $lang = tyrepol_current_lang();

    $url = get_permalink($post_id);
    if (!$url || $lang !== 'en') return $url;

    $home = trailingslashit(home_url('/'));
    if (strpos($url, $home) !== 0) return $url; // niestandardowy adres — nie ryzykujemy psucia go

    $path = substr($url, strlen($home));
    if (strpos($path, 'en/') === 0) return $url; // prefiks już jest

    return $home . 'en/' . $path;
}

/**
 * Jednorazowe utworzenie domyślnych terminów (marka/oś/sezon/typ pojazdu), żeby po instalacji
 * motywu filtry w panelu i na katalogu nie były puste. Nie nadpisuje terminów, które już istnieją.
 */
function tyrepol_maybe_seed_taxonomies() {
    if (get_option('tyrepol_taxonomies_seeded')) return;

    $taxonomies = get_transient('tyrepol_taxonomies_definition');
    if (!$taxonomies) return;

    foreach ($taxonomies as $slug => $tax) {
        foreach ($tax['terms'] as $term_slug => $term_name) {
            if (!term_exists($term_slug, $slug)) {
                wp_insert_term($term_name, $slug, ['slug' => $term_slug]);
            }
        }
    }

    update_option('tyrepol_taxonomies_seeded', 1);
}
add_action('init', 'tyrepol_maybe_seed_taxonomies', 20);

/**
 * Sprzątanie zduplikowanych kategorii (marka/oś/sezon/typ pojazdu), które powstały WCZEŚNIEJ,
 * gdy te taksonomie były (błędnie) zarejestrowane jako tłumaczalne w Polylang — automat kopiujący
 * opony na wersję EN tworzył wtedy DRUGI termin z tą samą nazwą i sufiksem „-pl”/„-en” w slugu
 * (np. „Ciężarowe” / ciezarowe-pl obok zwykłego „Ciężarowe” / ciezarowe), zamiast po prostu użyć
 * tego samego terminu dla obu wersji językowych opony. Teraz te taksonomie NIE są już tłumaczalne
 * (patrz wyżej), więc duplikaty się nie tworzą — ale te, które już powstały, trzeba scalić ręcznie
 * (jednym kliknięciem): wszystkie opony przypisane do duplikatu „…-pl”/„…-en” dostają z powrotem
 * zwykły termin bez sufiksu, a duplikat jest usuwany.
 */
function tyrepol_znajdz_duplikaty_kategorii() {
    $duplikaty = [];
    foreach (['marka-opony', 'os-montazu', 'sezon-opony', 'typ-pojazdu'] as $taxonomy) {
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        if (is_wp_error($terms) || empty($terms)) continue;

        $by_slug = [];
        foreach ($terms as $term) $by_slug[$term->slug] = $term;

        foreach ($terms as $term) {
            foreach (['-pl', '-en'] as $sufiks) {
                if (substr($term->slug, -strlen($sufiks)) !== $sufiks) continue;
                $base_slug = substr($term->slug, 0, -strlen($sufiks));
                if (isset($by_slug[$base_slug]) && $by_slug[$base_slug]->name === $term->name) {
                    $duplikaty[] = ['taxonomy' => $taxonomy, 'duplikat' => $term, 'oryginal' => $by_slug[$base_slug]];
                }
            }
        }
    }
    return $duplikaty;
}

add_action('admin_notices', function () {
    if (!current_user_can('manage_categories')) return;
    $duplikaty = tyrepol_znajdz_duplikaty_kategorii();
    if (empty($duplikaty)) return;

    $url = wp_nonce_url(admin_url('admin.php?action=tyrepol_wyczysc_duplikaty_kategorii'), 'tyrepol_wyczysc_duplikaty_kategorii');
    echo '<div class="notice notice-warning"><p>'
        . sprintf(
            tyrepol_esc_html(
                'Wykryto %d zduplikowanych kategorii opon (powstałych wcześniej przez Polylang) — np. dwa razy ta sama nazwa w Typy pojazdów / Sezony / Osie montażu / Marki.',
                'Found %d duplicate tyre categories (created earlier by Polylang) — e.g. the same name twice in Vehicle types / Seasons / Axle positions / Brands.'
            ),
            count($duplikaty)
        )
        . ' <a href="' . esc_url($url) . '" class="button button-primary">'
        . tyrepol_esc_html('Wyczyść duplikaty', 'Clean up duplicates')
        . '</a></p></div>';
});

add_action('admin_action_tyrepol_wyczysc_duplikaty_kategorii', function () {
    if (!current_user_can('manage_categories')) {
        wp_die(tyrepol_esc_html('Brak uprawnień do tej operacji.', 'You don\'t have permission to perform this action.'));
    }
    check_admin_referer('tyrepol_wyczysc_duplikaty_kategorii');

    $duplikaty = tyrepol_znajdz_duplikaty_kategorii();
    $scalono = 0;

    foreach ($duplikaty as $d) {
        $posts = get_posts([
            'post_type'      => 'opona',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'any',
            'tax_query'      => [
                ['taxonomy' => $d['taxonomy'], 'field' => 'term_id', 'terms' => $d['duplikat']->term_id],
            ],
        ]);
        foreach ($posts as $post_id) {
            wp_set_object_terms($post_id, $d['oryginal']->term_id, $d['taxonomy'], true);
        }
        wp_delete_term($d['duplikat']->term_id, $d['taxonomy']);
        $scalono++;
    }

    wp_safe_redirect(add_query_arg('tyrepol_duplikaty_scalone', $scalono, wp_get_referer() ?: admin_url()));
    exit;
});

add_action('admin_notices', function () {
    if (!isset($_GET['tyrepol_duplikaty_scalone'])) return;
    printf(
        '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
        sprintf(
            tyrepol_esc_html('Scalono %d zduplikowanych kategorii.', 'Merged %d duplicate categories.'),
            (int) $_GET['tyrepol_duplikaty_scalone']
        )
    );
});

/**
 * Etykieta wzoru bieżnika i rozmiaru w liście admina — ułatwia rozpoznanie, które wpisy należą
 * do tego samego modelu, bez otwierania każdego z osobna.
 */
add_filter('manage_opona_posts_columns', function ($columns) {
    $columns['wzor_bieznika'] = tyrepol_t('Wzór bieżnika (model)', 'Tread pattern (model)');
    $columns['rozmiar'] = tyrepol_t('Rozmiar', 'Size');
    return $columns;
});
add_action('manage_opona_posts_custom_column', function ($column, $post_id) {
    if (!function_exists('get_field')) return;
    if ($column === 'wzor_bieznika') {
        echo esc_html(get_field('wzor_bieznika', $post_id));
    }
    if ($column === 'rozmiar') {
        echo esc_html(get_field('rozmiar', $post_id));
    }
}, 10, 2);

/**
 * Przypomnienie nad polami przy dodawaniu/edycji opony: wpisy o tym samym „Wzorze bieżnika”
 * i tej samej „Marce” łączą się automatycznie w JEDNĄ kartę produktu w katalogu i JEDNĄ stronę
 * szczegółów z tabelą wszystkich rozmiarów (dokładnie jak w dawnej wersji statycznej) — nie
 * trzeba nic dodatkowo zaznaczać, wystarczy wpisać identyczny „Wzór bieżnika” w każdym wariancie.
 * Pole „Kolejność” (panel „Atrybuty” po prawej) ustala kolejność wierszy w tej tabeli.
 */
add_action('edit_form_after_title', function ($post) {
    if (!$post || $post->post_type !== 'opona') return;
    echo '<div class="notice notice-info inline" style="margin:14px 0 0;padding:10px 14px;">'
        . '<p>' . tyrepol_esc_html('Jeden wpis = jeden rozmiar. Kilka wpisów z TAKIM SAMYM „Wzorem bieżnika” i TĄ SAMĄ „Marką” (po prawej) automatycznie łączy się w jedną kartę w katalogu i jedną stronę produktu z tabelą wszystkich rozmiarów — nic więcej nie trzeba zaznaczać.', 'One entry = one size. Several entries with the SAME "Tread pattern" and the SAME "Brand" (on the right) automatically merge into one catalogue card and one product page with a table of all sizes — nothing else needs to be set.') . '</p>'
        . '<p>' . tyrepol_esc_html('Kolejność wierszy w tabeli rozmiarów ustawia pole „Kolejność” w panelu „Atrybuty” po prawej (mniejsza liczba = wyżej).', 'The row order in the size table is set by the "Order" field in the "Attributes" panel on the right (a lower number = higher up).') . '</p>'
        . '<p>' . tyrepol_esc_html('Ta opona pokaże się identycznie na polskiej i angielskiej wersji strony — nie trzeba tworzyć osobnej kopii angielskiej. Nazwy kategorii (marka/oś/sezon/typ pojazdu) i etykiety parametrów tłumaczą się same, o ile mają wypełnione pole „Nazwa (EN)” / „Nazwa parametru (EN)”.', 'This tyre will appear identically on the Polish and English versions of the site — no separate English copy is needed. Category names (brand/axle/season/vehicle type) and parameter labels translate themselves automatically, as long as their "Name (EN)" / "Parameter name (EN)" field is filled in.') . '</p>'
        . '</div>';
});

/**
 * Kopiuje WSZYSTKIE dane jednej opony na drugą (już istniejący, pusty wpis „opona”) — funkcja
 * pomocnicza używana przy ręcznym duplikowaniu (patrz niżej). Kopiuje WSZYSTKIE pola ACF (w tym
 * „Wzór bieżnika”/„Rozmiar”), wartości „Dodatkowych parametrów” z rejestru „Cechy opon” (patrz
 * inc/cechy-opony.php) oraz zdjęcie wyróżniające (to też zwykłe post meta), a także taksonomie
 * katalogowe (marka, oś montażu, sezon, typ pojazdu) — BEZ „cecha-opony”, bo to rejestr definicji
 * parametrów, a nie przypisanie do konkretnej opony.
 */
function tyrepol_kopiuj_dane_opony($z_id, $do_id) {
    foreach (get_post_meta($z_id) as $key => $values) {
        if (in_array($key, ['_edit_lock', '_edit_last'], true)) continue;
        foreach ($values as $value) {
            add_post_meta($do_id, $key, maybe_unserialize($value));
        }
    }

    foreach (get_object_taxonomies('opona') as $taxonomy) {
        if ($taxonomy === 'cecha-opony') continue;
        $terms = wp_get_object_terms($z_id, $taxonomy, ['fields' => 'ids']);
        if (!is_wp_error($terms) && !empty($terms)) {
            wp_set_object_terms($do_id, $terms, $taxonomy);
        }
    }
}

/**
 * Duplikowanie opony jednym kliknięciem — szybkie tworzenie kolejnego wariantu (np. innego
 * rozmiaru tego samego modelu). Nowa kopia trafia jako szkic, żeby nic nie opublikowało się
 * przypadkiem bez sprawdzenia.
 */
add_filter('post_row_actions', function ($actions, $post) {
    if (!$post || $post->post_type !== 'opona' || !current_user_can('edit_posts')) return $actions;

    $url = wp_nonce_url(
        admin_url('admin.php?action=tyrepol_duplikuj_opone&post=' . $post->ID),
        'tyrepol_duplikuj_opone_' . $post->ID
    );
    $actions['tyrepol_duplikuj'] = '<a href="' . esc_url($url) . '">' . tyrepol_esc_html('Duplikuj', 'Duplicate') . '</a>';
    return $actions;
}, 10, 2);

add_action('admin_action_tyrepol_duplikuj_opone', function () {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_die(tyrepol_esc_html('Brak uprawnień do tej operacji.', 'You don\'t have permission to perform this action.'));
    }
    check_admin_referer('tyrepol_duplikuj_opone_' . $post_id);

    $original = get_post($post_id);
    if (!$original || $original->post_type !== 'opona') {
        wp_die(tyrepol_esc_html('Nie znaleziono opony do duplikowania.', 'Tyre to duplicate not found.'));
    }

    $new_id = wp_insert_post([
        'post_title'   => $original->post_title . ' ' . tyrepol_t('(kopia)', '(copy)'),
        'post_content' => $original->post_content,
        'post_excerpt' => $original->post_excerpt,
        'post_status'  => 'draft',
        'post_type'    => 'opona',
        'post_author'  => get_current_user_id(),
        'menu_order'   => $original->menu_order,
    ], true);

    if (is_wp_error($new_id)) {
        wp_die(esc_html($new_id->get_error_message()));
    }

    tyrepol_kopiuj_dane_opony($post_id, $new_id);

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
    exit;
});

/**
 * Sprzątanie angielskich „klonów” opon, które automat tworzył WCZEŚNIEJ (gdy każda opublikowana
 * opona PL dostawała automatycznie kopię EN jako szkic do przetłumaczenia). Opony NIE są już
 * tłumaczalne (patrz komentarz przy rejestracji CPT wyżej) — te stare, nigdy nieopublikowane kopie
 * EN są teraz zbędne i tylko zaśmiecają listę „Wszystkie opony”. Wykrywamy je bardzo ostrożnie:
 * musi to być SZKIC, oznaczony przez Polylang jako angielski, połączony jako tłumaczenie
 * z OPUBLIKOWANYM polskim wpisem, z IDENTYCZNYM „Wzorem bieżnika” i „Rozmiarem” (dokładnie to
 * kopiował automat) — żeby przypadkiem nie przenieść do kosza czyjegoś prawdziwego, nowego szkicu.
 */
function tyrepol_znajdz_zbedne_klony_opon() {
    if (!function_exists('pll_get_post_language') || !function_exists('pll_get_post')) return [];

    $draft_ids = get_posts([
        'post_type'      => 'opona',
        'post_status'    => 'draft',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $do_kosza = [];
    foreach ($draft_ids as $draft_id) {
        if (pll_get_post_language($draft_id) !== 'en') continue;

        $pl_id = pll_get_post($draft_id, 'pl');
        if (!$pl_id || get_post_status($pl_id) !== 'publish') continue;

        $wzor_zgodny    = get_field('wzor_bieznika', $draft_id) === get_field('wzor_bieznika', $pl_id);
        $rozmiar_zgodny = get_field('rozmiar', $draft_id) === get_field('rozmiar', $pl_id);

        if ($wzor_zgodny && $rozmiar_zgodny) {
            $do_kosza[] = $draft_id;
        }
    }
    return $do_kosza;
}

add_action('admin_notices', function () {
    if (!current_user_can('edit_posts')) return;
    $do_kosza = tyrepol_znajdz_zbedne_klony_opon();
    if (empty($do_kosza)) return;

    $url = wp_nonce_url(admin_url('admin.php?action=tyrepol_wyczysc_klony_opon'), 'tyrepol_wyczysc_klony_opon');
    echo '<div class="notice notice-warning"><p>'
        . sprintf(
            tyrepol_esc_html(
                'Znaleziono %d zbędnych angielskich kopii opon (utworzonych wcześniej automatycznie, nigdy nieopublikowanych) — opony nie potrzebują już osobnej wersji angielskiej. Można je bezpiecznie przenieść do kosza.',
                'Found %d unnecessary English tyre copies (created earlier automatically, never published) — tyres no longer need a separate English version. They can be safely moved to trash.'
            ),
            count($do_kosza)
        )
        . ' <a href="' . esc_url($url) . '" class="button button-primary">'
        . tyrepol_esc_html('Przenieś do kosza', 'Move to trash')
        . '</a></p></div>';
});

add_action('admin_action_tyrepol_wyczysc_klony_opon', function () {
    if (!current_user_can('edit_posts')) {
        wp_die(tyrepol_esc_html('Brak uprawnień do tej operacji.', 'You don\'t have permission to perform this action.'));
    }
    check_admin_referer('tyrepol_wyczysc_klony_opon');

    $do_kosza = tyrepol_znajdz_zbedne_klony_opon();
    foreach ($do_kosza as $id) {
        wp_trash_post($id);
    }

    wp_safe_redirect(add_query_arg('tyrepol_klony_wyczyszczone', count($do_kosza), wp_get_referer() ?: admin_url()));
    exit;
});

add_action('admin_notices', function () {
    if (!isset($_GET['tyrepol_klony_wyczyszczone'])) return;
    printf(
        '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
        sprintf(
            tyrepol_esc_html('Przeniesiono do kosza %d zbędnych kopii.', 'Moved %d unnecessary copies to trash.'),
            (int) $_GET['tyrepol_klony_wyczyszczone']
        )
    );
});
