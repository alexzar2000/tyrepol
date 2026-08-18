<?php
/**
 * Walker menu głównego — zamienia standardowe menu WordPress (Wygląd → Menu) na dokładnie
 * taki sam HTML/klasy BEM, jakie miała statyczna wersja (header__item, header__dropdown-menu…),
 * dzięki czemu CSS z assets/style.css działa bez żadnych zmian.
 *
 * Budowa menu w panelu: dodaj pozycje najwyższego poziomu (Główna, Opony / Marki, O TyrePol,
 * Kontakt) oraz jedną pozycję „Baza wiedzy” (link niestandardowy, adres „#”), a pod nią —
 * przeciągając w prawo — podpozycje (np. „Aktualności” wskazujące na stronę Aktualności).
 */

if (!defined('ABSPATH')) exit;

class TyrePol_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="header__dropdown-menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes, true);
        $is_current   = in_array('current-menu-item', $item->classes, true)
            || in_array('current-menu-parent', $item->classes, true)
            || in_array('current-menu-ancestor', $item->classes, true);

        $url = esc_url($item->url ?: '#');
        $title = esc_html($item->title);

        if ($depth === 0) {
            $li_class = 'header__item' . ($has_children ? ' header__item--dropdown' : '');
            $link_class = 'header__link' . ($is_current ? ' header__link--active' : '') . ($has_children ? ' header__dropdown-toggle' : '');

            $output .= '<li class="' . esc_attr($li_class) . '">';
            $output .= '<a class="' . esc_attr($link_class) . '" href="' . $url . '">' . $title;
            if ($has_children) {
                $output .= ' <span class="header__dropdown-arrow">&#9662;</span>';
            }
            $output .= '</a>';
        } else {
            $link_class = 'header__dropdown-link' . ($is_current ? ' header__dropdown-link--active' : '');
            $output .= '<li class="header__dropdown-item">';
            $output .= '<a class="' . esc_attr($link_class) . '" href="' . $url . '">' . $title . '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

/**
 * Awaryjne menu (gdy w panelu nie utworzono jeszcze menu „Menu główne”) — te same pozycje,
 * które były zaszyte na stałe w statycznej wersji, żeby strona nigdy nie została bez nawigacji.
 */
function tyrepol_fallback_menu() {
    $current = tyrepol_current_path();
    $items = [
        ['label' => __('Główna', 'tyrepol'), 'url' => home_url('/')],
        ['label' => __('Opony / Marki', 'tyrepol'), 'url' => home_url('/opony/')],
        ['label' => __('O TyrePol', 'tyrepol'), 'url' => home_url('/o-firmie/')],
        ['label' => __('Kontakt', 'tyrepol'), 'url' => home_url('/kontakt/')],
    ];
    echo '<ul class="header__list">';
    foreach ($items as $i) {
        $active = ($i['url'] === $current) ? ' header__link--active' : '';
        printf('<li class="header__item"><a class="header__link%s" href="%s">%s</a></li>', esc_attr($active), esc_url($i['url']), esc_html($i['label']));
    }
    echo '</ul>';
}

function tyrepol_current_path() {
    global $wp;
    return home_url(add_query_arg([], $wp->request)) . '/';
}
