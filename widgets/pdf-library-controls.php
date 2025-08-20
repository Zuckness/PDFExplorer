<?php
namespace Elementor;

if (!defined('ABSPATH')) exit;

trait PDF_Library_Widget_Controls {
    protected function register_controls() {
        $this->register_filter_controls();
        $this->register_pagination_controls();
        $this->register_box_style_controls();
        $this->register_category_header_controls();
        $this->register_subcategory_header_controls();
        $this->register_typography_controls();
        $this->register_search_style_controls();
    }

   protected function register_pagination_controls() {
    $this->start_controls_section(
        'pagination_section',
        [
            'label' => __('Pagination', 'pdf-library'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
        'items_per_page',
        [
            'label' => __('Items Per Page', 'pdf-library'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 50,
            'default' => 10,
        ]
    );

    $this->add_control(
        'enable_pagination',
        [
            'label' => __('Enable Pagination', 'pdf-library'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pdf-library'),
            'label_off' => __('No', 'pdf-library'),
            'return_value' => 'yes',
            'default' => 'yes',
        ]
    );

    $this->add_control(
        'pagination_mode',
        [
            'label' => __('Pagination Mode', 'pdf-library'),
            'type' => Controls_Manager::SELECT,
            'default' => 'numeric',
            'options' => [
                'numeric' => __('Numeric Buttons', 'pdf-library'),
                'load_more' => __('Load More Button', 'pdf-library'),
                'infinite' => __('Infinite Scroll', 'pdf-library'),
                'shortened' => __('Shortened Numeric', 'pdf-library'),
            ],
            'condition' => [
                'enable_pagination' => 'yes',
            ],
        ]
    );

    // Load More Button Text
    $this->add_control(
        'load_more_text',
        [
            'label' => __('Load More Button Text', 'pdf-library'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Load More', 'pdf-library'),
            'condition' => [
                'pagination_mode' => 'load_more',
                'enable_pagination' => 'yes',
            ],
        ]
    );
    $this->end_controls_section();
    // Pagination Button Styles - Grouped in a separate section for clarity
    $this->start_controls_section(
        'pagination_style_section',
        [
            'label' => __('Pagination Styles', 'pdf-library'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'enable_pagination' => 'yes',
            ],
        ]
    );

    // Pagination Container Styles
    $this->add_control(
        'pagination_container_bg',
        [
            'label' => __('Pagination Container Background', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $this->add_control(
        'pagination_container_padding',
        [
            'label' => __('Pagination Container Padding', 'pdf-library'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    // Numeric Button Styles
    $this->add_control(
        'pagination_button_color',
        [
            'label' => __('Page Button Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_control(
        'pagination_button_bg',
        [
            'label' => __('Page Button Background', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'pagination_button_border',
            'label' => __('Page Button Border', 'pdf-library'),
            'selector' => '{{WRAPPER}} .pdf-pagination button',
        ]
    );

    $this->add_control(
        'pagination_button_border_radius',
        [
            'label' => __('Page Button Border Radius', 'pdf-library'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    // Active Button Styles
    $this->add_control(
        'pagination_button_active_color',
        [
            'label' => __('Active Button Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button.active' => 'color: {{VALUE}} !important;',
            ],
        ]
    );

    $this->add_control(
        'pagination_button_active_bg',
        [
            'label' => __('Active Button Background', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button.active' => 'background-color: {{VALUE}} !important;',
            ],
        ]
    );

    // Button Hover Styles
    $this->add_control(
        'pagination_button_hover_color',
        [
            'label' => __('Button Hover Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button:hover' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_control(
        'pagination_button_hover_bg',
        [
            'label' => __('Button Hover Background', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination button:hover' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    // Load More Button Specific Styles (class .load-more-btn)
    $this->add_control(
        'load_more_button_color',
        [
            'label' => __('Load More Button Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination .load-more-btn' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_mode' => 'load_more',
                'enable_pagination' => 'yes',
            ],
        ]
    );

    $this->add_control(
        'load_more_button_bg',
        [
            'label' => __('Load More Button Background', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination .load-more-btn' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_mode' => 'load_more',
                'enable_pagination' => 'yes',
            ],
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'load_more_button_border',
            'label' => __('Load More Button Border', 'pdf-library'),
            'selector' => '{{WRAPPER}} .pdf-pagination .load-more-btn',
            'condition' => [
                'pagination_mode' => 'load_more',
                'enable_pagination' => 'yes',
            ],
        ]
    );

    $this->add_control(
        'load_more_button_border_radius',
        [
            'label' => __('Load More Button Border Radius', 'pdf-library'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .pdf-pagination .load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_mode' => 'load_more',
                'enable_pagination' => 'yes',
            ],
        ]
    );

    $this->end_controls_section();
}


    protected function register_box_style_controls() {
        $this->start_controls_section('widget_box_style', [
            'label' => __('Widget Box', 'pdf-library'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('widget_height', [
            'label' => __('Widget Height', 'pdf-library'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 100, 'max' => 2000]],
            'default' => ['unit' => 'px', 'size' => 600],
            'selectors' => [
                '{{WRAPPER}} .pdf-library-container' => 'height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function register_category_header_controls() {
        $this->start_controls_section('category_header_style', [
            'label' => __('Category Header Style', 'pdf-library'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('category_header_text_color', [
            'label' => __('Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-category-header' => 'color: {{VALUE}}',
            ],
        ]);

        $this->add_control('category_header_bg_color', [
            'label' => __('Background Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-category-header' => 'background-color: {{VALUE}}',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_header_typography',
                'selector' => '{{WRAPPER}} .pdf-category-header',
            ]
        );

        $this->add_responsive_control('category_header_padding', [
            'label' => __('Padding', 'pdf-library'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .pdf-category-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function register_subcategory_header_controls() {
        $this->start_controls_section('subcategory_header_style', [
            'label' => __('Subcategory Header Style', 'pdf-library'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('subcategory_header_text_color', [
            'label' => __('Text Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-subcategory-header' => 'color: {{VALUE}}',
            ],
        ]);

        $this->add_control('subcategory_header_bg_color', [
            'label' => __('Background Color', 'pdf-library'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pdf-subcategory-header' => 'background-color: {{VALUE}}',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subcategory_header_typography',
                'selector' => '{{WRAPPER}} .pdf-subcategory-header',
            ]
        );

        $this->add_responsive_control('subcategory_header_padding', [
            'label' => __('Padding', 'pdf-library'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .pdf-subcategory-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function register_filter_controls() {
        $this->start_controls_section('filter_section', [
            'label' => __('Filters', 'pdf-library'),
        ]);

        $this->add_control('author', [
            'label' => __('Author', 'pdf-library'),
            'type' => Controls_Manager::SELECT2,
            'options' => $this->get_authors(),
            'multiple' => true,
            'default' => [],
        ]);

        $this->add_control('year', [
            'label' => __('Year', 'pdf-library'),
            'type' => Controls_Manager::SELECT2,
            'options' => $this->get_years(),
            'multiple' => true,
            'default' => [],
        ]);

        $this->add_control('category', [
            'label' => __('Category', 'pdf-library'),
            'type' => Controls_Manager::SELECT2,
            'options' => $this->get_categories_from_pdfs(),
            'multiple' => true,
            'default' => [],
        ]);

        $this->add_control('group_mode', [
            'label' => __('Group Mode', 'pdf-library'),
            'type' => Controls_Manager::SELECT,
            'default' => 'category_year',
            'options' => [
                'category_year' => __('Category > Year', 'pdf-library'),
                'year_category' => __('Year > Category', 'pdf-library'),
                'category' => __('Category only', 'pdf-library'),
                'year' => __('Year only', 'pdf-library'),
                'none' => __('None', 'pdf-library'),
            ],
        ]);

        $this->add_control('sort_by', [
            'label' => __('Sort By', 'pdf-library'),
            'type' => Controls_Manager::SELECT,
            'default' => 'title_asc',
            'options' => [
                'title_asc' => __('Title (A-Z)', 'pdf-library'),
                'title_desc' => __('Title (Z-A)', 'pdf-library'),
                'year_asc' => __('Year (Oldest First)', 'pdf-library'),
                'year_desc' => __('Year (Newest First)', 'pdf-library'),
            ],
        ]);

        $this->add_control('enable_search', [
            'label' => __('Enable Search', 'pdf-library'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);

        $this->add_control('search_placeholder', [
            'label' => __('Search Placeholder', 'pdf-library'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Search by title...', 'pdf-library'),
            'condition' => ['enable_search' => 'yes'],
        ]);

        $this->add_control('display_meta', [
            'label' => __('Show Metadata', 'pdf-library'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'default' => ['year', 'category', 'author'],
            'options' => [
                'year' => __('Year', 'pdf-library'),
                'category' => __('Category', 'pdf-library'),
                'author' => __('Author', 'pdf-library'),
            ],
        ]);

        $this->end_controls_section();
    }

    protected function register_search_style_controls() {
        $this->start_controls_section(
            'section_search_style',
            [
                'label' => __('Search Bar Style', 'pdf-library'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['enable_search' => 'yes'],
            ]
        );

        $this->add_control(
            'search_input_color',
            [
                'label' => __('Text Color', 'pdf-library'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pdf-search-bar input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_input_bg',
            [
                'label' => __('Background Color', 'pdf-library'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pdf-search-bar input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'search_border',
                'label' => __('Border', 'pdf-library'),
                'selector' => '{{WRAPPER}} .pdf-search-bar input',
            ]
        );

        $this->add_control(
            'search_border_radius',
            [
                'label' => __('Border Radius', 'pdf-library'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pdf-search-bar input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_input_typography',
                'label' => __('Typography', 'pdf-library'),
                'selector' => '{{WRAPPER}} .pdf-search-bar input',
            ]
        );

        $this->add_responsive_control(
            'search_input_padding',
            [
                'label' => __('Padding', 'pdf-library'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pdf-search-bar input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_placeholder_color',
            [
                'label' => __('Placeholder Color', 'pdf-library'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pdf-search-bar input::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pdf-search-bar input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pdf-search-bar input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pdf-search-bar input::-ms-input-placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'search_input_shadow',
                'label' => __('Box Shadow', 'pdf-library'),
                'selector' => '{{WRAPPER}} .pdf-search-bar input',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_typography_controls() {
        $this->start_controls_section('style_section', [
            'label' => __('Typography & Spacing', 'pdf-library'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_heading', [
            'label' => __('Title Typography', 'pdf-library'),
            'type' => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .pdf-list li',
            ]
        );

        $this->add_control('meta_heading', [
            'label' => __('Metadata Typography', 'pdf-library'),
            'type' => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typo',
                'selector' => '{{WRAPPER}} .pdf-meta',
            ]
        );

        $this->add_control('spacing', [
            'label' => __('Spacing Between Items', 'pdf-library'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .pdf-list li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }
}
