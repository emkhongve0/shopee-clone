<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // 1. Xem danh sách sản phẩm đã thích
    public function index() {
        $products = Auth::user()->wishlists()->latest()->get();
        return view('wishlist.index', compact('products'));
    }

    // 2. Thêm hoặc Xóa khỏi yêu thích (Toggle)
    public function toggle(Request $request) {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $user = Auth::user();
        $productId = $request->product_id;

        // syncWithoutDetaching sẽ thêm nếu chưa có, hoặc dùng toggle
        $status = $user->wishlists()->toggle($productId);

        $isLiked = count($status['attached']) > 0;

        return response()->json([
            'status' => 'success',
            'isLiked' => $isLiked,
            'message' => $isLiked ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích'
        ]);

    }
    public function clear() {
    Auth::user()->wishlists()->detach(); // Xóa sạch mối quan hệ trong bảng trung gian
    return response()->json([
        'status' => 'success',
        'message' => 'Đã làm trống danh sách yêu thích'
    ]);
}
}
