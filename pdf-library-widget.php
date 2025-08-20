<?php
/**
 * Plugin Name: PDF Library Widget for Elementor
 * Description: Upload and list PDF files using filename format (YYYY_title_category.pdf) with filters.
 * Version: 1.0
 * Author: Zuck
 */

if (!defined('ABSPATH')) exit;

// Register Elementor widget
function register_pdf_library_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/pdf-library-widget.php');
    $widgets_manager->register(new \Elementor\PDF_Library_Widget());
}
add_action('elementor/widgets/register', 'register_pdf_library_widget');

// Enqueue frontend styles
function pdf_library_enqueue_styles() {
    wp_enqueue_style(
        'pdf-library-style',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        [],
        filemtime(__DIR__ . '/assets/style.css')
    );
}
add_action('elementor/frontend/after_enqueue_styles', 'pdf_library_enqueue_styles');

// Enqueue frontend scripts
function pdf_library_enqueue_scripts() {
    wp_enqueue_script(
        'pdf-library-script',
        plugin_dir_url(__FILE__) . 'assets/script.js',
        [],
        filemtime(__DIR__ . '/assets/script.js'), // forces version change
        true
    );
}
add_action('elementor/frontend/after_enqueue_scripts', 'pdf_library_enqueue_scripts');


// Extract metadata from PDF filename on upload
function pdf_library_extract_metadata_on_upload($metadata, $attachment_id) {
    $mime = get_post_mime_type($attachment_id);
    if ($mime !== 'application/pdf') return $metadata;

    $file = get_attached_file($attachment_id);
    $filename = basename($file);

    // Match YYYY_title_category.pdf
    if (preg_match('/^(\d{4})_([^_]+)_([^_]+)\.pdf$/i', $filename, $matches)) {
        $year     = $matches[1];
        $rawTitle = $matches[2];
        $category = $matches[3];

        $title = str_replace('-', ' ', $rawTitle); // Optional: convert dashes to spaces

        update_post_meta($attachment_id, 'pdf_year', $year);
        update_post_meta($attachment_id, 'pdf_category', $category);

        wp_update_post([
            'ID'         => $attachment_id,
            'post_title' => $title,
        ]);
    }

    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'pdf_library_extract_metadata_on_upload', 10, 2);
