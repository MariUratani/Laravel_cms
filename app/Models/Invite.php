<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\InvitationFamilyNotification;


class Invite extends Model
{
    use HasFactory, Notifiable;
    // use Notifiable;
    protected $fillable = ['family_id', 'email', 'token'];
    public function sendInvitationFamilyNotification()
    // public function sendInvitationFamilyNotification($token)
    {
        $this->notify(new InvitationFamilyNotification($this));
        // $this->notify(new InvitationFamilyNotification($token, new BareMail()));
    }
}
