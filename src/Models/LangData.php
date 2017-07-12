<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LangData extends Model
{
    protected $table = 'lang_data';
    protected $fillable = ['lang', 'json', 'model', 'uid'];
}
