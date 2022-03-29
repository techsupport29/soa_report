<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Maatwebsite\Excel\Facades\Excel;class import extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function arenaDetails(){
        return $this->hasOne('App\Models\arena','area_code', 'areaCode');
    }


    public function BankDetails(){
        return $this->hasMany('App\Models\BankAccount','area_code', 'area_code');
    }
    
    public function getImport() {
        $records = DB::table('imports')->select('id','arena_name','for_total', 'area_code')->get()->toArray();
        return $records;
    }
}