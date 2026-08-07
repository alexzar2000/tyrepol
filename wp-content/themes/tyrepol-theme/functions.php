<?php
function tyrepol_theme_setup() {
    add_theme_support('title-tag'); 
    add_theme_support('post-thumbnails'); 
}
add_action('after_setup_theme', 'tyrepol_theme_setup');

// Підключення стилів та скриптів
function tyrepol_enqueue_scripts() {
    // Шрифти Google Fonts
    wp_enqueue_style('tyrepol-google-fonts', 'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap', array(), null);
    
    // Swiper CSS
    wp_enqueue_style('tyrepol-swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null);
    
    // Ваш основний стиль (assets/style.css)
    wp_enqueue_style('tyrepol-main-style', get_template_directory_uri() . '/assets/style.css', array(), null);
    
    // Swiper JS
    wp_enqueue_script('tyrepol-swiper-script', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
    
    // Ваш кастомний скрипт (assets/script.js)
    wp_enqueue_script('tyrepol-main-script', get_template_directory_uri() . '/assets/script.js', array('tyrepol-swiper-script'), null, true);
}
add_action('wp_enqueue_scripts', 'tyrepol_enqueue_scripts');