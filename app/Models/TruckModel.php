<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckModel extends Model
{
    /*
     * VALUES
     * id                       string required
     * truck_manufacturer_id    string required
     * name                     string required
     */

    use HasFactory;
    //string as id
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'truck_manufacturer_id',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function truck_manufacturer(){
        return $this->belongsTo(TruckManufacturer::class, "truck_manufacturer_id");
    }
}
