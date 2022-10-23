<?php

namespace App\Http\Controllers;

use App\Mail\MailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(){
        $data = [
            'subject' => 'Judul Email',
            'body' => 'body email'
        ];
        Mail::to('mfahmifadh@gmail.com')->send(new MailVerification($data));
        return response()->json(['email terkirim']);

    }
}
