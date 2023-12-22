<?php

namespace App\Http\Controllers;


use App\Models\Account;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

class AccountController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $account = Account::all();

    if (isset($account)) {
      return $this->sendResponse($account, "Successfully retrieved data");
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
    // validation
    $request->validate([
      'name' => 'required|string|unique:accounts,name,NULL,id,user_id,' . $id . '|max:255',
      'balance' => 'required',
      'currency' => 'required|string|max:255'
    ]);

    //fill
    $account = new Account;
    $account->fill($request->except(['user_id']));
    $account->user_id = $id;


    if ($account->save()) {
      //set user has newly created account to true
      $user = new User;
      $user = User::find($id);
      $user->has_account = true;
      $user->save();
      $categories = [
        ['description' => 'Food', 'type' => 'SPENDING', 'account_id' => $account->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
        ['description' => 'Transport', 'type' => 'SPENDING', 'account_id' => $account->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
        ['description' => 'Bills', 'type' => 'SPENDING', 'account_id' => $account->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]
      ];
      Category::insert($categories);
      return $this->sendResponse($account, "Successfully created an account!");
    } else {
      return $this->sendError("Error in saving data!");
    }
  }

  private function addDefaultCategories()
  {
    $category = new Category;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $account = Account::where('user_id', $id)->get();

    if (isset($account)) {
      return $this->sendResponse($account, "Successfully retrieved data");
    }
  }


  public function showById($id)
  {
    $account = Account::find($id);
    
    if (isset($account)) {
      return $this->sendResponse($account, "Successfully retrieved data");
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

    $request->validate([
      'name' => 'required|string|max:255',
      'balance' => 'required'
    ]);
    
    $account = Account::find($id);
    $account["balance"] = $request['amount'];
    if (!isset($account)) {
      return $this->sendError("No account found!");
    }

    $account->fill($request->except(['user_id']));

    if ($account->save()) {
      return $this->sendResponse($account, "Successfully updated an account!");
    } else {
      return $this->sendError("Error in saving data!");
    }
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
  }
}
