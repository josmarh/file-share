<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class EmailSubscribers extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
    ];


    public function user()
    {
        return belongTo(User::class, 'user_id', 'id');
    }
}
