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
        printf(
            '<tr><th scope="row" style="font-weight:400;"><label for="tyrepol_cecha_%1$d">%2$s</label><br><span class="description">(%3$s)</span></th><td><input type="text" class="regular-text" id="tyrepol_cecha_%1$d" name="tyrepol_cecha_wartosc[%1$d]" value="%4$s"></td></tr>',
            $term->term_id,
            esc_html($term->name),
            esc_html($typ_label),
            esc_attr($value)
        );
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

        $columns[] = [
            'label'   => $term->name,
            'type'    => ($typ === 'ikona' && $icon_id) ? 'icon' : 'text',
            'icon_id' => $icon_id,
            'values'  => $values,
        ];
    }
    return $columns;
}
