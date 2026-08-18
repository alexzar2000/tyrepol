<?php
/**
 * Domyślny szablon awaryjny (wymagany przez WordPress w każdym motywie klasycznym).
 * W praktyce WordPress prawie nigdy go nie użyje — każdy realny typ strony w tym motywie ma
 * własny, bardziej szczegółowy szablon (front-page.php, home.php, single.php, page.php,
 * single-opona.php, page-opony.php, template-elastyczna.php). Ten plik to tylko bezpieczna
 * „siatka bezpieczeństwa” na wypadek nietypowego adresu, którego żaden z nich nie obsłuży —
 * bez niego WordPress oznacza motyw jako niekompletny/uszkodzony.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

<section class="legal legal--top">
  <div class="legal__inner">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <h1 class="legal__title"><?php the_title(); ?></h1>
      <div class="legal__section legal__content">
        <?php the_content(); ?>
      </div>
    <?php endwhile; else : ?>
      <h1 class="legal__title"><?php esc_html_e('Nie znaleziono treści', 'tyrepol'); ?></h1>
      <p><?php esc_html_e('Przepraszamy, nie udało się odnaleźć żądanej strony.', 'tyrepol'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
