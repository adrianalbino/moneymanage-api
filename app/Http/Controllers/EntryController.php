<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Entry;
use App\Models\Account;

class EntryController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
      'name' => 'required|string|max:255',
      'date' => 'required|string',
      'amount' => 'required|numeric|between:0,10000000000.99',
      'type' => 'required|in:INCOME,SPENDING',
      'description' => 'required|string|max:255'
    ]);

    $account = Account::find($id);
    $account["balance"] -= $request['amount'];
    $account->save();

    $entry = new Entry;
    $entry->fill($request->except(['account_id', 'category_id']));
    $entry->category_id = $request['category_id'];
    $entry->account_id = $id;

    if ($entry->save()) {
      return $this->sendResponse($entry, "Successfully added an entry");
    } else {
      return $this->sendError("Error in adding entry");
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
    $entry = Entry::where('id', $id)->get();

    if (isset($entry)) {
      return $this->sendResponse($entry, "Successfully retrieved data");
    }
  }

  public function showByUser($id)
  {
    $entry = Entry::where('account_id', $id)->get();

    if (isset($entry)) {
      return $this->sendResponse($entry, "Successfully retrieved data");
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
  }
}
