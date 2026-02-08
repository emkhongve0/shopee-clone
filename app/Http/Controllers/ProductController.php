<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
{
    // Tìm sản phẩm theo id, nếu không thấy sẽ văng lỗi 404 (tránh treo trang)
    $product = \App\Models\Product::findOrFail($id);

    // Truyền biến $product sang trang show.blade.php
    return view('products.show', compact('product'));
}
}
