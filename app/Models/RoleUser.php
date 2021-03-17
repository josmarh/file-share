<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class RoleUser extends Model
{
    use HasFactory;
    use Sortable;

    public $table = 'role_user';

    public $sortable = [
        'role_id',
    ];
}
