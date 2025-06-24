<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::
        when($request->has('search') && $request->filled('search'), function($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%');
        })
        ->when($request->has('role') && $request->filled('role'), function($query) use ($request) {
            if($request->role == 'all'){
                return $query;
            }
            $query->where('role', $request->role);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('admin.user.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.user.index');
    }
}