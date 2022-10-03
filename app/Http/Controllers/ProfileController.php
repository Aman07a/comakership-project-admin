<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adminProfile()
    {
        $id = Auth::user()->id;
        if (User::where('is_admin', 1)->where('id', $id)->get()->all()) {
            $admin = User::find($id);
            return view('admin.profile', compact('admin'));
        } else {
            return back();
        }
    }

    public function updateAdminProfile(Request $req)
    {
        $id = Auth::user()->id;
        $admin = User::find($id);

        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::where('id', $id)->update(
            [
                'name' => $req->name,
                'email' => $req->email,
                'phone_number' => $req->phone_number,
                'alternate_phone_number' => $req->alternate_phone_number,
                'password' => Hash::make($req->password),
                'is_admin' => $admin->is_admin,
                'created_at' => $admin->created_at,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        return redirect()->route('admin.profile')->with('success', 'Your account has been updated.');
    }

    public function userProfile()
    {
        $id = Auth::user()->id;
        if (User::where('is_admin', 0)->where('id', $id)->get()->all()) {
            $user = User::find($id);
            return view('client.profile', compact('user'));
        } else {
            return back();
        }
    }

    public function updateUserProfile(Request $req)
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::where('id', $id)->update(
            [
                'name' => $req->name,
                'email' => $req->email,
                'phone_number' => $req->phone_number,
                'alternate_phone_number' => $req->alternate_phone_number,
                'password' => Hash::make($req->password),
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        return redirect()->route('user.profile')->with('success', 'Your account has been updated.');
    }
}
