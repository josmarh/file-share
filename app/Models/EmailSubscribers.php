<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

use App\Models\User;

class EmailSubscribers extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;

    public $table = 'fs_email_subscribers';
    protected $fillable = [
        'name',
        'email',
        'status',
    ];
    public $sortable = [
        'name',
        'email',
    ];

}
