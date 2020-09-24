<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $fillable =[
      'c_num',
    	'comment',
    	'c_activation',
    	'created_at',
    	'updated_at',
    	'c_writer',
      'post_num'
    ];
}
