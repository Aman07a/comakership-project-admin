<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Broker;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadImageToAdmin()
    {
        $api_key = Broker::where('user_id', Auth::id())->value('api_key');
        return view('admin.image', compact('api_key'));
    }

    public function storeImageToAdmin(Request $req)
    {
        /**
         ** Testing: To upload image to broker (database)
         */
        $req->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $original_name = $req->file('image')->getClientOriginalName();
        $changed_name = preg_replace('/\.[^.]+$/', '', $original_name);
        $imageName = $changed_name . '.' . $req->image->extension();
        $req->image->move(storage_path('images/brokers/' . date('Y-m-d') . '/'), $imageName);
        $image = date('Y-m-d') . '/' .  $imageName;

        Broker::create(
            [
                'name' => 'Creator',
                'api_key' => 'creation',
                'image' => $image,
                'user_id' => '2',
            ]
        );

        return redirect()->route('home');
    }
}
