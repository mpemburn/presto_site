<?php

namespace App\Models;

use App\Traits\BindsDynamically;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use BindsDynamically;

    public $timestamps = false;
    public $primaryKey = 'ID';
    protected $fillable = [
        'post_content',
        'post_author',
        'post_title',
        'post_excerpt',
        'post_type',
        'to_ping',
        'pinged',
        'post_content_filtered',
        'guid',
    ];
}
