<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;


class AuthController extends BaseController
{
  public function register(Request $request)
  {
    $fields = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|string|unique:users,email|max:255',
      'password' => 'required|string|max:255',
      'confirm_password' => 'required|same:password|max:255'
    ]);

    $user = User::create([
      'first_name' => $fields['first_name'],
      'last_name' => $fields['last_name'],
      'email' => $fields['email'],
      'password' => bcrypt($fields['password']),
    ]);

    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
      'user' => $user,
      'token' => $token
    ];

    return $this->sendResponse($response, 'Successfully registered user');
  }

  public function login(Request $request)
  {
    $fields = $request->validate([
      'email' => 'required|email|string|max:255',
      'password' => 'required|string|max:255',
    ]);

    $user = User::where('email', $fields['email'])->first();

    if (!$user || !Hash::check($fields['password'], $user->password)) {
      return $this->sendError('Invalid credentials', [], 401);
    }

    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
      'user' => $user,
      'token' => $token
    ];

    return $this->sendResponse($response, 'Welcome back, ' . $user['first_name'] . '!');
  }

  public function getUserDetails(Request $request)
  {
    return $this->sendResponse($request->user(), '');
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();
    return $this->sendResponse([], 'Come back soon :(');
  }
}
