<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class ProductController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','store']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products as p')->leftJoin('product_categories as c', 'p.category_id', '=', 'c.id')->select('p.id', 'p.product_name', 'p.hsn', 'p.tax_percentage', 'c.category_name')->get();
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $med_types = DB::table('medicine_types')->get();
        $categories = DB::table('product_categories')->get();
        $taxes = DB::table('tax')->get();
        return view('product.create', compact('categories', 'taxes', 'med_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'medicine_type' => 'required',
            'category_id' => 'required',
            'tax_percentage' => 'required'
        ]);
        $input = $request->all();
        $input['available_for_consultation'] = ($request->has('available_for_consultation')) ? 1 : 0;
        $product = Product::create($input);
        return redirect()->route('product.index')->with('success','Product created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = DB::table('product_categories')->get();
        $taxes = DB::table('tax')->get();
        $med_types = DB::table('medicine_types')->get();
        return view('product.edit', compact('product','categories', 'taxes', 'med_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'medicine_type' => 'required',
            'category_id' => 'required',
            'tax_percentage' => 'required'
        ]);
        $input = $request->all();
        $input['available_for_consultation'] = ($request->has('available_for_consultation')) ? 1 : 0;
        $product = Product::find($id);
        $product->update($input);
        
        return redirect()->route('product.index')->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->route('product.index')
                        ->with('success','Product deleted successfully');
    }
}
