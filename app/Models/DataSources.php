<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSources extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'fs_data_sources';
    protected $fillable = [
        'name',
    ];
}
