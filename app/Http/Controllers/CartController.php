<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['items' => [], 'total' => 0]);
        }

        $cartItems = Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        $total = $cartItems->sum(function($item) {
            // Thêm kiểm tra null để tránh lỗi sập trang nếu sản phẩm bị xóa khỏi DB
            return $item->product ? ($item->quantity * $item->product->price) : 0;
        });

        return response()->json([
            'status' => 'success', // Thống nhất dùng status
            'items' => $cartItems,
            'total' => $total
        ]);
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập!',
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $qty = $request->quantity ?? 1;

        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $qty
            ]);
        }

        $newCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'status' => 'success', // Thống nhất dùng status
            'message' => 'Đã thêm vào giỏ hàng!',
            'cart_count' => $newCount
        ]);
    }

    public function remove($id)
{
    $userId = Auth::id();
    // 1. Tìm đúng món hàng của user đó
    $cartItem = Cart::where('user_id', $userId)->where('id', $id)->first();

    if ($cartItem) {
        $cartItem->delete();

        // 2. Tính lại tổng số lượng (cho badge giỏ hàng)
        $newCount = Cart::where('user_id', $userId)->sum('quantity');

        // 3. Tính lại tổng tiền của cả giỏ hàng (giả sử có quan hệ 'product')
        $newTotal = Cart::where('user_id', $userId)->get()->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'status' => 'success',
            'cart_count' => $newCount,
            'total' => $newTotal, // Trả thêm tổng tiền để UI cập nhật ngay
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Không tìm thấy sản phẩm hoặc bạn không có quyền xóa'
    ], 404);
}


// API: Xóa toàn bộ giỏ hàng
public function clear()
{
    Cart::where('user_id', Auth::id())->delete();
    return response()->json(['status' => 'success', 'cart_count' => 0]);
}
}

