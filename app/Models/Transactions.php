<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\DataSources;

class Transactions extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $table = 'fs_transactions';
    protected $fillable = [
        'file_name',
        'file_hash',
        'data_source_id',
        'file_size',
        'file_type',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function datasources()
    {
        return $this->belongTo(DataSources::class, 'data_source_id', 'id');
    }
}
