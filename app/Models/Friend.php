<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;
    protected $table='friends';
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
    public function acceptRequest()
    {
        return $this->update(['status' =>'accepted']);
    }
    public function rejectRequest()
    {
        return $this->update(['status' =>'rejected']);
    }
}
