<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            // ─────────────────────────────────────────────
            // Decathlon Own Brands (floor 1)
            // ─────────────────────────────────────────────
            ['name' => 'Quechua',    'slug' => 'quechua',    'description' => 'Decathlon\'s brand for hiking, trekking and mountain sports',     'website' => 'https://www.quechua.com',    'sort_order' => 1,  'status' => true],
            ['name' => 'Kipsta',     'slug' => 'kipsta',     'description' => 'Decathlon\'s brand for team sports equipment',                    'website' => 'https://www.kipsta.com',     'sort_order' => 2,  'status' => true],
            ['name' => 'Kalenji',    'slug' => 'kalenji',    'description' => 'Decathlon\'s running brand for shoes and apparel',                'website' => 'https://www.kalenji.com',    'sort_order' => 3,  'status' => true],
            ['name' => 'Domyos',     'slug' => 'domyos',     'description' => 'Decathlon\'s fitness and gym brand',                             'website' => 'https://www.domyos.com',     'sort_order' => 4,  'status' => true],
            ['name' => 'Artengo',    'slug' => 'artengo',    'description' => 'Decathlon\'s racket sports brand',                               'website' => 'https://www.artengo.com',    'sort_order' => 5,  'status' => true],
            ['name' => 'Nabaiji',    'slug' => 'nabaiji',    'description' => 'Decathlon\'s swimming and water sports brand',                   'website' => 'https://www.nabaiji.com',    'sort_order' => 6,  'status' => true],
            ['name' => 'B\'Twin',    'slug' => 'btwin',      'description' => 'Decathlon\'s cycling brand for bikes and accessories',           'website' => 'https://www.btwin.com',      'sort_order' => 7,  'status' => true],
            ['name' => 'Wedze',      'slug' => 'wedze',      'description' => 'Decathlon\'s winter sports brand for skiing and snowboarding',   'website' => 'https://www.wedze.com',      'sort_order' => 8,  'status' => true],
            ['name' => 'Newfeel',    'slug' => 'newfeel',    'description' => 'Decathlon\'s walking and urban fitness brand',                   'website' => 'https://www.newfeel.com',    'sort_order' => 9,  'status' => true],
            ['name' => 'Tribord',    'slug' => 'tribord',    'description' => 'Decathlon\'s sailing and water sports brand',                    'website' => 'https://www.tribord.com',    'sort_order' => 10, 'status' => true],
            ['name' => 'Solognac',   'slug' => 'solognac',   'description' => 'Decathlon\'s hunting and country sports brand',                  'website' => null,                         'sort_order' => 11, 'status' => true],
            ['name' => 'Forclaz',    'slug' => 'forclaz',    'description' => 'Decathlon\'s brand for mountain trekking',                       'website' => null,                         'sort_order' => 12, 'status' => true],
            ['name' => 'Geologic',   'slug' => 'geologic',   'description' => 'Decathlon\'s archery and precision sports brand',                'website' => null,                         'sort_order' => 13, 'status' => true],
            ['name' => 'Rockrider',  'slug' => 'rockrider',  'description' => 'Decathlon\'s mountain biking brand',                             'website' => null,                         'sort_order' => 14, 'status' => true],
            ['name' => 'Inesis',     'slug' => 'inesis',     'description' => 'Decathlon\'s golf brand',                                        'website' => null,                         'sort_order' => 15, 'status' => true],

            // ─────────────────────────────────────────────
            // International Brands (floor 2)
            // ─────────────────────────────────────────────
            ['name' => 'Nike',         'slug' => 'nike',         'description' => 'American multinational sportswear corporation',              'website' => 'https://www.nike.com',       'sort_order' => 20, 'status' => true],
            ['name' => 'Adidas',       'slug' => 'adidas',       'description' => 'German multinational corporation for sportswear',           'website' => 'https://www.adidas.com',     'sort_order' => 21, 'status' => true],
            ['name' => 'Puma',         'slug' => 'puma',         'description' => 'German multinational corporation for athletic footwear',    'website' => 'https://www.puma.com',       'sort_order' => 22, 'status' => true],
            ['name' => 'Under Armour', 'slug' => 'under-armour', 'description' => 'American sports equipment company',                         'website' => 'https://www.underarmour.com','sort_order' => 23, 'status' => true],
            ['name' => 'Reebok',       'slug' => 'reebok',       'description' => 'Athletic footwear and apparel brand',                       'website' => 'https://www.reebok.com',     'sort_order' => 24, 'status' => true],
            ['name' => 'New Balance',  'slug' => 'new-balance',  'description' => 'American athletic footwear and apparel brand',              'website' => 'https://www.newbalance.com', 'sort_order' => 25, 'status' => true],
            ['name' => 'Asics',        'slug' => 'asics',        'description' => 'Japanese multinational corporation for running shoes',      'website' => 'https://www.asics.com',      'sort_order' => 26, 'status' => true],
            ['name' => 'Skechers',     'slug' => 'skechers',     'description' => 'American footwear brand for lifestyle and sport',           'website' => 'https://www.skechers.com',   'sort_order' => 27, 'status' => true],
            ['name' => 'Columbia',     'slug' => 'columbia',     'description' => 'Outdoor apparel and equipment brand',                       'website' => 'https://www.columbia.com',   'sort_order' => 28, 'status' => true],
            ['name' => 'The North Face','slug'=> 'the-north-face','description' => 'Outdoor recreation products company',                       'website' => 'https://www.thenorthface.com','sort_order'=> 29, 'status' => true],
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate(
                ['slug' => $brandData['slug']],
                $brandData
            );
        }

        $this->command->info('✅ Brands seeded — ' . Brand::count() . ' total brands (15 Decathlon + 10 International).');
    }
}
