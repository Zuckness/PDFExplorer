<?php
namespace Elementor;

trait PDF_Library_Widget_Utils {
    private function get_authors() {
        $users = get_users();
        $options = [];
        foreach ($users as $user) {
            $options[$user->ID] = $user->display_name;
        }
        return $options;
    }

    private function get_years() {
        global $wpdb;
        $results = $wpdb->get_col("SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'pdf_year'");
        $options = [];
        foreach ($results as $year) {
            $options[$year] = $year;
        }
        return $options;
    }

    private function get_categories_from_pdfs() {
        global $wpdb;
        $results = $wpdb->get_col("SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'pdf_category'");
        $options = [];
        foreach ($results as $cat) {
            $options[$cat] = $cat;
        }
        return $options;
    }
}
