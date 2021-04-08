<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckManufacturer extends Model
{
     /*
     * VALUES
     * id                       string required
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
    ];

    public function truck_models()
    {
        return $this->hasMany(TruckModel::class);
    }

    public function jobs()
    {
        return $this->hasManyThrough(Job::class, TruckModel::class);
    }
}
