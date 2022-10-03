<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact()
    {
        return view('admin.contact');
    }

    public function sendEmail(Request $req)
    {
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone_number,
            'alternate_phone' => $req->alternate_phone_number,
            'message' => $req->message,
        ];
        Mail::to("aman21.aa22@gmail.com")->send(new ContactMail($data));
        return back()->with('message_sent', 'Your message has been succesfully sent!');
    }
}
