<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArenaHasGroup extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function group(){
    //     return $this->belongsTo('App\Models\OperatorGroups','group_id');
    // }

}
