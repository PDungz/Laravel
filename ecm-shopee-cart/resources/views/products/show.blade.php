@extends('layouts.app')

@section('content')
    <div>
        <div style="display: flex; align-items: center;">
            <h1>Hiển thị</h1>
            <div class="margin-right"></div>
            <a class="btn btn-danger" href="{{ route('products.index') }}">Quay lại</a>
        </div>

        <div class="margin-bottom">
            <label for="name">Sản phẩm:</label>
            <input class="input-field-full" type="text" id="name" name="name" value="{{ $product->name }}" disabled>
        </div>
        <div class="margin-bottom">
            <label for="image">Ảnh:</label>
            <div class="margin-bottom"></div>
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="Ảnh {{ $product->name }}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
            @else
                <span>No Image</span>
            @endif
        </div>
        <div class="margin-bottom">
            <label for="description">Mô tả:</label>
            <textarea class="input-field-full" id="description" name="description" disabled>{{ $product->description }}</textarea>
        </div>
        <div class="margin-bottom">
            <label for="quantity">Số lượng trong kho:</label>
            <input class="input-field-full" type="number" id="quantity_stock" name="quantity_stock" value="{{ $product->quantity }}" disabled>
        </div>
        <div class="margin-bottom">
            <label for="price">Giá tiền:</label>
            <input class="input-field-full" type="text" id="price" name="price" value="{{ $product->price }}" disabled>
        </div>
        <div class="margin-bottom">
            <label for="">Danh mục:</label>
            <input class="input-field-full" type="text" id="category_id" name="category_id" value="{{ $product->price }}" disabled>
        </div>
        <div class="margin-bottom">
            <label class="label">Thêm vào giỏ hàng:</label>
        </div>
        <form action="{{ route('carts.store') }}" method="POST" id="cartForm" class="margin-bottom">
            @csrf
            <input type="hidden" name="productId" value="{{ $product->id }}">
            <div class="margin-bottom" style="display: flex; align-items: center;">
                <label for="quantity" class="label" style="margin-right: 10px;">Số lượng:</label>
                <div class="input-group" style="max-width: 200px; display: flex; align-items: center;">
                    <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity" style="border: 1px solid #ccc; padding: 5px 10px;">-</button>
                    <input type="number" name="quantity" id="quantity" class="input-field-full text-center" min="1" max="{{ $product->quantity }}" value="1" required style="text-align: center; width: 60px; margin: 0 5px; border: 1px solid #ccc; height: 34px;">
                    <button type="button" class="btn btn-outline-secondary" id="increaseQuantity" style="border: 1px solid #ccc; padding: 5px 10px;">+</button>
                </div>
            </div>            
            <button type="submit" class="btn btn-dark mt-3 mb-5" id="addToCartButton">Thêm vào giỏ hàng</button>
        </form>
    </div>

    <script>
        // Nút tăng số lượng
        document.getElementById('increaseQuantity').addEventListener('click', function() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            var maxValue = parseInt(quantityInput.max);

            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
            toggleAddToCartButton();
        });

        // Nút giảm số lượng
        document.getElementById('decreaseQuantity').addEventListener('click', function() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            var minValue = parseInt(quantityInput.min);

            if (currentValue > minValue) {
                quantityInput.value = currentValue - 1;
            }
            toggleAddToCartButton();
        });

        // Kiểm tra khi người dùng nhập thủ công
        document.getElementById('quantity').addEventListener('input', function() {
            toggleAddToCartButton();
        });

        // Hàm kiểm tra số lượng và kích hoạt nút Thêm vào giỏ hàng
        function toggleAddToCartButton() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            var minValue = parseInt(quantityInput.min);
            var maxValue = parseInt(quantityInput.max);
            var addToCartButton = document.getElementById('addToCartButton');

            if (currentValue < minValue || currentValue > maxValue || isNaN(currentValue)) {
                addToCartButton.disabled = true;
            } else {
                addToCartButton.disabled = false;
            }
        }

        // Gọi hàm kiểm tra khi tải trang
        window.onload = function() {
            toggleAddToCartButton();
        };
    </script>
@endsection
