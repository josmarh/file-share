<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class DataSources extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;

    public $table = 'fs_data_sources';
    protected $fillable = [
        'name',
    ];
    public $sortable = [
        'name',
    ];
}
