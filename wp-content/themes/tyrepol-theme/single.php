<?php
/**
 * Pojedyncza aktualność — standardowy wpis WordPress (tytuł, obrazek wyróżniający, treść
 * z edytora). Sekcja „Zobacz również” pokazuje 3 najnowsze pozostałe wpisy.
 */
if (!defined('ABSPATH')) exit;
get_header();

while (have_posts()) : the_post();
?>

  <section class="article article--top" id="wpis">
    <div class="article__inner">

      <a class="article__back reveal" href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/aktualnosci/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
        <?php esc_html_e('Powrót do aktualności', 'tyrepol'); ?>
      </a>

      <div class="article__header reveal">
        <p class="article__date"><?php echo esc_html(get_the_date('j F Y')); ?></p>
        <h1 class="article__title"><?php the_title(); ?></h1>
      </div>

      <?php if (has_post_thumbnail()) : ?>
        <div class="article__cover reveal"><?php the_post_thumbnail('large'); ?></div>
      <?php else : ?>
        <div class="article__cover reveal" aria-hidden="true">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v16H4z"></path><path d="M8 9h8M8 13h8M8 17h5"></path></svg>
        </div>
      <?php endif; ?>

      <div class="article__body reveal">
        <?php the_content(); ?>
      </div>

      <div class="article__share reveal">
        <span class="article__share-label"><?php esc_html_e('Udostępnij:', 'tyrepol'); ?></span>
        <a class="article__share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Udostępnij na Facebooku', 'tyrepol'); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
        </a>
        <a class="article__share-link" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Udostępnij na LinkedIn', 'tyrepol'); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
        </a>
      </div>

    </div>

    <?php
    $related = new WP_Query([
        'post_type' => 'post', 'posts_per_page' => 3, 'post__not_in' => [get_the_ID()],
        'ignore_sticky_posts' => true, 'orderby' => 'date', 'order' => 'DESC',
    ]);
    if ($related->have_posts()) : ?>
    <div class="article__related">
      <h2 class="article__related-title reveal"><?php esc_html_e('Zobacz również', 'tyrepol'); ?></h2>
      <div class="article__related-grid">
        <?php while ($related->have_posts()) : $related->the_post(); ?>
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
          <a class="news-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e('Czytaj więcej', 'tyrepol'); ?></a>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <?php endif; ?>
  </section>

<?php endwhile; ?>

<?php get_footer(); ?>
