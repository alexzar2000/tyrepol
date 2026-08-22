<?php
/**
 * Lista aktualności (Ustawienia → Czytanie → „Strona z wpisami”) — standardowe wpisy WordPress.
 * Dodawanie nowej aktualności = zwykły „Dodaj wpis” w panelu (tytuł, obrazek wyróżniający, treść).
 * Przycisk „Załaduj więcej” prowadzi do kolejnej strony wyników (standardowa paginacja WP) —
 * w statycznej wersji był to JS doklejający karty; tu jest to zwykły, w pełni działający link.
 */
if (!defined('ABSPATH')) exit;
get_header();
$top = !is_paged();
?>

<section class="news<?php echo $top ? ' news--top' : ''; ?>" id="aktualnosci">
  <div class="news__inner">

    <?php if ($top) : ?>
    <div class="news__header reveal">
      <h1 class="news__title"><?php echo esc_html(get_the_title(get_option('page_for_posts')) ?: tyrepol_t('Aktualności', 'News')); ?></h1>
      <p class="news__desc"><?php echo esc_html(get_theme_mod('news_intro', tyrepol_t('Najnowsze informacje ze świata TyrePol — premiery, promocje i porady dla kierowców zawodowych.', 'The latest news from TyrePol — launches, promotions and tips for professional drivers.'))); ?></p>
    </div>
    <?php endif; ?>

    <?php if (have_posts()) : ?>
    <div class="news__grid">
      <?php while (have_posts()) : the_post(); ?>
      <article class="news-card reveal">
        <a class="news-card__media-link" href="<?php the_permalink(); ?>">
          <div class="news-card__media">
            <span class="news-card__date">
              <span class="news-card__date-month"><?php echo esc_html(get_the_date('M')); ?></span>
              <span class="news-card__date-day"><?php echo esc_html(get_the_date('j')); ?></span>
              <span class="news-card__date-year"><?php echo esc_html(get_the_date('Y')); ?></span>
            </span>
            <?php if (has_post_thumbnail()) : the_post_thumbnail('tyrepol-card', ['class' => 'news-card__img', 'loading' => 'lazy']); else : ?>
              <div class="news-card__placeholder" aria-hidden="true">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v16H4z"></path><path d="M8 9h8M8 13h8M8 17h5"></path></svg>
              </div>
            <?php endif; ?>
          </div>
        </a>
        <h3 class="news-card__title"><?php the_title(); ?></h3>
        <p class="news-card__excerpt"><?php the_excerpt(); ?></p>
        <a class="news-card__link" href="<?php the_permalink(); ?>"><?php tyrepol_esc_html_e('Czytaj więcej', 'Read more'); ?></a>
      </article>
      <?php endwhile; ?>
    </div>

    <div class="news__actions">
      <?php
      global $wp_query;
      $paged = max(1, get_query_var('paged'));
      if ($paged < $wp_query->max_num_pages) : ?>
        <a class="news__load-more" href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>"><?php tyrepol_esc_html_e('Załaduj więcej aktualności', 'Load more news'); ?></a>
      <?php endif; ?>
    </div>

    <?php else : ?>
      <p class="news__empty"><?php tyrepol_esc_html_e('Brak aktualności do wyświetlenia.', 'No news to display.'); ?></p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
