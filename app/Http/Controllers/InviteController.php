<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mail\InvitationEmail;


class InviteController extends Controller
{
    public function showInviteForm()
    {
        return view('invites.invite');
    }

    public function sendInvite(Request $request)
    {
        // Validasi input jika diperlukan
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Kirim email undangan
        Mail::to($email)->send(new InvitationEmail());

        return redirect()->back()->with('success', 'Undangan telah berhasil dikirim.');
    }
}
