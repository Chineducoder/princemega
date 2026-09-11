<?php

namespace Tests\Feature;

use App\Models\Hub;
use App\Models\Product;
use App\Support\DeliveryEngine;
use App\Support\WholesaleTiers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\StorefrontSeeder::class);
    }

    /**
     * Carry the session cookie from the previous response onto the next
     * request — the test client does not persist cookies by default.
     */
    protected function carrySession($response): void
    {
        $name = config('session.cookie');
        $cookie = collect($response->headers->getCookies())->first(fn ($c) => $c->getName() === $name);

        if ($cookie) {
            $this->withCredentials();
            $this->withUnencryptedCookies([$name => $cookie->getValue()]);
        }
    }

    public function test_renders_the_editorial_homepage(): void
    {
        $response = $this->get(route('shop.index'));

        $response->assertOk();
        $response->assertSee('BEAUTY.', false);
        $response->assertSee('AT SCALE.', false);
        $response->assertSee('FEATURED PICKS', false);
    }

    public function test_renders_category_product_and_brands_pages(): void
    {
        $product = Product::active()->first();

        $this->get(route('shop.category', 'skincare'))->assertOk();
        $this->get(route('shop.product', $product->slug))->assertOk();
        $this->get(route('shop.brands'))->assertOk();
        $this->get(route('checkout.index'))->assertOk();
    }

    public function test_persists_purchasing_mode_and_hub_selection(): void
    {
        $this->postJson(route('shop.mode'), ['mode' => 'wholesale'])
            ->assertOk()
            ->assertJson(['mode' => 'wholesale']);
        $this->assertEquals('wholesale', session('shop.mode'));

        $this->postJson(route('shop.hub'), ['hub_id' => 2])->assertOk();
        $this->assertEquals(2, session('shop.hub'));
    }

    public function test_adds_a_retail_item_and_returns_a_computed_summary(): void
    {
        $product = Product::active()->first();

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'mode' => 'retail',
        ])->assertOk();

        $response->assertJsonStructure(['summary', 'lines']);
        $this->assertEquals(2, $response->json('summary.count'));
        $this->assertEquals($product->retail_price * 2, $response->json('summary.subtotal'));
    }

    public function test_applies_wholesale_moq_and_tier_pricing_to_cart_lines(): void
    {
        $product = Product::where('slug', 'the-ordinary-niacinamide-10-zinc-1')->first();

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 5, // below MOQ 12 — should be floored
            'mode' => 'wholesale',
        ])->assertOk();

        $this->assertEquals(12, $response->json('summary.count'));
        $this->assertEquals(15900, $response->json('lines.0.unit_price'));
        $this->assertEquals(15900 * 12, $response->json('summary.subtotal'));
    }

    public function test_walks_the_wholesale_ladder_to_the_deepest_tier(): void
    {
        $product = Product::where('slug', 'the-ordinary-niacinamide-10-zinc-1')->first();

        $resolved = WholesaleTiers::resolve($product->wholesale_tiers, $product->retail_price, 30);

        $this->assertEquals(14800, $resolved['price']);
        $this->assertEquals(24, $resolved['min']);
        $this->assertEquals('24+ units', $resolved['label']);
    }

    public function test_quotes_delivery_through_the_engine(): void
    {
        $retail = DeliveryEngine::quote(['mode' => 'retail', 'city' => 'Port Harcourt', 'subtotal' => 10000]);
        $free = DeliveryEngine::quote(['mode' => 'retail', 'city' => 'Port Harcourt', 'subtotal' => 60000]);
        $wholesale = DeliveryEngine::quote(['mode' => 'wholesale']);
        $pickup = DeliveryEngine::quote(['mode' => 'retail', 'hub_id' => 2, 'method' => 'pickup']);

        $this->assertEquals(1500, $retail['fee']);
        $this->assertEquals(0, $free['fee']);
        $this->assertTrue($free['free']);
        $this->assertEquals(0, $wholesale['fee']);
        $this->assertEquals(0, $pickup['fee']);
        $this->assertEquals('SAME DAY', $pickup['eta']);
    }

    public function test_exports_the_compiled_order_to_whatsapp(): void
    {
        $product = Product::where('slug', 'the-ordinary-niacinamide-10-zinc-1')->first();

        $add = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 12,
            'shade' => '330',
            'mode' => 'wholesale',
        ])->assertOk();

        $this->carrySession($add);

        $response = $this->postJson(route('checkout.whatsapp'), ['mode' => 'wholesale'])->assertOk();

        $summary = $response->json('summary');
        $link = $response->json('redirect_url');

        $this->assertStringContainsString('PRINCE MEGA AGENCY — ORDER REQUEST', $summary);
        $this->assertStringContainsString('Order Type: WHOLESALE', $summary);
        $this->assertStringContainsString('Shade: 330', $summary);
        $this->assertStringContainsString('Unit: ₦15,900', $summary);
        $this->assertStringContainsString('TOTAL: ₦190,800', $summary);
        $this->assertStringStartsWith('https://wa.me/', $link);
        $this->assertStringStartsWith('PMA-', $response->json('reference'));
    }

    public function test_rejects_export_when_the_cart_is_empty(): void
    {
        $this->postJson(route('checkout.whatsapp'))->assertStatus(422);
    }

    public function test_updates_and_removes_cart_lines(): void
    {
        $product = Product::active()->first();

        $added = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'mode' => 'retail',
        ])->assertOk();

        $this->carrySession($added);

        $itemId = $added->json('lines.0.id');

        $updated = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
            'mode' => 'retail',
        ])->assertOk();

        $this->assertEquals(3, $updated->json('summary.count'));

        $this->deleteJson("/cart/{$itemId}")->assertOk();
        $this->assertEquals(0, $this->getJson(route('cart.summary'))->json('count'));
    }

    public function test_seeds_with_uneven_hub_inventory_for_availability_states(): void
    {
        $hub = Hub::where('slug', 'mile-1')->first();
        $product = Product::where('slug', 'the-ordinary-niacinamide-10-zinc-1')->first();

        $this->assertGreaterThan(0, $product->stockAt($hub));
        $this->assertGreaterThan(0, $product->stockAt(null));
    }
}
