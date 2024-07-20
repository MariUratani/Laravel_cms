<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('invite');
    }

    public function sendInviteFamilyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = Auth::user();

        if (!$user->family_id) {
            return back()->with('error', '家族IDが設定されていません。先に家族を作成してください。');
        }

        // 既に招待されているメールアドレスかチェック
        $existingInvite = Invite::where('email', $request->email)->first();
        if ($existingInvite) {
            return back()->with('error', 'このメールアドレスは既に招待されています。');
        }

        try {
            $invite = Invite::create([
                'family_id' => $user->family_id,
                'email' => $request->email,
                'token' => Str::random(16),
            ]);

            // デバッグ用のメール送信
            Mail::raw('Test email', function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Email');
            });


            $invite->sendInvitationFamilyNotification();
            // $invite->sendInvitationFamilyNotification($invite->token);
            
            return back()->with('status', '招待メールを送信しました。');
        } catch (\Exception $e) {
            // ログにエラーを記録
            \Log::error('招待メール送信エラー: ' . $e->getMessage());
            return back()->with('error', '招待メールの送信に失敗しました。もう一度お試しください。');
        }
    }
}