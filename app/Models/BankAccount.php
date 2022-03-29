<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function arenaDetails(){
        return $this->hasOne('App\Models\arena','area_code', 'area_code');
    }

    public function BankActivity(){
        return $this->hasMany('App\Models\Activitylogs','subject_id', 'id');
    }

   

}   
