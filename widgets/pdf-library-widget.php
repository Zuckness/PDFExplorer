<?php
namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
require_once(__DIR__ . '/pdf-library-controls.php');
require_once(__DIR__ . '/pdf-library-utils.php');
class PDF_Library_Widget extends Widget_Base {
    use PDF_Library_Widget_Controls;
    use PDF_Library_Widget_Utils;
    public function get_name() {
        return 'pdf_library_widget';
    }

    public function get_title() {
        return 'PDF Library';
    }

    public function get_icon() {
        return 'eicon-document';
    }

    public function get_categories() {
        return ['general'];
    }
    public function get_script_depends() {
        return ['pdf-library-script'];
    }
    
    public function get_style_depends() {
        return ['pdf-library-style'];
    }


    protected function register_controls() {
        $this->register_filter_controls();
        $this->register_pagination_controls();
        $this->register_box_style_controls();
        $this->register_category_header_controls();
        $this->register_subcategory_header_controls();
        $this->register_typography_controls();
        $this->register_search_style_controls();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        $author = $settings['author'];
        $year = $settings['year'];
        $category = $settings['category'];
        $show_meta = $settings['display_meta'] ?? [];
        $group_mode = $settings['group_mode'] ?? 'category_year';
        $sort_by = $settings['sort_by'] ?? 'title_asc';
    
        $args = [
            'post_type' => 'attachment',
            'post_mime_type' => 'application/pdf',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'meta_query' => []
        ];
    
        if (!empty($author)) $args['author__in'] = $author;
        if (!empty($year)) $args['meta_query'][] = ['key' => 'pdf_year', 'value' => $year, 'compare' => 'IN'];
        if (!empty($category)) $args['meta_query'][] = ['key' => 'pdf_category', 'value' => $category, 'compare' => 'IN'];
    
        $pdfs = get_posts($args);
        if (empty($pdfs)) {
            echo '<p>No PDFs found.</p>';
            return;
        }
    
        usort($pdfs, function ($a, $b) use ($sort_by) {
            $a_title = get_the_title($a->ID);
            $b_title = get_the_title($b->ID);
            $a_year = get_post_meta($a->ID, 'pdf_year', true);
            $b_year = get_post_meta($b->ID, 'pdf_year', true);
    
            switch ($sort_by) {
                case 'title_asc': return strcmp($a_title, $b_title);
                case 'title_desc': return strcmp($b_title, $a_title);
                case 'year_asc': return strcmp($a_year, $b_year);
                case 'year_desc': return strcmp($b_year, $a_year);
                default: return 0;
            }
        });
    
        $grouped = [];
        foreach ($pdfs as $pdf) {
            $cat = get_post_meta($pdf->ID, 'pdf_category', true) ?: 'Uncategorized';
            $yr = get_post_meta($pdf->ID, 'pdf_year', true) ?: 'Unspecified';
    
            switch ($group_mode) {
                case 'year_category':
                    $grouped[$yr][$cat][] = $pdf;
                    break;
                case 'category_year':
                    $grouped[$cat][$yr][] = $pdf;
                    break;
                case 'year':
                    $grouped[$yr][] = $pdf;
                    break;
                case 'category':
                    $grouped[$cat][] = $pdf;
                    break;
                default:
                    $grouped['All'][] = $pdf;
                    break;
            }
        }
    
        $first_pdf = wp_get_attachment_url($pdfs[0]->ID);
        $widget_id = $this->get_id();
    
        ?>
    
        <div 
            class="pdf-library-container"
            data-widget-id="<?php echo esc_attr($widget_id); ?>"
            data-enable-pagination="<?php echo esc_attr($settings['enable_pagination'] ?? 'no'); ?>"
            data-pagination-mode="<?php echo esc_attr($settings['pagination_mode'] ?? 'numeric'); ?>"
            data-items-per-page="<?php echo esc_attr($settings['items_per_page'] ?? 10); ?>"
        >
    
            <div class="pdf-library-sidebar">
    
                <?php if (($settings['enable_search'] ?? 'no') === 'yes'): ?>
                    <div class="pdf-search-bar">
                        <input type="text" id="pdfSearchInput-<?php echo esc_attr($widget_id); ?>" placeholder="<?php echo esc_attr($settings['search_placeholder']); ?>">
                    </div>
                <?php endif; ?>
    
                <div class="pdf-list">
                    <ul id="pdfList-<?php echo esc_attr($widget_id); ?>">
                        <?php foreach ($grouped as $group_label => $subgroups): ?>
                            <?php if (is_array($subgroups)): ?>
                                <?php foreach ($subgroups as $subgroup_label => $items): ?>
                                    <?php if (is_array($items)): ?>
                                        <?php foreach ($items as $pdf): 
                                            $title = str_replace('-', ' ', get_the_title($pdf->ID));
                                            $url = wp_get_attachment_url($pdf->ID);
                                            $meta_parts = [];
                                            if (in_array('year', $show_meta)) $meta_parts[] = get_post_meta($pdf->ID, 'pdf_year', true);
                                            if (in_array('category', $show_meta)) $meta_parts[] = str_replace('-', ' ', get_post_meta($pdf->ID, 'pdf_category', true));
                                            if (in_array('author', $show_meta)) $meta_parts[] = get_the_author_meta('display_name', $pdf->post_author);
                                        ?>
                                            <li data-url="<?php echo esc_url($url); ?>">
                                                <?php echo esc_html($title); ?><br>
                                                <span class="pdf-meta"><?php echo esc_html(implode(' | ', array_filter($meta_parts))); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php
                                            $pdf = $items;
                                            $title = str_replace('-', ' ', get_the_title($pdf->ID));
                                            $url = wp_get_attachment_url($pdf->ID);
                                            $meta_parts = [];
                                            if (in_array('year', $show_meta)) $meta_parts[] = get_post_meta($pdf->ID, 'pdf_year', true);
                                            if (in_array('category', $show_meta)) $meta_parts[] = str_replace('-', ' ', get_post_meta($pdf->ID, 'pdf_category', true));
                                            if (in_array('author', $show_meta)) $meta_parts[] = get_the_author_meta('display_name', $pdf->post_author);
                                        ?>
                                        <li data-url="<?php echo esc_url($url); ?>">
                                            <?php echo esc_html($title); ?><br>
                                            <span class="pdf-meta"><?php echo esc_html(implode(' | ', array_filter($meta_parts))); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php
                                    $pdf = $subgroups;
                                    $title = str_replace('-', ' ', get_the_title($pdf->ID));
                                    $url = wp_get_attachment_url($pdf->ID);
                                    $meta_parts = [];
                                    if (in_array('year', $show_meta)) $meta_parts[] = get_post_meta($pdf->ID, 'pdf_year', true);
                                    if (in_array('category', $show_meta)) $meta_parts[] = str_replace('-', ' ', get_post_meta($pdf->ID, 'pdf_category', true));
                                    if (in_array('author', $show_meta)) $meta_parts[] = get_the_author_meta('display_name', $pdf->post_author);
                                ?>
                                <li data-url="<?php echo esc_url($url); ?>">
                                    <?php echo esc_html($title); ?><br>
                                    <span class="pdf-meta"><?php echo esc_html(implode(' | ', array_filter($meta_parts))); ?></span>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div id="pdfPagination-<?php echo esc_attr($widget_id); ?>" class="pdf-pagination"></div>
                </div>
            </div>
    
            <div class="pdf-preview">
                <iframe id="pdfViewer-<?php echo esc_attr($widget_id); ?>" src="<?php echo esc_url($first_pdf); ?>#toolbar=0"></iframe>
            </div>
        </div>
    
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new PDF_Library_Widget());
