<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryConTroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'required|string|max:255|unique:categories,description',
            ]);
    
            // Tao moi
            Category::create($request->all());
            return redirect()->route('categories.index')->with('success', 'Them thanh cong');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('categories.create')->with('error', 'Them that bai - ' . $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.update', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            //code...
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'required|string|max:255|unique:categories,description,' . $category->id,
            ]);
    
            // Tao moi
            $category->update($request->all());
            return redirect()->route('categories.index')->with('success', 'Cap nhat thanh cong');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('categories.index')->with('error', 'Cap nhat that bai - ' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Xoa thanh cong');
    }
}
