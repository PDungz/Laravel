<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $categories = Category::when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
            })->get();
            return view('categories.index', compact('categories', 'search'));

        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể tìm kiếm danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('categories.create');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể hiển thị form tạo danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name', 
                'description' => 'required|string|max:255|unique:categories,description',
            ]);

            Category::create($request->all());
            
            return redirect()->route('categories.index')->with('success', 'Tạo danh mục thành công.');
        } catch (\Exception $e) {
            return redirect()->route('categories.create')->with('error', 'Không thể hiển thị form tạo danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('categories.show', compact('category'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể hiển thị danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        try {
            return view('categories.update', compact('category'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể hiển thị form sửa danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id, 
                'description' => 'required|string|max:255|unique:categories,description,' . $category->id,
            ]);

            $category->update($request->all());

            return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể cập nhật danh mục. Vui lòng thử lại sau.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không thể xóa danh mục. Vui lòng thử lại sau.');
        }
    }
}
