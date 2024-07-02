<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Categories = Category::orderBy('CategoryID', 'desc')->get();
        return view("categories.index")->with('Categories', $Categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("categories.create");
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
            'CategoryName' => 'required|unique:categories,CategoryName',
        ], [
            'CategoryName.unique' => 'هذا الصنف مسجل',
            'CategoryName.required' => 'يجب ادخال الاسم',
        ]);
        $Category = new Category;
        $Category->CategoryName = $request->input('CategoryName');
        $Category->AddedBy = auth()->user()->id;
        $Category->save();
        return redirect("/categories")->with("success", "تمت اضافة الصنف بنجاح");
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
        $Category = Category::find($id);
        return view("categories.edit")->with('Category', $Category);
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
            'CategoryName' => [
                'required',
                Rule::unique('categories')->ignore($request->CategoryID, 'CategoryID'), // Assuming 'SupplierID' is the name of the input field containing the Supplier's ID
            ],
        ], [
            'CategoryName.unique' => 'هذا الصنف مسجل',
            'CategoryName.required' => 'يجب ادخال الاسم',
        ]);
        $Category = Category::find($id);
        $Category->CategoryName = $request->input('CategoryName');
        $Category->save();
        return redirect("/categories")->with("success", "تمت تعديل الصنف بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CheckCategoryItems = Item::where(["CategoryID" => $id])->get();
        if (count($CheckCategoryItems) > 0)
            return redirect("/categories")->with("error", "الصنف مرتبط بمنتجات ");
        $Category = Category::find($id);
        $Category->delete();
        return redirect("/categories")->with("success", "تمت حذف الصنف بنجاح");
    }
}
