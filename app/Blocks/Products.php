<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use WP_Query;

class Products extends Block
{
    public $name = 'Produkty';
    public $description = 'products';
    public $slug = 'products';
    public $category = 'woocommerce';
    public $icon = 'store';
    public $keywords = ['produkty', 'woocommerce', 'sklep', 'pętla', 'loop'];
    public $mode = 'edit';
    public $supports = [
        'align' => false,
        'mode' => false,
        'jsx' => true,
        'anchor' => true,
        'customClassName' => true,
    ];

    public function fields()
    {
        $products = new FieldsBuilder('products');

        $products
            ->setLocation('block', '==', 'acf/products')
           ->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Produkty',
				'open' => false,
				'multi_expand' => true,
			])
			->addTab('Elementy', ['placement' => 'top'])
            ->addWysiwyg('txt', [
                'label' => 'Treść nad produktami',
                'toolbar' => 'full',
                'media_upload' => false,
            ])
            ->addNumber('posts_per_page', [
                'label' => 'Liczba produktów na stronę',
                'default_value' => 10,
            ])
            // Usunęliśmy pole wyboru kategorii
            ->addTab('Ustawienia bloku', ['placement' => 'top'])
            ->addText('section_id', [
                'label' => 'ID',
            ])
            ->addText('section_class', [
                'label' => 'Dodatkowe klasy CSS',
            ])
            ->addTrueFalse('flip', [
                'label' => 'Odwrotna kolejność (sidebar po prawej)',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addTrueFalse('nomt', [
                'label' => 'Usunięcie marginesu górnego',
                'ui' => 1,
            ])
            ->addSelect('background', [
                'label' => 'Kolor tła',
                'choices' => [
                    'none' => 'Brak (domyślne)',
                    'section-white' => 'Białe',
                    'section-light' => 'Jasne',
                    'section-gray' => 'Szare',
                    'section-brand' => 'Marki',
                    'section-dark' => 'Ciemne',
                ],
                'default_value' => 'none',
            ]);

        return $products;
    }

    public function productQuery()
    {
        // Pobieramy numer bieżącej strony dla paginacji
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = [
            'post_type' => 'product',
            'posts_per_page' => get_field('posts_per_page') ?: 10,
            'paged' => $paged, // Dodajemy paginację do zapytania
            'post_status' => 'publish',
        ];

        return new WP_Query($args);
    }


      public function getProductCategories()
    {
        $all_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ]);

        if (is_wp_error($all_categories)) {
            return [];
        }

        $category_tree = [];
        $categories_by_id = [];

        // Indeksujemy wszystkie kategorie po ich ID
        foreach ($all_categories as $category) {
            $categories_by_id[$category->term_id] = $category;
            $category->children = []; // Inicjalizujemy pustą tablicę na dzieci
        }

        // Budujemy drzewo
        foreach ($all_categories as $category) {
            if ($category->parent == 0) {
                // To jest kategoria nadrzędna
                $category_tree[] = $category;
            } else {
                // To jest kategoria podrzędna, dodajemy ją do jej rodzica
                if (isset($categories_by_id[$category->parent])) {
                    $categories_by_id[$category->parent]->children[] = $category;
                }
            }
        }

        return $category_tree;
    }

    public function with()
    {
         return [
            'block_title' => get_field('block-title'),
            'content' => get_field('txt'),
            'product_query' => $this->productQuery(),
            'product_categories' => $this->getProductCategories(), // <-- DODAJ TĘ LINIĘ
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'flip' => get_field('flip'),
            'nomt' => get_field('nomt'),
            'background' => get_field('background'),
        ];
    }
}