<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Constants\ProductStatus;


class ProductService
{
    /**
     * Lấy danh sách sản phẩm (có lọc và phân trang)
     */
    public function getFilteredProducts(array $filters, int $perPage = 12)
{
    $query = Product::with('category')->latest();

    // 1. Tìm kiếm Search
    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    // 2. Lọc theo Danh mục (Nếu chọn từ Tab hoặc Select)
    if (!empty($filters['category_id']) && $filters['category_id'] !== 'all') {
        $query->where('category_id', $filters['category_id']);
    }

    // 3. Khoảng giá (priceRange) - Theo đúng value trong Blade của bạn
    if (!empty($filters['priceRange']) && $filters['priceRange'] !== 'all') {
        switch ($filters['priceRange']) {
            case '0-500000':
                $query->where('price', '<=', 500000);
                break;
            case '500000-2000000':
                $query->whereBetween('price', [500000, 2000000]);
                break;
            case '2000000+':
                $query->where('price', '>', 2000000);
                break;
        }
    }

    // 4. Tình trạng kho (stockStatus)
    if (!empty($filters['stockStatus']) && $filters['stockStatus'] !== 'all') {
        if ($filters['stockStatus'] === 'in-stock') {
            $query->where('stock', '>=', 20);
        } elseif ($filters['stockStatus'] === 'low-stock') {
            $query->whereBetween('stock', [1, 19]);
        } elseif ($filters['stockStatus'] === 'out-of-stock') {
            $query->where('stock', 0);
        }
    }

    // 5. Trạng thái hiển thị (productStatus)
    if (!empty($filters['productStatus']) && $filters['productStatus'] !== 'all') {
        $statusValue = ($filters['productStatus'] === 'active') ? 1 : 0;
        $query->where('status', $statusValue);
    }

    // 6. Đánh giá (rating)
    if (!empty($filters['rating']) && $filters['rating'] !== 'all') {
        $minRating = (float) str_replace('+', '', $filters['rating']);
        $query->where('rating', '>=', $minRating);
    }

    return $query->paginate($perPage)->appends(request()->query());
}

    /**
     * Lấy tất cả danh mục
     */
    public function getAllCategories()
    {
        return Category::select('id', 'name')->orderBy('name')->get();
    }

    /**
     * Xóa sản phẩm đơn lẻ
     */
    public function deleteProduct(Product $product): bool
    {
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }
        return $product->delete();
    }

    /**
     * Xử lý hành động hàng loạt
     */
    public function executeBulkAction(string $action, array $ids, array $data = [])
    {
        if ($action === 'delete') {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                if ($product->image && Storage::exists('public/' . $product->image)) {
                    Storage::delete('public/' . $product->image);
                }
                $product->delete();
            }
            return true;
        }

        if ($action === 'update_status') {
            return Product::whereIn('id', $ids)->update(['status' => (int)$data['value']]);
        }

        if ($action === 'update_category') {
            return Product::whereIn('id', $ids)->update(['category_id' => $data['category_id']]);
        }

        if ($action === 'update_price') {
            $safeIds = implode(',', array_map('intval', $ids));
            $type = $data['price_type'];
            $value = (float) $data['price_value'];

            if ($type === 'percent') {
                $multiplier = 1 + ($value / 100);
                return DB::statement("UPDATE products SET price = price * ? WHERE id IN ($safeIds)", [$multiplier]);
            } else {
                return DB::statement("UPDATE products SET price = GREATEST(0, price + ?) WHERE id IN ($safeIds)", [$value]);
            }
        }

        return false; // Fix lỗi P1075
    }

    /**
     * Lấy toàn bộ dữ liệu cần thiết cho trang Index (Dữ liệu thật cho AlpineJS)
     */
    public function getProductPageData(array $filters = []): array
{
    // 1. Khởi tạo Query với Eager Loading để tránh N+1
    $query = Product::with('category')->latest();

    // --- BẮT ĐẦU LOGIC BỘ LỌC BACKEND ---
    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    if (!empty($filters['priceRange']) && $filters['priceRange'] !== 'all') {
        switch ($filters['priceRange']) {
            case '0-500000': $query->where('price', '<=', 500000); break;
            case '500000-2000000': $query->whereBetween('price', [500000, 2000000]); break;
            case '2000000+': $query->where('price', '>', 2000000); break;
        }
    }

    if (!empty($filters['stockStatus']) && $filters['stockStatus'] !== 'all') {
        switch ($filters['stockStatus']) {
            case 'in-stock': $query->where('stock', '>=', 20); break;
            case 'low-stock': $query->whereBetween('stock', [1, 19]); break;
            case 'out-of-stock': $query->where('stock', 0); break;
        }
    }

    if (!empty($filters['productStatus']) && $filters['productStatus'] !== 'all') {
        // Khớp với logic: active = 1, hidden = 0
        $status = ($filters['productStatus'] === 'active') ? 1 : 0;
        $query->where('status', $status);
    }

    if (!empty($filters['rating']) && $filters['rating'] !== 'all') {
        $rating = (float) str_replace('+', '', $filters['rating']);
        $query->where('rating', '>=', $rating);
    }
    // --- KẾT THÚC LOGIC BỘ LỌC ---

    // Lấy dữ liệu sản phẩm đã lọc
    $productsRaw = $query->get();

    // Lấy tất cả danh mục để làm Tabs và Modal
    $allCategories = Category::orderBy('name')->get();

    // 2. Map dữ liệu sản phẩm (Giữ nguyên logic xử lý ảnh và format cũ)
    $allProducts = $productsRaw->map(function($p) {
        return $this->mapProductForFrontend($p);
    });

    // 3. Tính toán Category Tabs (Giữ nguyên logic cũ)
    $categoryTabs = [];
    foreach ($allCategories as $category) {
        // Đếm số lượng dựa trên danh sách sản phẩm ĐÃ LỌC
        $count = $productsRaw->where('category_id', $category->id)->count();
        $categoryTabs[] = [
            'id'      => Str::slug($category->name),
            'real_id' => $category->id,
            'name'    => $category->name,
            'count'   => $count
        ];
    }

    // Xử lý Tab "Chưa phân loại"
    $uncategorizedCount = $productsRaw->whereNull('category_id')->count();
    if ($uncategorizedCount > 0) {
        $categoryTabs[] = [
            'id'      => 'chua-phan-loai',
            'real_id' => null,
            'name'    => 'Chưa phân loại',
            'count'   => $uncategorizedCount
        ];
    }

    // Thêm tab "Tất cả" lên đầu
    array_unshift($categoryTabs, [
        'id'      => 'all',
        'real_id' => null,
        'name'    => 'Tất cả sản phẩm',
        'count'   => $allProducts->count()
    ]);

    return [
        'allProducts'  => $allProducts,
        'categoryTabs' => $categoryTabs,
        'categories'   => $allCategories
    ];
}
public function createProduct(array $data)
{
    // Logic chuyển đổi (Giữ nguyên phần này bạn đã viết)
    $statusValue = 1;
    if (isset($data['status'])) {
        if (is_string($data['status'])) {
            $statusValue = ($data['status'] === 'active') ? 1 : 0;
        } else {
            $statusValue = (int) $data['status'];
        }
    }

    $slug = Str::slug($data['name']) . '-' . Str::random(5);

    return Product::create([
        'name'          => $data['name'],
        'sku'           => $data['sku'] ?? 'SKU-' . strtoupper(Str::random(8)),
        'slug'          => $slug,
        'price'         => (float) $data['price'],
        'old_price'     => (float) ($data['old_price'] ?? $data['price']),
        'image'         => $data['image'] ?? null,
        'stock'         => (int) $data['stock'],

        // SỬA DÒNG NÀY: Chỉ dùng biến đã chuyển đổi sang số
        'status'        => $statusValue,

        'category_id'   => $data['category_id'],
        'badge'         => $data['badge'] ?? null,
        'sold_ratio'    => 0,
        'is_flash_sale' => 0,
    ]);
}

public function updateProduct(\App\Models\Product $product, array $data)
{
    // --- 1. XỬ LÝ TRẠNG THÁI (Logic cũ của bạn) ---
    $statusValue = $product->status;
    if (isset($data['status'])) {
        if (is_string($data['status'])) {
            $s = strtolower($data['status']);
            $map = ['active' => 1, 'hidden' => 0, 'draft' => 2];
            $statusValue = $map[$s] ?? $statusValue;
        } else {
            $statusValue = (int) $data['status'];
        }
    }

    // --- 2. XỬ LÝ HÌNH ẢNH (Gom về một chỗ) ---
    if (request()->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại trong kho (Storage)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        // Lưu ảnh mới vào thư mục 'products'
        $data['image'] = request()->file('image')->store('products', 'public');
    }

    // --- 3. CẬP NHẬT SLUG (Logic cũ của bạn) ---
    if (isset($data['name']) && $data['name'] !== $product->name) {
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
    }

    // --- 4. THỰC HIỆN CẬP NHẬT MỘT LẦN DUY NHẤT ---
    $product->update([
        'name'          => $data['name'] ?? $product->name,
        'sku'           => $data['sku'] ?? $product->sku,
        'slug'          => $data['slug'] ?? $product->slug,
        'price'         => (float) ($data['price'] ?? $product->price),
        'old_price'     => (float) ($data['old_price'] ?? $product->old_price),
        'stock'         => (int) ($data['stock'] ?? $product->stock),
        'status'        => $statusValue, // Sử dụng biến đã xử lý ở Bước 1
        'category_id'   => $data['category_id'] ?? $product->category_id,
        'badge'         => $data['badge'] ?? $product->badge,
        'image'         => $data['image'] ?? $product->image, // Ưu tiên ảnh mới vừa upload
        'is_flash_sale' => isset($data['is_flash_sale']) ? (bool)$data['is_flash_sale'] : $product->is_flash_sale,
        'description'   => $data['description'] ?? $product->description,
    ]);

    // --- 5. TRẢ VỀ DỮ LIỆU ĐÃ MAP CHO FRONTEND ---
    // Dùng $this-> thay vì $this->productService->
    return $this->mapProductForFrontend($product->load('category'));
}

public function mapProductForFrontend($p)
{
    if (is_array($p)) {
        return $p;
    }
    // Logic xử lý ảnh cũ
    $image = $p->image;

    if (!$image) {
        // Trường hợp database không có ảnh
        $imageUrl = 'https://placehold.co/600x400/1e293b/475569?text=No+Image';
    } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
        // Nếu là link ảnh tuyệt đối (như ảnh demo)
        $imageUrl = $image;
    } else {
        // Nếu là ảnh đã tải lên trong thư mục storage
        $imageUrl = asset('storage/' . $image);
    }

    return [
        'id'            => $p->id,
        'name'          => $p->name,
        'image'         => $imageUrl,
        'category'      => $p->category->name ?? 'Chưa phân loại',
        'category_slug' => isset($p->category->name) ? Str::slug($p->category->name) : 'chua-phan-loai',
        'category_id'   => $p->category_id,
        'price'         => (float) $p->price,
        'old_price'     => (float) ($p->old_price ?? $p->price),
        'stock'         => (int) $p->stock,
        'status' => match ((int)$p->status) {
            1 => 'active',
            2 => 'draft',
            default => 'hidden',
        },
        'rating'        => (float) ($p->rating ?? 5.0),
        'reviews'       => (int) ($p->reviews ?? 0),
        'sku'           => $p->sku,
        'createdAt'     => $p->created_at ? $p->created_at->format('d/m/Y') : now()->format('d/m/Y'),
        'updatedAt'     => $p->updated_at ? $p->updated_at->format('d/m/Y') : now()->format('d/m/Y'),
        'description'   => $p->description,
    ];
}

}
