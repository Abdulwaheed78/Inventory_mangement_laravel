<?php

namespace App\Http\Controllers;

use League\Csv\Writer;
use App\Http\Controllers\LogController; // to call tghis controller log function
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Import DB

use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    // Display a list of categories
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.categories.index', compact('categories')); // Return to a view
    }

    // Show the form for creating a new category
    public function create()
    {
        return view('admin.categories.create');
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $cat = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        app(abstract: LogController::class)->insert('insert', 'category', auth()->id(), $cat->id);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    // Display the specified category
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    // Show the form for editing the specified category
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Update the specified category in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id . '|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        app(abstract: LogController::class)->insert('update', 'category', auth()->id(), $id);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        app(abstract: LogController::class)->insert('delete', 'category', auth()->id(), $id);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function stock()
    {
        $data = Category::all();
        return view('admin.stocks.index', compact('data'));
    }

    public function stock_detail(Request $request)
    {
        if ($request->category == 0) {
            // Fetch total product count and total stock count per category
            $categoryTotals = Product::select(
                'category_id',
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(stock_quantity) as total_stock')
            )
                ->groupBy('category_id')
                ->with('category')
                ->get();

            return view('admin.stocks.detail', compact('categoryTotals'));
        } else {
            // Fetch products for the selected category
            $category = $request->category;
            $products = Product::with(['warehouse'])->where('category_id', $category)->get();

            return view('admin.stocks.detail', compact('products'));
        }
    }

    public function export()
    {
        $categories = Product::select(
            'categories.name as category_name',
            DB::raw('COUNT(products.id) as total_products'),
            DB::raw('SUM(products.stock_quantity) as total_stock')
        )
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->groupBy('products.category_id', 'categories.name')
        ->get();

        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'Category', 'Products', 'Quantity']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->category_name, // Corrected this line
                $category->total_products,
                $category->total_stock
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="current_stock.csv"',
        ]);
    }
}
