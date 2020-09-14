<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomment extends Model
{
    use HasFactory;
    protected $table = 'recomment';
    protected $fillable =[
      'rc_num',
    	'recomment',
    	're_activation',
    	'c_num',
    	'rc_writer'
    ];
}
