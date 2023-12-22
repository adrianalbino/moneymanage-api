<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

class CategoryController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $category = Category::all();

    if (isset($category)) {
      return $this->sendResponse($category, "Successfully retrieved data");
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $id)
  {
    $request->validate([
      'description' => 'required|string|unique:categories,description,NULL,id,account_id,' . $id . '|max:20',
      'type' => 'required|in:INCOME,SPENDING'
    ]);

    $category = new Category();
    $category->fill($request->except(['account_id']));
    $category->account_id = $id;

    if ($category->save()) {
      return $this->sendResponse($category, 'Successfully added category');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $category = Category::where('account_id', $id)->get();

    if (isset($category)) {
      return $this->sendResponse($category, "Successfully retrieved data");
    }
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
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
    $category = Category::where('id', $id);
    $category->delete();
    return $this->sendResponse($category, "Successfully deleted category");
  }
}
