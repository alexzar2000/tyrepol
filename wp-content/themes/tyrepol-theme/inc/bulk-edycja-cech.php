<?php
/**
 * Zbiorcza zmiana kategorii (Marka / Oś montażu / Sezon / Typ pojazdu) dla wielu opon naraz.
 *
 * Domyślny "Edytuj zbiorczo" WordPressa (patrz lista Opony → Wszystkie opony → zaznacz kilka →
 * Edycja zbiorcza) tylko DODAJE zaznaczone kategorie do już istniejących u każdej opony — nigdy
 * nie zastępuje ani nie czyści (tak działa WordPress dla WSZYSTKICH taksonomii hierarchicznych,
 * nie tylko naszych — to nie błąd w tym motywie). Dlatego to własne narzędzie, dostępne jako
 * dodatkowa pozycja w tej samej rozwijanej liście "Edycja zbiorcza", daje wybór:
 *   - Dodaj      — jak domyślne działanie WordPressa (dokłada zaznaczone do istniejących),
 *   - Usuń       — usuwa TYLKO zaznaczone, reszta zostaje bez zmian,
 *   - Ustaw dokładnie — zastępuje CAŁĄ zawartość danej grupy dokładnie tym, co zaznaczono,
 *   - Wyczyść    — usuwa WSZYSTKIE terminy danej grupy (bez zaznaczania niczego).
 *
 * Działanie: zaznaczenie opon na liście + wybranie tej pozycji z "Edycja zbiorcza" przenosi na
 * osobny, prosty ekran (bez własnej pozycji w menu — dostępny tylko z tego przejścia), gdzie
 * wybiera się GRUPĘ (Marka/Oś/Sezon/Typ pojazdu), SPOSÓB (jw.) i — poza „Wyczyść” — konkretne
 * wartości. Lista zaznaczonych wcześniej opon jest pamiętana tymczasowo (15 minut) pod losowym
 * tokenem w przejściowym wpisie (transient), żeby nie trzeba było przepisywać ID opon w adresie.
 */

if (!defined('ABSPATH')) exit;

/** Taksonomie katalogu opon dostępne w tym narzędziu + ich etykiety (do <select>). */
function tyrepol_bulk_taksonomie() {
    return [
        'marka-opony' => tyrepol_t('Marka', 'Brand'),
        'os-montazu'  => tyrepol_t('Oś montażu', 'Axle position'),
        'sezon-opony' => tyrepol_t('Sezon', 'Season'),
        'typ-pojazdu' => tyrepol_t('Typ pojazdu', 'Vehicle type'),
    ];
}

/** Nazwa terminu do wyświetlenia w tym narzędziu — PL i (jeśli wypełnione) EN naraz, tak samo
 * jak w boksie „Typ pojazdu” na ekranie pojedynczej opony (patrz inc/cpt-opona.php). */
function tyrepol_bulk_nazwa_terminu($term) {
    $nazwa_en = function_exists('get_field') ? get_field('nazwa_en', $term->taxonomy . '_' . $term->term_id) : '';
    return $nazwa_en ? $term->name . ' / ' . $nazwa_en : $term->name;
}

/**
 * Dodatkowa pozycja w rozwijanej liście "Edycja zbiorcza" na liście Opon.
 */
add_filter('bulk_actions-edit-opona', function ($actions) {
    $actions['tyrepol_bulk_kategorie'] = tyrepol_t('Zmień kategorie zbiorczo (dodaj/usuń/ustaw/wyczyść)…', 'Bulk-change categories (add/remove/set/clear)…');
    return $actions;
});

/**
 * Obsługa wybrania powyższej pozycji: zamiast od razu coś zmieniać, zapamiętujemy zaznaczone
 * opony pod losowym tokenem (na 15 minut) i przenosimy na ekran wyboru grupy/sposobu/wartości.
 */
add_filter('handle_bulk_actions-edit-opona', function ($redirect_to, $doaction, $post_ids) {
    if ($doaction !== 'tyrepol_bulk_kategorie') return $redirect_to;
    if (empty($post_ids)) return $redirect_to;

    $token = wp_generate_password(20, false);
    set_transient('tyrepol_bulk_ids_' . $token, array_map('intval', $post_ids), 15 * MINUTE_IN_SECONDS);

    return add_query_arg(
        ['page' => 'tyrepol-bulk-kategorie', 'tyrepol_token' => $token],
        admin_url('admin.php')
    );
}, 10, 3);

/**
 * Ukryta strona (bez pozycji w menu — dostępna tylko z przejścia wyżej) z formularzem wyboru
 * grupy / sposobu / wartości.
 */
add_action('admin_menu', function () {
    $hook = add_submenu_page(
        null, // bez własnej pozycji w menu
        tyrepol_t('Zbiorcza zmiana kategorii', 'Bulk category change'),
        tyrepol_t('Zbiorcza zmiana kategorii', 'Bulk category change'),
        'manage_categories',
        'tyrepol-bulk-kategorie',
        'tyrepol_render_bulk_kategorie_page'
    );
    // "load-{$hook}" odpala się PRZED jakimkolwiek wyjściem HTML — bezpieczne miejsce na
    // ewentualne przekierowanie po zapisaniu formularza (patrz funkcja niżej).
    add_action("load-{$hook}", 'tyrepol_obsluz_bulk_kategorie_zapis');
});

/**
 * Odczytuje z transienta listę ID opon zapamiętaną przy przejściu z listy — zwraca tylko te,
 * które NADAL istnieją i są typu „opona” (na wypadek, gdyby coś zostało usunięte w międzyczasie).
 */
function tyrepol_bulk_pobierz_id_opon($token) {
    $ids = get_transient('tyrepol_bulk_ids_' . sanitize_text_field($token));
    if (!is_array($ids)) return [];
    return array_values(array_filter($ids, function ($id) {
        return get_post_type((int) $id) === 'opona';
    }));
}

/**
 * Przetwarza zapis formularza (wywołane na "load-{page}", więc przed jakimkolwiek wyjściem —
 * można bezpiecznie przekierować). Samo wyświetlenie formularza obsługuje funkcja renderująca
 * niżej (wywoływana później, jako właściwa treść strony).
 */
function tyrepol_obsluz_bulk_kategorie_zapis() {
    if (!isset($_POST['tyrepol_bulk_submit'])) return;
    check_admin_referer('tyrepol_bulk_kategorie_apply');
    if (!current_user_can('manage_categories')) {
        wp_die(tyrepol_esc_html('Brak uprawnień do tej operacji.', 'You don\'t have permission to perform this action.'));
    }

    $token     = sanitize_text_field($_POST['tyrepol_token'] ?? '');
    $post_ids  = tyrepol_bulk_pobierz_id_opon($token);
    $taxonomie = tyrepol_bulk_taksonomie();
    $taxonomy  = sanitize_key($_POST['tyrepol_taxonomy'] ?? '');
    $sposob    = sanitize_key($_POST['tyrepol_sposob'] ?? '');

    if (empty($post_ids) || !isset($taxonomie[$taxonomy]) || !in_array($sposob, ['dodaj', 'usun', 'ustaw', 'wyczysc'], true)) {
        wp_die(tyrepol_esc_html('Nieprawidłowe dane formularza (token mógł wygasnąć po 15 minutach — wróć do listy opon i zaznacz je ponownie).', 'Invalid form data (the token may have expired after 15 minutes — go back to the tyre list and select them again).'));
    }

    // Zaznaczone wartości — tylko liczby, i tylko te, które NAPRAWDĘ są terminami tej konkretnej
    // taksonomii (bez tego ktoś mógłby w teorii podstawić w formularzu ID z zupełnie innej
    // taksonomii — sanitizacja "od złej strony" na wszelki wypadek).
    $wybrane_raw = array_map('absint', (array) ($_POST['tyrepol_terms'] ?? []));
    $wybrane = array_values(array_filter($wybrane_raw, function ($term_id) use ($taxonomy) {
        $term = get_term($term_id, $taxonomy);
        return $term && !is_wp_error($term);
    }));

    if ($sposob !== 'wyczysc' && empty($wybrane)) {
        wp_die(tyrepol_esc_html('Nie zaznaczono żadnej wartości — zaznacz przynajmniej jedną albo wybierz „Wyczyść”.', 'No value selected — select at least one, or choose "Clear".'));
    }

    foreach ($post_ids as $post_id) {
        if ($sposob === 'wyczysc') {
            wp_set_object_terms($post_id, [], $taxonomy, false);
            continue;
        }
        if ($sposob === 'ustaw') {
            wp_set_object_terms($post_id, $wybrane, $taxonomy, false);
            continue;
        }
        if ($sposob === 'dodaj') {
            wp_set_object_terms($post_id, $wybrane, $taxonomy, true);
            continue;
        }
        if ($sposob === 'usun') {
            $obecne = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
            if (is_wp_error($obecne)) $obecne = [];
            $pozostale = array_values(array_diff($obecne, $wybrane));
            wp_set_object_terms($post_id, $pozostale, $taxonomy, false);
        }
    }

    delete_transient('tyrepol_bulk_ids_' . $token);

    wp_safe_redirect(add_query_arg([
        'post_type'               => 'opona',
        'tyrepol_bulk_zrobione'   => count($post_ids),
        'tyrepol_bulk_taxonomy'   => $taxonomy,
        'tyrepol_bulk_sposob'     => $sposob,
    ], admin_url('edit.php')));
    exit;
}

/**
 * Treść ukrytej strony: albo formularz wyboru grupy/sposobu/wartości (gdy token jest ważny),
 * albo krótki komunikat, że trzeba wrócić do listy i zaznaczyć opony ponownie (token wygasł/zły).
 */
function tyrepol_render_bulk_kategorie_page() {
    $token = sanitize_text_field($_GET['tyrepol_token'] ?? '');
    $post_ids = tyrepol_bulk_pobierz_id_opon($token);
    $powrot = admin_url('edit.php?post_type=opona');

    echo '<div class="wrap"><h1>' . tyrepol_esc_html('Zbiorcza zmiana kategorii', 'Bulk category change') . '</h1>';

    if (empty($post_ids)) {
        echo '<p>' . tyrepol_esc_html(
            'Nie znaleziono zaznaczonych opon (token mógł wygasnąć po 15 minutach). Wróć do listy i zaznacz je ponownie.',
            'No selected tyres found (the token may have expired after 15 minutes). Go back to the list and select them again.'
        ) . '</p>';
        printf('<p><a href="%s" class="button">%s</a></p>', esc_url($powrot), tyrepol_esc_html('Wróć do listy opon', 'Back to the tyre list'));
        echo '</div>';
        return;
    }

    echo '<p>' . sprintf(
        tyrepol_t('Wybrano %d opon(y):', 'Selected %d tyre(s):'),
        count($post_ids)
    ) . '</p>';
    echo '<ul style="list-style:disc;margin-left:20px;max-height:160px;overflow:auto;">';
    foreach ($post_ids as $post_id) {
        echo '<li>' . esc_html(get_the_title($post_id)) . '</li>';
    }
    echo '</ul>';

    $taxonomie = tyrepol_bulk_taksonomie();
    ?>
    <form method="post">
        <?php wp_nonce_field('tyrepol_bulk_kategorie_apply'); ?>
        <input type="hidden" name="tyrepol_token" value="<?php echo esc_attr($token); ?>">

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><?php tyrepol_esc_html_e('Grupa', 'Group'); ?></th>
                <td>
                    <select name="tyrepol_taxonomy" id="tyrepol-bulk-taxonomy">
                        <?php foreach ($taxonomie as $slug => $label) : ?>
                            <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php tyrepol_esc_html_e('Sposób', 'Method'); ?></th>
                <td>
                    <label style="display:block;margin-bottom:6px;">
                        <input type="radio" name="tyrepol_sposob" value="dodaj" checked>
                        <?php tyrepol_esc_html_e('Dodaj — dokłada zaznaczone do tego, co opona już ma', 'Add — adds the selected value(s) to what the tyre already has'); ?>
                    </label>
                    <label style="display:block;margin-bottom:6px;">
                        <input type="radio" name="tyrepol_sposob" value="usun">
                        <?php tyrepol_esc_html_e('Usuń — zdejmuje TYLKO zaznaczone, reszta zostaje', 'Remove — removes ONLY the selected value(s), the rest stays'); ?>
                    </label>
                    <label style="display:block;margin-bottom:6px;">
                        <input type="radio" name="tyrepol_sposob" value="ustaw">
                        <?php tyrepol_esc_html_e('Ustaw dokładnie — zastępuje całą grupę tym, co zaznaczono', 'Set exactly — replaces the whole group with what\'s selected'); ?>
                    </label>
                    <label style="display:block;">
                        <input type="radio" name="tyrepol_sposob" value="wyczysc" id="tyrepol-bulk-wyczysc">
                        <?php tyrepol_esc_html_e('Wyczyść — usuwa WSZYSTKO z tej grupy (bez zaznaczania niczego)', 'Clear — removes EVERYTHING from this group (nothing to select)'); ?>
                    </label>
                </td>
            </tr>
            <tr id="tyrepol-bulk-terms-row">
                <th scope="row"><?php tyrepol_esc_html_e('Wartości', 'Values'); ?></th>
                <td>
                    <?php foreach ($taxonomie as $slug => $label) :
                        $terms = get_terms(['taxonomy' => $slug, 'hide_empty' => false]);
                        if (is_wp_error($terms)) $terms = [];
                    ?>
                    <div class="tyrepol-bulk-terms" data-taxonomy="<?php echo esc_attr($slug); ?>" style="display:none;">
                        <?php foreach ($terms as $term) : ?>
                            <label style="display:block;">
                                <input type="checkbox" name="tyrepol_terms[]" value="<?php echo (int) $term->term_id; ?>">
                                <?php echo esc_html(tyrepol_bulk_nazwa_terminu($term)); ?>
                            </label>
                        <?php endforeach; ?>
                        <?php if (empty($terms)) : ?>
                            <p class="description"><?php tyrepol_esc_html_e('Brak terminów w tej grupie.', 'No terms in this group.'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="tyrepol_bulk_submit" class="button button-primary">
                <?php tyrepol_esc_html_e('Zastosuj do zaznaczonych opon', 'Apply to selected tyres'); ?>
            </button>
            <a href="<?php echo esc_url($powrot); ?>" class="button"><?php tyrepol_esc_html_e('Anuluj', 'Cancel'); ?></a>
        </p>
    </form>

    <script>
    (function () {
        var taxonomySelect = document.getElementById('tyrepol-bulk-taxonomy');
        var wyczyscRadio    = document.getElementById('tyrepol-bulk-wyczysc');
        var termsRow        = document.getElementById('tyrepol-bulk-terms-row');
        var groups          = document.querySelectorAll('.tyrepol-bulk-terms');
        var sposobRadios    = document.querySelectorAll('input[name="tyrepol_sposob"]');

        function odswiez() {
            var wybranaTaxonomy = taxonomySelect.value;
            groups.forEach(function (el) {
                el.style.display = (el.getAttribute('data-taxonomy') === wybranaTaxonomy) ? '' : 'none';
            });
            // "Wyczyść" nie wymaga zaznaczania żadnych wartości — cały wiersz znika.
            termsRow.style.display = wyczyscRadio.checked ? 'none' : '';
        }

        taxonomySelect.addEventListener('change', odswiez);
        sposobRadios.forEach(function (r) { r.addEventListener('change', odswiez); });
        odswiez();
    })();
    </script>
    <?php
    echo '</div>';
}

/**
 * Komunikat po zapisaniu (przekierowanie z tyrepol_obsluz_bulk_kategorie_zapis wyżej).
 */
add_action('admin_notices', function () {
    if (!isset($_GET['tyrepol_bulk_zrobione'])) return;
    $ekran = get_current_screen();
    if (!$ekran || $ekran->post_type !== 'opona') return;

    $taxonomie = tyrepol_bulk_taksonomie();
    $taxonomy  = sanitize_key($_GET['tyrepol_bulk_taxonomy'] ?? '');
    $sposob    = sanitize_key($_GET['tyrepol_bulk_sposob'] ?? '');
    $label     = $taxonomie[$taxonomy] ?? $taxonomy;
    $sposob_etykiety = [
        'dodaj'   => tyrepol_t('dodano do', 'added to'),
        'usun'    => tyrepol_t('usunięto z', 'removed from'),
        'ustaw'   => tyrepol_t('ustawiono dokładnie w', 'set exactly in'),
        'wyczysc' => tyrepol_t('wyczyszczono w', 'cleared in'),
    ];
    $sposob_label = $sposob_etykiety[$sposob] ?? $sposob;

    printf(
        '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
        sprintf(
            tyrepol_t('Gotowe — %s „%s” w %d opon(ach).', 'Done — %s "%s" in %d tyre(s).'),
            esc_html($sposob_label),
            esc_html($label),
            (int) $_GET['tyrepol_bulk_zrobione']
        )
    );
});
