<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Hub;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StorefrontSeeder extends Seeder
{
    public function run(): void
    {
        /* ---------------------------------------------------------- */
        /* Physical hubs                                               */
        /* ---------------------------------------------------------- */
        $hubs = collect([
            ['name' => 'All Physical Hubs', 'slug' => 'all', 'short_code' => 'ALL', 'address' => 'Network-wide availability', 'area' => 'Port Harcourt', 'is_default' => true, 'sort' => 0],
            ['name' => 'Rumuola Head Office', 'slug' => 'rumuola', 'short_code' => 'RUMUOLA', 'address' => 'Rumuola Road, Port Harcourt', 'area' => 'GRA Phase II', 'is_default' => false, 'sort' => 1],
            ['name' => 'Mile 1 Education Bus-Stop Hub', 'slug' => 'mile-1', 'short_code' => 'MILE 1', 'address' => 'Education Bus-Stop, Diobu', 'area' => 'Mile 1 Diobu', 'is_default' => false, 'sort' => 2],
            ['name' => 'Woji YKC Plaza Node', 'slug' => 'woji', 'short_code' => 'WOJI', 'address' => 'YKC Plaza, Woji Road', 'area' => 'Woji / GRA Ext.', 'is_default' => false, 'sort' => 3],
            ['name' => 'Peter Odili Market Square Branch', 'slug' => 'peter-odili', 'short_code' => 'PETER ODILI', 'address' => 'Market Square, Peter Odili Road', 'area' => 'Trans-Amadi', 'is_default' => false, 'sort' => 4],
        ])->mapWithKeys(function (array $hub) {
            $model = Hub::updateOrCreate(['slug' => $hub['slug']], $hub);

            return [$hub['slug'] => $model];
        });

        /* ---------------------------------------------------------- */
        /* Categories                                                  */
        /* ---------------------------------------------------------- */
        $categories = collect([
            ['name' => 'Skincare', 'slug' => 'skincare', 'nav_label' => 'SKINCARE', 'sort' => 1],
            ['name' => 'Makeup', 'slug' => 'makeup', 'nav_label' => 'MAKEUP', 'sort' => 2],
            ['name' => 'Fragrance', 'slug' => 'fragrance', 'nav_label' => 'FRAGRANCE', 'sort' => 3],
            ['name' => 'Hair', 'slug' => 'hair', 'nav_label' => 'HAIR', 'sort' => 4],
            ['name' => 'Body', 'slug' => 'body', 'nav_label' => 'BODY', 'sort' => 5],
            ['name' => 'Tools', 'slug' => 'tools', 'nav_label' => 'TOOLS', 'sort' => 6],
        ])->mapWithKeys(function (array $cat) {
            $model = Category::updateOrCreate(['slug' => $cat['slug']], $cat);

            return [$cat['slug'] => $model];
        });

        /* ---------------------------------------------------------- */
        /* Brands                                                      */
        /* ---------------------------------------------------------- */
        $brands = collect([
            'The Ordinary', 'CeraVe', 'Nivea', 'Garnier', 'Maybelline',
            'L’Oréal Paris', 'Shea Moisture', 'Cantu', 'Davidoff',
            'Bath & Body Works',
        ])->mapWithKeys(fn (string $name) => [
            $name => Brand::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name],
            ),
        ]);

        /* ---------------------------------------------------------- */
        /* Products                                                    */
        /* ---------------------------------------------------------- */
        $img = fn (string $id, int $w = 900, int $h = 1125) => [
            'src' => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w={$w}&h={$h}&q=80",
            'w' => $w, 'h' => $h,
        ];

        $products = [
            [
                'brand' => 'The Ordinary', 'category' => 'skincare',
                'name' => 'The Ordinary Niacinamide 10% + Zinc 1%',
                'slug' => 'the-ordinary-niacinamide-10-zinc-1',
                'subtitle' => 'Blemish control serum • 30ml',
                'micro_label' => 'CLINICAL SKINCARE',
                'description' => 'A water-based serum that visibly regulates sebum and refines the look of pores. Universal daily wear for oily and combination skin in tropical humidity.',
                'retail_price' => 24500,
                'wholesale_tiers' => [12 => 15900, 24 => 14800],
                'moq' => 12,
                'images' => [$img('photo-1620916566398-39f1143ab7be'), $img('photo-1611930022073-b7a4ba5fcccd')],
                'weight_kg' => 0.2,
                'featured' => true,
            ],
            [
                'brand' => 'The Ordinary', 'category' => 'skincare',
                'name' => 'The Ordinary Glycolic Acid 7% Exfoliating Toner',
                'slug' => 'the-ordinary-glycolic-acid-7-toner',
                'subtitle' => 'Resurfacing toner • 240ml',
                'micro_label' => 'CLINICAL SKINCARE',
                'description' => 'A daily exfoliating solution that smooths texture and evens tone. Formulated with Tasmanian pepperberry to reduce irritation.',
                'retail_price' => 22800,
                'wholesale_tiers' => [12 => 14500, 24 => 13200],
                'moq' => 12,
                'images' => [$img('photo-1608248543803-ba4f8c70ae0b')],
            ],
            [
                'brand' => 'CeraVe', 'category' => 'skincare',
                'name' => 'CeraVe Hydrating Facial Cleanser',
                'slug' => 'cerave-hydrating-facial-cleanser',
                'subtitle' => 'Barrier-support cleanser • 473ml',
                'micro_label' => 'PHARMACY SKINCARE',
                'description' => 'Ceramides, hyaluronic acid and glycerin in a non-foaming wash that removes buildup without stripping the barrier.',
                'retail_price' => 26500,
                'wholesale_tiers' => [12 => 17200, 24 => 15900],
                'moq' => 12,
                'images' => [$img('photo-1556228720-195a672e8a03')],
            ],
            [
                'brand' => 'CeraVe', 'category' => 'body',
                'name' => 'CeraVe SA Smoothing Cream',
                'slug' => 'cerave-sa-smoothing-cream',
                'subtitle' => 'Salicylic acid body cream • 340g',
                'micro_label' => 'BODY CLINICAL',
                'description' => 'Salicylic acid and urea combine to soften rough, bumpy skin on arms, legs and heels.',
                'retail_price' => 31000,
                'wholesale_tiers' => [12 => 19800, 24 => 18500],
                'moq' => 12,
                'images' => [$img('photo-1556228720-195a672e8a03')],
            ],
            [
                'brand' => 'Nivea', 'category' => 'body',
                'name' => 'Nivea Shea Smooth Body Milk',
                'slug' => 'nivea-shea-smooth-body-milk',
                'subtitle' => '48h moisture • 400ml',
                'micro_label' => 'BODY ESSENTIALS',
                'description' => 'Deep moisture milk with shea butter and vitamin E for dry skin. Fast absorbing, non-greasy.',
                'retail_price' => 12500,
                'wholesale_tiers' => [12 => 8200, 24 => 7500],
                'moq' => 12,
                'images' => [$img('photo-1631729371254-42c2892f0e6e')],
            ],
            [
                'brand' => 'Garnier', 'category' => 'skincare',
                'name' => 'Garnier Micellar Cleansing Water',
                'slug' => 'garnier-micellar-cleansing-water',
                'subtitle' => 'Even sensitive skin • 700ml',
                'micro_label' => 'CLEANSING',
                'description' => 'Micelles lift makeup and impurities without rinsing. The drawer-staple first cleanse.',
                'retail_price' => 9800,
                'wholesale_tiers' => [12 => 6900, 24 => 6200],
                'moq' => 12,
                'images' => [$img('photo-1571781926291-c477ebfd024b')],
            ],
            [
                'brand' => 'Maybelline', 'category' => 'makeup',
                'name' => 'Maybelline Fit Me Matte + Poreless',
                'slug' => 'maybelline-fit-me-matte-poreless',
                'subtitle' => 'Liquid foundation • 30ml',
                'micro_label' => 'COMPLEXION',
                'description' => 'The shade-true matte foundation engineered for oily skin. Blurs pores, wears through the humid afternoon.',
                'retail_price' => 18500,
                'wholesale_tiers' => [12 => 12800, 24 => 11800],
                'moq' => 12,
                'shades' => [
                    ['name' => '110', 'value' => '110', 'type' => 'num'],
                    ['name' => '220', 'value' => '220', 'type' => 'num'],
                    ['name' => '310', 'value' => '310', 'type' => 'num'],
                    ['name' => '330', 'value' => '330', 'type' => 'num'],
                    ['name' => '470', 'value' => '470', 'type' => 'num'],
                ],
                'images' => [$img('photo-1596462502278-27bfdc403348'), $img('photo-1512496015851-a90fb38ba796')],
                'featured' => true,
            ],
            [
                'brand' => 'Maybelline', 'category' => 'makeup',
                'name' => 'Maybelline Sky High Washable Mascara',
                'slug' => 'maybelline-sky-high-mascara',
                'subtitle' => 'Lash lift effect • Blackest Black',
                'micro_label' => 'EYES',
                'description' => 'Length and volume with a flex-tower brush. Washable, flake-free, humidity-tested.',
                'retail_price' => 14200,
                'wholesale_tiers' => [12 => 9800, 24 => 8900],
                'moq' => 12,
                'images' => [$img('photo-1583241800698-e8ab01c85918')],
            ],
            [
                'brand' => 'L’Oréal Paris', 'category' => 'makeup',
                'name' => 'L’Oréal Infallible 24H Matte Lipstick',
                'slug' => 'loreal-infallible-matte-lipstick',
                'subtitle' => 'Transfer-proof colour • 5ml',
                'micro_label' => 'LIPS',
                'description' => 'Full-coverage liquid lipstick that survives meetings, transit and harmattan air.',
                'retail_price' => 16500,
                'wholesale_tiers' => [12 => 11500, 24 => 10500],
                'moq' => 12,
                'shades' => [
                    ['name' => 'Nude Estime', 'value' => 'Nude Estime', 'type' => 'color', 'color' => '#b4695a'],
                    ['name' => 'Fiancée', 'value' => 'Fiancee', 'type' => 'color', 'color' => '#a84a4a'],
                    ['name' => 'Rioja', 'value' => 'Rioja', 'type' => 'color', 'color' => '#7c2f3a'],
                ],
                'images' => [$img('photo-1631214540242-3cd8c4b0b3b8')],
            ],
            [
                'brand' => 'Shea Moisture', 'category' => 'hair',
                'name' => 'SheaMoisture Manuka Honey & Mafura Oil Shampoo',
                'slug' => 'sheamoisture-manuka-honey-shampoo',
                'subtitle' => 'Intense hydration • 384ml',
                'micro_label' => 'HAIR CLEANSING',
                'description' => 'Sulphate-free cleanse for dry, textured hair. Manuka honey and mafura oil restore softness after braids and weaves.',
                'retail_price' => 21500,
                'wholesale_tiers' => [12 => 14900, 24 => 13800],
                'moq' => 12,
                'images' => [$img('photo-1631730359585-38a4935cbec4')],
            ],
            [
                'brand' => 'Cantu', 'category' => 'hair',
                'name' => 'Cantu Shea Butter Leave-In Conditioning Repair Cream',
                'slug' => 'cantu-leave-in-conditioning-repair-cream',
                'subtitle' => 'Deep repair • 453g',
                'micro_label' => 'HAIR TREATMENT',
                'description' => 'The cult repair cream for relaxed, natural and transitioning hair. Pure shea butter, no mineral oil.',
                'retail_price' => 13800,
                'wholesale_tiers' => [12 => 9400, 24 => 8600],
                'moq' => 12,
                'images' => [$img('photo-1608248543803-ba4f8c70ae0b')],
                'featured' => true,
            ],
            [
                'brand' => 'Davidoff', 'category' => 'fragrance',
                'name' => 'Davidoff Cool Water Eau de Toilette',
                'slug' => 'davidoff-cool-water-edt',
                'subtitle' => 'Marine freshness • 125ml',
                'micro_label' => 'FRAGRANCE',
                'description' => 'The definitive fresh scent — mint, lavender and sandalwood. A permanent line in the agency’s fragrance wall.',
                'retail_price' => 42000,
                'wholesale_tiers' => [6 => 31000, 12 => 28500],
                'moq' => 6,
                'pack_note' => 'CASE OF 6',
                'images' => [$img('photo-1541643600914-78b084683601')],
            ],
            [
                'brand' => 'Bath & Body Works', 'category' => 'fragrance',
                'name' => 'Bath & Body Works Fine Fragrance Mist',
                'slug' => 'bath-body-works-fragrance-mist',
                'subtitle' => 'Into the Night • 236ml',
                'micro_label' => 'BODY MIST',
                'description' => 'Raspberry, amber and night-blooming jasmine in the signature ultra-fine mist.',
                'retail_price' => 27500,
                'wholesale_tiers' => [12 => 19500, 24 => 18000],
                'moq' => 12,
                'shades' => [
                    ['name' => 'Into the Night', 'value' => 'Into the Night', 'type' => 'color', 'color' => '#2b1f33'],
                    ['name' => 'Cherry Snowcone', 'value' => 'Cherry Snowcone', 'type' => 'color', 'color' => '#d4465c'],
                ],
                'images' => [$img('photo-1594035910387-fea47794261f')],
            ],
            [
                'brand' => 'The Ordinary', 'category' => 'tools',
                'name' => 'Beauty Blender Pro Foundation Sponge',
                'slug' => 'beauty-blender-pro-sponge',
                'subtitle' => 'Edgeless application',
                'micro_label' => 'TOOLS',
                'description' => 'Super-soft, edgeless sponge for streak-free complexion work. Latex free.',
                'retail_price' => 8500,
                'wholesale_tiers' => [12 => 5800, 24 => 5200],
                'moq' => 12,
                'images' => [$img('photo-1596704017254-9b121068fb31')],
            ],
        ];

        foreach ($products as $index => $p) {
            $product = Product::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'brand_id' => $brands[$p['brand']]->id,
                    'category_id' => $categories[$p['category']]->id,
                    'name' => $p['name'],
                    'subtitle' => $p['subtitle'] ?? null,
                    'micro_label' => $p['micro_label'],
                    'description' => $p['description'] ?? null,
                    'retail_price' => $p['retail_price'],
                    'wholesale_tiers' => $p['wholesale_tiers'],
                    'moq' => $p['moq'],
                    'pack_note' => $p['pack_note'] ?? null,
                    'shades' => $p['shades'] ?? null,
                    'images' => $p['images'],
                    'weight_kg' => $p['weight_kg'] ?? 0.4,
                    'volume_m3' => $p['volume_m3'] ?? 0.003,
                    'is_featured' => $p['featured'] ?? false,
                    'is_active' => true,
                    'sort' => $index,
                ],
            );

            /*
             * Inventory ledger: All Hubs reflects network-wide stock;
             * physical hubs carry their own. Deliberately uneven so the
             * hub selector visibly changes availability.
             */
            $network = [24, 68, 140, 90, 52, 12, 300, 45, 76, 110, 62, 18, 84, 40][$index % 14];
            $matrix = [
                'rumuola' => [40, 120, 66, 30, 95, 20, 210, 32, 50, 74, 44, 12, 60, 25],
                'mile-1' => [4, 60, 12, 3, 55, 8, 90, 18, 3, 40, 22, 0, 35, 10],
                'woji' => [22, 3, 34, 18, 70, 15, 140, 3, 28, 56, 30, 4, 48, 16],
                'peter-odili' => [16, 44, 28, 12, 3, 0, 110, 25, 19, 32, 48, 8, 3, 22],
            ];
            foreach ($matrix as $slug => $stocks) {
                \App\Models\HubInventory::updateOrCreate(
                    ['hub_id' => $hubs[$slug]->id, 'product_id' => $product->id],
                    ['stock' => $stocks[$index]],
                );
            }
            \App\Models\HubInventory::updateOrCreate(
                ['hub_id' => $hubs['all']->id, 'product_id' => $product->id],
                ['stock' => $network],
            );
        }
    }
}
