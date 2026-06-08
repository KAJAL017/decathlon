<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Collection;

class HomepageSeeder extends Seeder
{
    public function run()
    {
        // Clear existing sections
        HomeSection::truncate();

        $sections = [
            [
                'title' => 'Hero Slider',
                'type' => 'hero_banners',
                'sort_order' => 0,
                'settings' => ['position' => 'hero'],
            ],
            [
                'title' => 'Our Commitments',
                'type' => 'service_highlights',
                'sort_order' => 1,
                'settings' => [
                    'items' => [
                        ['title' => 'Free Shipping', 'text' => 'On all orders above ₹999', 'icon' => 'truck'],
                        ['title' => '2 Year Warranty', 'text' => 'Quality guaranteed', 'icon' => 'shield'],
                        ['title' => 'Easy Returns', 'text' => '30-day hassle free', 'icon' => 'refresh'],
                        ['title' => 'Secure Payment', 'text' => '100% safe checkout', 'icon' => 'lock'],
                    ]
                ],
            ],
            [
                'title' => 'Explore Categories',
                'type' => 'category_grid',
                'sort_order' => 2,
                'settings' => ['limit' => 8],
            ],
            [
                'title' => 'Trending Now',
                'subtitle' => 'The hottest gear this season',
                'type' => 'product_slider',
                'sort_order' => 3,
                'settings' => ['filter' => 'trending', 'limit' => 10],
            ],
            [
                'title' => 'Limited Time Offer',
                'subtitle' => 'Flash Sale Ending Soon!',
                'type' => 'countdown_timer',
                'sort_order' => 4,
                'settings' => [
                    'title' => 'SEASON END CLEARANCE',
                    'subtitle' => 'UP TO 70% OFF ON ALL WINTER GEAR',
                    'end_date' => now()->addDays(3)->format('Y-m-d\TH:i'),
                    'button_text' => 'Shop the Sale',
                    'button_link' => '/shop',
                    'background_image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=2000'
                ],
            ],
            [
                'title' => 'Featured Collections',
                'type' => 'collection_list',
                'sort_order' => 5,
                'settings' => ['limit' => 2],
            ],
            [
                'title' => 'Innovation in Sports',
                'type' => 'image_with_text',
                'sort_order' => 6,
                'settings' => [
                    'alignment' => 'left',
                    'image_url' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?auto=format&fit=crop&q=80&w=1500',
                    'title' => 'DESIGNED FOR ATHLETES',
                    'content' => 'Our research and development teams work tirelessly to create products that enhance your performance while ensuring maximum comfort and safety.',
                    'button_text' => 'Our Story',
                    'button_link' => '/about'
                ],
            ],
            [
                'title' => 'What Customers Say',
                'type' => 'testimonials',
                'sort_order' => 7,
                'settings' => [
                    'testimonials' => [
                        ['author' => 'Rahul Sharma', 'quote' => 'The quality of the badminton racket is exceptional. Highly recommend!', 'role' => 'Pro Player'],
                        ['author' => 'Anjali Gupta', 'quote' => 'Fast delivery and very easy returns process. Great shopping experience.', 'role' => 'Fitness Enthusiast'],
                        ['author' => 'Michael D.', 'quote' => 'Decathlon gear is the only gear I trust for my mountain treks.', 'role' => 'Alpinist']
                    ]
                ],
            ],
            [
                'title' => 'Follow Us @Instagram',
                'type' => 'instagram_feed',
                'sort_order' => 8,
                'settings' => [
                    'username' => '@decathlon_india',
                    'images' => [
                        'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=600',
                        'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=600',
                        'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&q=80&w=600',
                        'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&q=80&w=600',
                        'https://images.unsplash.com/photo-1517838276537-c70104047f50?auto=format&fit=crop&q=80&w=600'
                    ]
                ],
            ],
            [
                'title' => 'Frequently Asked',
                'type' => 'faq_section',
                'sort_order' => 9,
                'settings' => [
                    'faqs' => [
                        ['question' => 'Is shipping free?', 'answer' => 'Yes, on all orders above ₹999.'],
                        ['question' => 'How can I return an item?', 'answer' => 'You can initiate a return from your account dashboard within 30 days.'],
                        ['question' => 'Is there a warranty?', 'answer' => 'Most equipment comes with a standard 2-year warranty.']
                    ]
                ],
            ],
            [
                'title' => 'Join the Club',
                'type' => 'newsletter',
                'sort_order' => 10,
                'settings' => [
                    'title' => 'NEVER MISS A BEAT',
                    'subtitle' => 'Get the latest sports gear updates and exclusive community offers.',
                    'placeholder' => 'Enter your email address',
                    'button_text' => 'Join Now'
                ],
            ],
        ];

        foreach ($sections as $section) {
            HomeSection::create($section);
        }
    }
}
