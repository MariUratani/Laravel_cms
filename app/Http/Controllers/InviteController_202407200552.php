<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    //
    public function showLinkRequestForm()
    {
        return view('invite');
    }

    public function sendInviteFamilyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        $invite = Invite::create([
            'family_id' => auth()->user()->family_id,
            'email' => $request->email,
            'token' => Str::random(16),
        ]);
    
        $invite->sendInvitationFamilyNotification($invite->token);
    
        return back()->with('status', '招待メールを送信しました。');
    }
}