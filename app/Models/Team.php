<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function userDetails(){
        return $this->hasOne('App\Models\User', 'team_id', 'id')->where('isAssign', 1);
    }

    public function arenaDetails(){
        return $this->hasMany('App\Models\Arena', 'team', 'name');
    }
}
