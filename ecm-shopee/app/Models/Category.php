<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * Đây là model đại diện cho bảng "categories" trong cơ sở dữ liệu. Model này được sử dụng
 * để quản lý danh mục sản phẩm hoặc nội dung trong ứng dụng. Nó tận dụng ORM Eloquent 
 * của Laravel để tương tác với cơ sở dữ liệu.
 * 
 * Tính năng:
 * - Hỗ trợ gán hàng loạt (mass assignment) cho các thuộc tính 'name' và 'description'.
 * - Bao gồm trait HasFactory để dễ dàng tạo các instance của model thông qua factory.
 * 
 * Thuộc tính:
 * @property string $name        Tên của danh mục.
 * @property string $description Mô tả ngắn gọn về danh mục.
 */
class Category extends Model
{
    use HasFactory;

    // Định nghĩa các thuộc tính có thể được gán hàng loạt.
    protected $fillable = ['name', 'description'];
}
