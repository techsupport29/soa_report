<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorGroups extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function arena(){
    //     return $this->hasMany('App\Models\arena','area_code');
    // }

    public function Hasgroup(){
        return $this->hasMany('App\Models\ArenaHasGroup','group_id');
    }

}
