<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Car;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // $search = $request->input('search');
    
        // // Tìm kiếm theo tên sản phẩm và các trường liên quan
        // $carts = Cart::with('car') // Eager load sản phẩm
        //     ->when($search, function ($query, $search) {
        //         $query->whereHas('car', function ($carQuery) use ($search) {
        //             $carQuery->where('name', 'like', "%{$search}%") // Tìm kiếm theo tên sản phẩm
        //                 ->orWhere('description', 'like', "%{$search}%") // Tìm kiếm theo mô tả
        //                 ->orWhere('price', 'like', "%{$search}%"); // Tìm kiếm theo giá tiền
        //         });
        //     })
        //     ->get();
        
        $carts = Cart::with('car')->get();
        // Trả kết quả về view
        // return view('carts.index', compact('carts', 'search'));
        return view('carts.index', compact('carts'));
    }

    public function store(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'car_id' => 'required|exists:cars,id',
                'quantity' => 'required|integer|min:1',
            ]);
    
            // Lấy sản phẩm từ cơ sở dữ liệu
            $car = Car::find($validatedData['car_id']);
    
            // Kiểm tra số lượng còn lại trong kho
            if ($car->quantity < $validatedData['quantity']) {
                return redirect()->route('carts.index')->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }
    
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng
            $existingCart = Cart::where('car_id', $validatedData['car_id'])->first();
    
            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $validatedData['quantity'];
    
                // Kiểm tra lại số lượng khi cộng thêm
                if ($car->quantity < $newQuantity) {
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
        } catch (\Exception $e) {
            return redirect()->route('carts.index')->with('error', 'Sản phẩm khong them duoc vao gio hang.' . $e->getMessage());
        }
    }


    public function update(Request $request, Cart $cart)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validatedData);

        return redirect()->route('carts.index')->with('success', 'Số lượng đã được cập nhật.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }


}
