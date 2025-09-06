<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Ecommerce Store',
            ],
            [
                'group' => 'general',
                'key' => 'site_description',
                'value' => 'Your one-stop shop for everything you need',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => 'logos/logo.png',
            ],
            [
                'group' => 'general',
                'key' => 'site_favicon',
                'value' => 'logos/favicon.ico',
            ],
            [
                'group' => 'general',
                'key' => 'contact_email',
                'value' => 'contact@ecommercestore.com',
            ],
            [
                'group' => 'general',
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
            ],
            [
                'group' => 'general',
                'key' => 'contact_address',
                'value' => '123 Commerce Street, Business City, BC 12345',
            ],
            [
                'group' => 'general',
                'key' => 'timezone',
                'value' => 'America/New_York',
            ],
            [
                'group' => 'general',
                'key' => 'currency',
                'value' => 'USD',
            ],
            [
                'group' => 'general',
                'key' => 'currency_symbol',
                'value' => '$',
            ],

            // Payment Settings
            [
                'group' => 'payment',
                'key' => 'payment_methods',
                'value' => json_encode(['cod', 'credit_card', 'paypal', 'bank_transfer']),
            ],
            [
                'group' => 'payment',
                'key' => 'cod_enabled',
                'value' => 'true',
            ],
            [
                'group' => 'payment',
                'key' => 'paypal_enabled',
                'value' => 'true',
            ],
            [
                'group' => 'payment',
                'key' => 'paypal_mode',
                'value' => 'sandbox',
            ],
            [
                'group' => 'payment',
                'key' => 'paypal_client_id',
                'value' => '',
            ],
            [
                'group' => 'payment',
                'key' => 'paypal_secret',
                'value' => '',
            ],
            [
                'group' => 'payment',
                'key' => 'stripe_enabled',
                'value' => 'false',
            ],
            [
                'group' => 'payment',
                'key' => 'stripe_key',
                'value' => '',
            ],
            [
                'group' => 'payment',
                'key' => 'stripe_secret',
                'value' => '',
            ],

            // Shipping Settings
            [
                'group' => 'shipping',
                'key' => 'shipping_methods',
                'value' => json_encode(['standard', 'express', 'overnight']),
            ],
            [
                'group' => 'shipping',
                'key' => 'free_shipping_enabled',
                'value' => 'true',
            ],
            [
                'group' => 'shipping',
                'key' => 'free_shipping_min_amount',
                'value' => '100',
            ],
            [
                'group' => 'shipping',
                'key' => 'flat_rate_enabled',
                'value' => 'true',
            ],
            [
                'group' => 'shipping',
                'key' => 'flat_rate_cost',
                'value' => '9.99',
            ],
            [
                'group' => 'shipping',
                'key' => 'express_shipping_cost',
                'value' => '19.99',
            ],
            [
                'group' => 'shipping',
                'key' => 'overnight_shipping_cost',
                'value' => '29.99',
            ],

            // Email Settings
            [
                'group' => 'email',
                'key' => 'mail_from_address',
                'value' => 'noreply@ecommercestore.com',
            ],
            [
                'group' => 'email',
                'key' => 'mail_from_name',
                'value' => 'Ecommerce Store',
            ],
            [
                'group' => 'email',
                'key' => 'order_notification_email',
                'value' => 'orders@ecommercestore.com',
            ],
            [
                'group' => 'email',
                'key' => 'send_order_confirmation',
                'value' => 'true',
            ],
            [
                'group' => 'email',
                'key' => 'send_order_shipped',
                'value' => 'true',
            ],
            [
                'group' => 'email',
                'key' => 'send_welcome_email',
                'value' => 'true',
            ],

            // Appearance Settings
            [
                'group' => 'appearance',
                'key' => 'theme',
                'value' => 'default',
            ],
            [
                'group' => 'appearance',
                'key' => 'dark_mode_enabled',
                'value' => 'false',
            ],
            [
                'group' => 'appearance',
                'key' => 'primary_color',
                'value' => '#3B82F6',
            ],
            [
                'group' => 'appearance',
                'key' => 'secondary_color',
                'value' => '#10B981',
            ],
            [
                'group' => 'appearance',
                'key' => 'items_per_page',
                'value' => '20',
            ],
            [
                'group' => 'appearance',
                'key' => 'show_breadcrumbs',
                'value' => 'true',
            ],
            [
                'group' => 'appearance',
                'key' => 'sidebar_collapsed',
                'value' => 'false',
            ],

            // Business Settings
            [
                'group' => 'business',
                'key' => 'tax_enabled',
                'value' => 'true',
            ],
            [
                'group' => 'business',
                'key' => 'tax_rate',
                'value' => '10',
            ],
            [
                'group' => 'business',
                'key' => 'invoice_prefix',
                'value' => 'INV-',
            ],
            [
                'group' => 'business',
                'key' => 'order_prefix',
                'value' => 'ORD-',
            ],
            [
                'group' => 'business',
                'key' => 'enable_reviews',
                'value' => 'true',
            ],
            [
                'group' => 'business',
                'key' => 'review_moderation',
                'value' => 'true',
            ],
            [
                'group' => 'business',
                'key' => 'enable_wishlist',
                'value' => 'true',
            ],
            [
                'group' => 'business',
                'key' => 'enable_compare',
                'value' => 'true',
            ],
            [
                'group' => 'business',
                'key' => 'maintenance_mode',
                'value' => 'false',
            ],

            // Social Media Settings
            [
                'group' => 'social',
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/ecommercestore',
            ],
            [
                'group' => 'social',
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/ecommercestore',
            ],
            [
                'group' => 'social',
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/ecommercestore',
            ],
            [
                'group' => 'social',
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/ecommercestore',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                [
                    'group' => $setting['group'],
                    'key' => $setting['key']
                ],
                [
                    'value' => $setting['value']
                ]
            );
        }
    }
}
