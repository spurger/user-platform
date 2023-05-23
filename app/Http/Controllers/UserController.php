<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('name');

        $users = User::whereNot(
            auth()->user()->getAuthIdentifierName(),
            auth()->user()->getAuthIdentifier()
        )->when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%");
        })->get();

        return response()->json($users, 200);
    }
}
