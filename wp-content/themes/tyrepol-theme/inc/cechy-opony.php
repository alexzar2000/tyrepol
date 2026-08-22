<?php
/**
 * Dodatkowe, NIEOGRANICZONE parametry opon — rejestr „Cechy opon” (taksonomia zarejestrowana
 * w inc/cpt-opona.php) + boks z wartościami na ekranie edycji opony + budowanie kolumn tabeli
 * specyfikacji na stronie produktu (single-opona.php).
 *
 * Jak to działa:
 * 1) Admin dodaje nowy parametr RAZ w Opony → Cechy opon (nazwa = nazwa terminu) i ustawia mu
 *    sposób wyświetlania (tekst / ikona) + ewentualną ikonę — pola ACF na terminie, patrz
 *    acf-json/group_cecha_opony.json. Dzięki temu nazwa i ikona są spójne dla wszystkich opon.
 * 2) Na ekranie edycji KAŻDEJ opony pojawia się boks „Dodatkowe parametry” z jednym polem
 *    tekstowym na KAŻDY parametr z rejestru (patrz tyrepol_render_cechy_meta_box) — nowo dodany
 *    parametr od razu jest tam widoczny, bez zmian w kodzie.
 * 3) Puste pole = ten parametr nie pojawia się w tabeli na stronie produktu dla żadnego wariantu,
 *    w którym go nie wypełniono (patrz tyrepol_get_opona_cechy_kolumny, użyte w single-opona.php).
 */

if (!defined('ABSPATH')) exit;

/**
 * Boks „Dodatkowe parametry (z rejestru „Cechy opon”)” na ekranie edycji wpisu Opona.
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'tyrepol_cechy_dodatkowe',
        tyrepol_t('Dodatkowe parametry (z rejestru „Cechy opon”)', 'Additional parameters (from the "Tyre features" registry)'),
        'tyrepol_render_cechy_meta_box',
        'opona',
        'normal',
        'default'
    );
});

function tyrepol_render_cechy_meta_box($post) {
    $terms = get_terms(['taxonomy' => 'cecha-opony', 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC']);
    wp_nonce_field('tyrepol_cechy_save', 'tyrepol_cechy_nonce');

    if (is_wp_error($terms) || empty($terms)) {
        printf(
            '<p>%s</p>',
            sprintf(
                /* translators: %s: link do ekranu zarządzania rejestrem parametrów */
                tyrepol_esc_html('Rejestr parametrów jest jeszcze pusty. Najpierw dodaj parametr w %s.', 'The parameter registry is still empty. First add a parameter in %s.'),
                '<a href="' . esc_url(admin_url('edit-tags.php?taxonomy=cecha-opony&post_type=opona')) . '">' . tyrepol_esc_html('Opony → Cechy opon', 'Tyres → Tyre features') . '</a>'
            )
        );
        return;
    }

    $values = get_post_meta($post->ID, '_tyrepol_cechy_dodatkowe', true);
    if (!is_array($values)) $values = [];

    echo '<table class="form-table" role="presentation"><tbody>';
    foreach ($terms as $term) {
        $typ = function_exists('get_field') ? get_field('sposob_wyswietlania', 'cecha-opony_' . $term->term_id) : 'tekst';
        $typ_label = ($typ === 'ikona') ? tyrepol_t('ikona', 'icon') : tyrepol_t('tekst', 'text');
        $value = $values[$term->term_id] ?? '';

        // „Lista wyboru” (patrz acf-json/group_cecha_opony.json) — rozwijana lista zamiast pola
        // tekstowego, dla wartości które się powtarzają (np. klasy A–G z etykiety UE opony).
        $typ_wartosci = function_exists('get_field') ? get_field('typ_wartosci', 'cecha-opony_' . $term->term_id) : 'tekst';
        $opcje_raw = ($typ_wartosci === 'lista' && function_exists('get_field')) ? get_field('opcje_listy', 'cecha-opony_' . $term->term_id) : '';
        $opcje = $opcje_raw ? array_filter(array_map('trim', explode("\n", $opcje_raw))) : [];

        printf(
            '<tr><th scope="row" style="font-weight:400;"><label for="tyrepol_cecha_%1$d">%2$s</label><br><span class="description">(%3$s)</span></th><td>',
            $term->term_id,
            esc_html($term->name),
            esc_html($typ_label)
        );

        if (!empty($opcje)) {
            printf('<select id="tyrepol_cecha_%1$d" name="tyrepol_cecha_wartosc[%1$d]">', $term->term_id);
            printf('<option value="">%s</option>', tyrepol_esc_html('— nie wybrano —', '— not selected —'));
            foreach ($opcje as $opcja) {
                printf('<option value="%1$s"%2$s>%1$s</option>', esc_attr($opcja), selected($value, $opcja, false));
            }
            echo '</select>';
        } else {
            printf('<input type="text" class="regular-text" id="tyrepol_cecha_%1$d" name="tyrepol_cecha_wartosc[%1$d]" value="%2$s">', $term->term_id, esc_attr($value));
        }

        echo '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p class="description">' . tyrepol_esc_html('Puste pole = ten parametr nie pojawi się w tabeli na stronie produktu dla tego wariantu.', 'An empty field means this parameter won\'t appear in the table on the product page for this variant.') . '</p>';
}

add_action('save_post_opona', function ($post_id) {
    if (!isset($_POST['tyrepol_cechy_nonce']) || !wp_verify_nonce($_POST['tyrepol_cechy_nonce'], 'tyrepol_cechy_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['tyrepol_cecha_wartosc'] ?? [];
    $clean = [];
    if (is_array($raw)) {
        foreach ($raw as $term_id => $value) {
            $value = sanitize_text_field($value);
            if ($value !== '') $clean[(int) $term_id] = $value;
        }
    }
    update_post_meta($post_id, '_tyrepol_cechy_dodatkowe', $clean);
});

/**
 * Kolumny tabeli specyfikacji budowane z rejestru „Cechy opon” dla podanej listy wariantów
 * (ID wpisów Opona tego samego modelu — patrz single-opona.php). Kolumna trafia do wyniku
 * tylko wtedy, gdy CHOĆ JEDEN wariant ma dla niej wypełnioną wartość; w tym wypadku pozostałe
 * warianty bez wartości dostają w tabeli „—” (dokładnie jak przy M+S / 3PMSF).
 * Zwraca tablicę gotową do złączenia z kolumnami stałymi w single-opona.php:
 * [ ['label'=>, 'type'=>'text'|'icon', 'icon_id'=>attachment_id|null, 'values'=>[post_id=>wartość]] ]
 */
function tyrepol_get_opona_cechy_kolumny($variant_ids) {
    $terms = get_terms(['taxonomy' => 'cecha-opony', 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC']);
    if (is_wp_error($terms) || empty($terms)) return [];

    $columns = [];
    foreach ($terms as $term) {
        $values = [];
        $has_value = false;
        foreach ($variant_ids as $v_id) {
            $stored = get_post_meta($v_id, '_tyrepol_cechy_dodatkowe', true);
            $val = (is_array($stored) && isset($stored[$term->term_id])) ? $stored[$term->term_id] : '';
            $values[$v_id] = $val;
            if ($val !== '') $has_value = true;
        }
        if (!$has_value) continue;

        $typ = function_exists('get_field') ? get_field('sposob_wyswietlania', 'cecha-opony_' . $term->term_id) : 'tekst';
        $icon_id = ($typ === 'ikona' && function_exists('get_field')) ? get_field('ikona', 'cecha-opony_' . $term->term_id) : null;

        // Angielska nazwa parametru (pole „Nazwa parametru (EN)” na terminie) — jeśli wypełniona
        // i strona jest po angielsku, ma pierwszeństwo jako nagłówek kolumny; inaczej zwykła nazwa.
        $label = $term->name;
        if (tyrepol_current_lang() === 'en' && function_exists('get_field')) {
            $nazwa_en = get_field('nazwa_en', 'cecha-opony_' . $term->term_id);
            if ($nazwa_en) $label = $nazwa_en;
        }

        $columns[] = [
            'label'   => $label,
            'type'    => ($typ === 'ikona' && $icon_id) ? 'icon' : 'text',
            'icon_id' => $icon_id,
            'values'  => $values,
        ];
    }
    return $columns;
}

/**
 * Domyślny zestaw parametrów z etykiety UE opony — dodawany RAZ automatycznie (jeśli danego
 * parametru jeszcze nie ma w rejestrze — nazwa musi być identyczna), żeby nie trzeba było
 * ręcznie wpisywać tych samych, powtarzających się parametrów dla każdej marki od zera. Można je
 * potem dowolnie edytować/usuwać w Opony → Cechy opon — to tylko punkt startowy.
 *
 * Klasy A–G (efektywność paliwowa, przyczepność na mokrym) i A–C (opory toczenia) dostają od razu
 * listę wyboru (patrz „typ_wartosci”/„opcje_listy” w acf-json/group_cecha_opony.json), żeby uniknąć
 * literówek — te same litery powtarzają się dla każdej opony. M+S i 3PMSF to typ „ikona” — wystarczy
 * wgrać ikonę raz w Opony → Cechy opon (edycja parametru), a potem w opony wpisywać dowolną
 * niepustą wartość (np. „tak”), żeby ikona pojawiła się w tabeli danego wariantu.
 */
function tyrepol_maybe_seed_cechy_opon() {
    if (get_option('tyrepol_cechy_opon_seeded')) return;

    $klasy_a_g = "A\nB\nC\nD\nE\nF\nG";
    $klasy_a_c = "A\nB\nC";

    $domyslne = [
        ['nazwa' => 'Indeks nośności',            'en' => 'Load index',                    'typ' => 'tekst', 'lista' => ''],
        ['nazwa' => 'Indeks prędkości',            'en' => 'Speed index',                   'typ' => 'tekst', 'lista' => ''],
        ['nazwa' => 'Efektywność paliwowa',        'en' => 'Fuel efficiency class',         'typ' => 'tekst', 'lista' => $klasy_a_g],
        ['nazwa' => 'Przyczepność na mokrym',      'en' => 'Wet grip class',                'typ' => 'tekst', 'lista' => $klasy_a_g],
        ['nazwa' => 'Opory toczenia',              'en' => 'External rolling noise class',  'typ' => 'tekst', 'lista' => $klasy_a_c],
        ['nazwa' => 'Hałas (dB)',                  'en' => 'External rolling noise (dB)',   'typ' => 'tekst', 'lista' => ''],
        ['nazwa' => 'M+S',                         'en' => 'M+S',                           'typ' => 'ikona', 'lista' => ''],
        ['nazwa' => '3PMSF',                       'en' => '3PMSF',                         'typ' => 'ikona', 'lista' => ''],
    ];

    foreach ($domyslne as $def) {
        if (term_exists($def['nazwa'], 'cecha-opony')) continue;

        $result = wp_insert_term($def['nazwa'], 'cecha-opony');
        if (is_wp_error($result)) continue;
        $term_id = $result['term_id'];

        if (function_exists('update_field')) {
            update_field('nazwa_en', $def['en'], 'cecha-opony_' . $term_id);
            update_field('sposob_wyswietlania', $def['typ'], 'cecha-opony_' . $term_id);
            if ($def['lista'] !== '') {
                update_field('typ_wartosci', 'lista', 'cecha-opony_' . $term_id);
                update_field('opcje_listy', $def['lista'], 'cecha-opony_' . $term_id);
            }
        }
    }

    update_option('tyrepol_cechy_opon_seeded', 1);
}
add_action('init', 'tyrepol_maybe_seed_cechy_opon', 21);
