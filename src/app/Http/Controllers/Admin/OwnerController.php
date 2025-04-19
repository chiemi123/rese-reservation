<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOwnerRequest;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'shop_owner');
        });

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $owners = $query->paginate(10);

        return view('admin.owners.index', compact('owners', 'keyword'));
    }

    public function create()
    {
        return view('admin.owners.create');
    }

    public function store(StoreOwnerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roleId = Role::where('name', 'shop_owner')->value('id');
        $user->roles()->attach($roleId);

        return redirect()->route('admin.owners.index')->with('success', '店舗代表者を登録しました。');
    }

    public function destroy(User $owner)
    {
        $owner->delete();

        return redirect()->route('admin.owners.index')->with('success', '店舗代表者を削除しました。');
    }
}
