<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'post';
    protected $fillable =[
      'post_num',
    	'Kategorie',
    	'Title',
      'Text',
    	'image',
    	'like',
    	'post_activation',
    	'writer',
      'categorie_num'
    ];
}
