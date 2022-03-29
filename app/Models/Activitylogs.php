<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitylogs extends Model
{
    use HasFactory;

    protected $connection = "mysql2";
    protected $guarded = [];
    protected $table = 'activityslogs';
        
    public function User(){
        return $this->setConnection('mysql')->belongsTo('App\Models\User','causer_id','id');
    }

}