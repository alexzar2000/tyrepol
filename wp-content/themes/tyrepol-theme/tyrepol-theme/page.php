<?php
/**
 * Domyślny szablon prostej strony (np. Polityka prywatności, Polityka cookies) — treść w
 * całości pochodzi ze zwykłego edytora WordPress (Dodaj/Edytuj stronę), nagłówek i data
 * ostatniej aktualizacji generują się same.
 */
if (!defined('ABSPATH')) exit;
get_header();
while (have_posts()) : the_post(); ?>

<section class="legal legal--top">
  <div class="legal__inner">
    <h1 class="legal__title"><?php the_title(); ?></h1>
    <p class="legal__updated"><?php printf(esc_html__('Ostatnia aktualizacja: %s', 'tyrepol'), esc_html(get_the_modified_date('j F Y'))); ?></p>
    <div class="legal__section legal__content">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<?php endwhile;
get_footer();
