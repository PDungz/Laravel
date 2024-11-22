@extends('layouts.app')

@section('content')
    <div class="margin-bottom">
        <div class="center">
            <h1>Giỏ hàng</h1>
        </div>
        <div class="center">
            @if (session('success'))
                <div class="text-success font-bold text-medium">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="text-danger font-bold text-medium">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div>
            <form action="{{ route('carts.index') }}" method="get">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}" class="input-field margin-right">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>
    
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Mô tả</th>
                    <th>Số lượng</th>
                    <th>Giá tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carts as $cart)
                    <tr>
                        <td>{{ $cart->id }}</td>
                        <td>{{ $cart->product->name }}</td>
                        <td>
                            @if ($cart->product->image)
                                <img src="{{ asset('storage/'.$cart->product->image) }}" alt="Ảnh {{ $cart->product->name }}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $cart->product->description }}</td>
                        <td style="text-align: center">
                            <form action="{{ route('carts.update', $cart->id) }}" method="POST" id="form-{{ $cart->id }}">
                                @csrf
                                @method('PUT')
                                <div class="margin-bottom" style="display: flex; align-items: center;">
                                    <div class="input-group" style="max-width: 200px; display: flex; align-items: center;">
                                        <button type="button" onclick="changeQuantity({{ $cart->id }}, -1)" class="btn btn-outline-secondary" style="border: 1px solid #ccc; padding: 5px 10px;" {{ $cart->quantity == 1 ? 'disabled' : '' }}>-</button>
                                        <input type="number" id="quantity-{{ $cart->id }}" name="quantity" class="input-field-full text-center" value="{{ $cart->quantity }}" required style="text-align: center; width: 60px; margin: 0 5px; border: 1px solid #ccc; height: 34px;">
                                        <button type="button" onclick="changeQuantity({{ $cart->id }}, 1)" class="btn btn-outline-secondary" style="border: 1px solid #ccc; padding: 5px 10px;">+</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        
                        <td>{{ $cart->product->price * $cart->quantity }}</td>
                        <td>
                            <form action="{{ route('carts.destroy', $cart->id) }}" method="post" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function changeQuantity(cartId, change) {
            // Lấy giá trị hiện tại của số lượng
            var quantityInput = document.getElementById('quantity-' + cartId);
            var currentQuantity = parseInt(quantityInput.value);

            // Nếu giá trị thay đổi hợp lệ
            if (currentQuantity + change >= 1) {
                // Cập nhật lại giá trị của số lượng
                quantityInput.value = currentQuantity + change;

                // Gửi form tự động sau khi thay đổi số lượng
                document.getElementById('form-' + cartId).submit();
            } else {
                alert('Số lượng không thể nhỏ hơn 1');
            }
        }
        function confirmDelete() {
            return confirm("Bạn có muốn xóa không?");
        }
    </script>
@endsection
