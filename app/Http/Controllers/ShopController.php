<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Support\Storefront;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $hub = Storefront::hub();

        /*
         * Catalogue is read as a single query with full eager loading —
         * no N+1 on brand, category or hub inventory. Model collections
         * are deliberately NOT stored in the cache: they must serialize
         * safely across PHP versions and cache drivers.
         */
        $products = Product::query()
            ->active()
            ->with(['brand', 'category', 'hubs'])
            ->orderBy('sort')
            ->get();

        $featured = $products->filter(fn ($p) => $p->is_featured)->values();
        $editorial = $products->slice(3, 3)->values();
        $availability = Storefront::availabilityMap($products);

        return view('shop.index', [
            'mode' => Storefront::mode(),
            'hub' => $hub,
            'hubOptions' => Storefront::hubOptions(),
            'products' => $products,
            'availability' => $availability,
            'featured' => $featured,
            'editorial' => $editorial,
            'categories' => $this->categories(),
        ]);
    }

    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::query()
            ->active()
            ->where('category_id', $category->id)
            ->with(['brand', 'category', 'hubs'])
            ->orderBy('sort')
            ->get();

        $availability = Storefront::availabilityMap($products);

        return view('shop.category', [
            'mode' => Storefront::mode(),
            'hub' => Storefront::hub(),
            'hubOptions' => Storefront::hubOptions(),
            'category' => $category,
            'products' => $products,
            'availability' => $availability,
            'categories' => $this->categories(),
        ]);
    }

    public function product(Request $request, string $slug)
    {
        $product = Product::query()
            ->active()
            ->with(['brand', 'category', 'hubs'])
            ->where('slug', $slug)
            ->firstOrFail();

        $availability = $product->availabilityAt(Storefront::hub());
        $hubOptions = Storefront::hubOptions();

        $hubStock = $product->hubs
            ->filter(fn ($h) => $h->slug !== 'all')
            ->mapWithKeys(fn ($h) => [
                $h->short_code => $h->pivot->stock > 0
                    ? ($h->pivot->stock <= 5 ? 'LOW' : 'IN STOCK')
                    : 'OUT',
            ]);

        return view('shop.product', [
            'mode' => Storefront::mode(),
            'hub' => Storefront::hub(),
            'hubOptions' => $hubOptions,
            'product' => $product,
            'availability' => $availability,
            'hubStock' => $hubStock,
            'ladder' => $product->ladder(),
            'categories' => $this->categories(),
        ]);
    }

    public function brands()
    {
        $brands = Brand::query()
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('shop.brands', [
            'mode' => Storefront::mode(),
            'hub' => Storefront::hub(),
            'hubOptions' => Storefront::hubOptions(),
            'brands' => $brands,
            'categories' => $this->categories(),
        ]);
    }

    protected function categories()
    {
        return Category::orderBy('sort')->get();
    }
}
