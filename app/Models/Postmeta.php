<?php

namespace App\Models;

use App\Traits\BindsDynamically;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postmeta extends Model
{
    use HasFactory;
    use BindsDynamically;

    public $timestamps = false;
    public $primaryKey = 'meta_id';
    protected $fillable = [
        'post_id',
        'meta_key',
        'meta_value',
    ];
}
