<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArenaHasGroup extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function arenaDetials(){
        return $this->belongsTo('App\Models\arena','area_code','area_code');
    }

    public function SoaDetails(){
        return $this->hasOne('App\Models\import', 'areaCode','area_code');
    }

}
