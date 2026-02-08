<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            $products = Product::where('status', 1)->latest()->take(12)->get();

            $flashSaleProducts = Product::where('is_flash_sale', true)
                                        ->where('status', 1)
                                        ->take(4)
                                        ->get();

            return view('home', compact('products', 'categories', 'flashSaleProducts'));
        } catch (\Exception $e) {
            Log::error("Lỗi xảy ra tại trang chủ: " . $e->getMessage());
            return view('home', [
                'products' => [],
                'categories' => [],
                'flashSaleProducts' => [],
                'error' => 'Hệ thống đang bảo trì.'
            ]);
        }
    }

    /**
     * CHI TIẾT SẢN PHẨM (Đã tích hợp Logic Theo dõi sản phẩm đã xem)
     */
    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // --- LOGIC MỚI: Tự động lưu vào danh sách đã xem ---
        $this->trackRecentlyViewed($product->id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)
            ->get();

        return view('product_detail', compact('product', 'relatedProducts'));
    }

    /**
     * HÀM BỔ TRỢ: Xử lý lưu Session sản phẩm đã xem (Tách biệt logic)
     */
    private function trackRecentlyViewed($productId)
    {
        $recentViewed = session()->get('recent_viewed', []);

        // Nếu đã xem rồi thì xóa vị trí cũ để đưa lên đầu mảng (mới nhất)
        if (($key = array_search($productId, $recentViewed)) !== false) {
            unset($recentViewed[$key]);
        }

        // Đưa ID vào đầu mảng
        array_unshift($recentViewed, $productId);

        // Giới hạn tối đa 20 sản phẩm để nhẹ Session
        $recentViewed = array_slice($recentViewed, 0, 20);

        session()->put('recent_viewed', $recentViewed);
    }

    public function category(Request $request, $slug)
    {
        $category = Category::with('children')->where('slug', $slug)->firstOrFail();

        if ($category->parent_id === null) {
            $relatedCategories = Category::where('parent_id', $category->id)->orderBy('name', 'asc')->get();
        } else {
            $relatedCategories = Category::where('parent_id', $category->parent_id)->orderBy('name', 'asc')->get();
        }

        $brands = Brand::orderBy('name', 'asc')->get();

        $categoryIds = [$category->id];
        if ($category->parent_id === null && $category->children->count() > 0) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        $query = Product::whereIn('category_id', $categoryIds)->where('status', 1);

        if ($request->filled('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        switch ($request->sort) {
            case 'newest': $query->latest(); break;
            case 'price_low': $query->orderBy('price', 'asc'); break;
            case 'price_high': $query->orderBy('price', 'desc'); break;
            case 'top_rated': $query->orderBy('rating', 'desc'); break;
            default: $query->latest(); break;
        }

        $products = $query->paginate(16)->withQueryString();

        $suggestedProducts = Product::whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('category_detail', compact(
            'category',
            'relatedCategories',
            'products',
            'brands',
            'suggestedProducts'
        ));
    }

    public function collection(Request $request, $slug)
    {
        $products = Product::where('collection', 'like', '%' . $slug . '%')
                           ->where('status', 1)
                           ->latest()
                           ->get();

        $categories = Category::all();
        return view('home', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $products = Product::where('name', 'like', '%' . $keyword . '%')->paginate(20);
        $categories = Category::all();
        return view('home', compact('products', 'categories'));
    }
}
