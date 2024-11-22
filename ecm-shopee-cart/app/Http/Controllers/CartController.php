<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị danh sách giỏ hàng.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        // Tìm kiếm theo tên sản phẩm và các trường liên quan
        $carts = Cart::with('product') // Eager load sản phẩm
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%") // Tìm kiếm theo tên sản phẩm
                        ->orWhere('description', 'like', "%{$search}%") // Tìm kiếm theo mô tả
                        ->orWhere('price', 'like', "%{$search}%"); // Tìm kiếm theo giá tiền
                });
            })
            ->get();
    
        // Trả kết quả về view
        return view('carts.index', compact('carts', 'search'));
    }
    

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Lấy sản phẩm từ cơ sở dữ liệu
        $product = Product::find($validatedData['productId']);

        // Kiểm tra số lượng còn lại trong kho
        if ($product->quantity < $validatedData['quantity']) {
            return redirect()->route('carts.index')->with('error', 'Số lượng sản phẩm trong kho không đủ.');
        }

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng
        $existingCart = Cart::where('productId', $validatedData['productId'])->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $validatedData['quantity'];

            // Kiểm tra lại số lượng khi cộng thêm
            if ($product->quantity < $newQuantity) {
                return redirect()->route('carts.index')->with('error', 'Số lượng sản phẩm trong kho không đủ để thêm.');
            }

            // Tăng số lượng nếu đã tồn tại
            $existingCart->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            // Tạo mục mới nếu chưa tồn tại
            Cart::create($validatedData);
        }

        // Không cần cập nhật số lượng sản phẩm trong kho ở đây nữa.

        return redirect()->route('carts.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }



    /**
     * Hiển thị chi tiết một mục giỏ hàng.
     */
    public function show(Cart $cart)
    {
        return view('carts.show', compact('cart'));
    }

    /**
     * Cập nhật số lượng mục trong giỏ hàng.
     */
    public function update(Request $request, Cart $cart)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validatedData);

        return redirect()->route('carts.index')->with('success', 'Số lượng đã được cập nhật.');
    }

    /**
     * Xóa mục giỏ hàng.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}
