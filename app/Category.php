<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='exp_category';
    protected $fillable=['display_name','description'];
}
