<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Settings\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $searchTerm = trim((string) $request->query('search', ''));
        $selectedCategory = trim((string) $request->query('category', ''));
        $selectedSort = trim((string) $request->query('sort', 'featured'));

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $productsQuery = Product::query()
            ->with([
                'category',
                'images' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order'),
            ])
            ->where('is_active', true)
            ->whereHas('category', fn (Builder $query) => $query->where('is_active', true));

        if ($searchTerm !== '') {
            $productsQuery->where(function (Builder $query) use ($searchTerm): void {
                $query
                    ->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%')
                    ->orWhere('slug', 'like', '%'.$searchTerm.'%');
            });
        }

        if ($selectedCategory !== '') {
            $productsQuery->whereHas('category', fn (Builder $query) => $query->where('slug', $selectedCategory));
        }

        $productsQuery = $this->applySort($productsQuery, $selectedSort);

        $products = $productsQuery->paginate(12)->withQueryString();

        $products->getCollection()->transform(fn (Product $product): Product => $this->enrichProductCard($product));

        return view('web.products', [
            'products' => $products,
            'categories' => $categories,
            'searchTerm' => $searchTerm,
            'selectedCategory' => $selectedCategory,
            'selectedSort' => $selectedSort,
        ]);
    }

    public function show(Product $product, SiteSetting $siteSetting): View
    {
        $product->load([
            'category',
            'images' => fn ($query) => $query
                ->where('is_active', true)
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        abort_if(! $product->is_active, 404);
        abort_if(! $product->category || ! $product->category->is_active, 404);

        $galleryImages = $product->images
            ->map(function ($image) use ($product): array {
                return [
                    'url' => $this->resolveMediaUrl($image->image_path),
                    'alt' => $image->alt_text ?: $product->name,
                ];
            })
            ->filter(fn (array $image): bool => filled($image['url']))
            ->values();

        $relatedProducts = Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with([
                'category',
                'images' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(fn (Product $relatedProduct): Product => $this->enrichProductCard($relatedProduct));

        $whatsappUrl = $this->buildWhatsAppUrl($siteSetting->whatsapp_number, $product->name);

        return view('web.product-detail', [
            'product' => $this->enrichProductCard($product),
            'galleryImages' => $galleryImages,
            'relatedProducts' => $relatedProducts,
            'whatsappUrl' => $whatsappUrl,
        ]);
    }

    private function applySort(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query
                ->orderByRaw($this->priceSqlExpression().' asc')
                ->orderBy('name'),
            'price_desc' => $query
                ->orderByRaw($this->priceSqlExpression().' desc')
                ->orderBy('name'),
            'newest' => $query
                ->latest('created_at'),
            default => $query
                ->orderByDesc('is_featured')
                ->orderBy('name'),
        };
    }

    private function priceSqlExpression(): string
    {
        return "cast(replace(replace(replace(replace(lower(display_price), 'rp', ''), '.', ''), ',', ''), ' ', '') as unsigned)";
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    private function enrichProductCard(Product $product): Product
    {
        $primaryImage = $product->images->first();

        $product->setAttribute('image_url', $this->resolveMediaUrl($primaryImage?->image_path));
        $product->setAttribute('image_alt', $primaryImage?->alt_text ?: $product->name);

        return $product;
    }

    private function buildWhatsAppUrl(?string $whatsappNumber, string $productName): ?string
    {
        if ($whatsappNumber === null || trim($whatsappNumber) === '') {
            return null;
        }

        return 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode('Halo, saya ingin pesan '.$productName.'.');
    }
}
