<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    /*
     * VALUES
     * id                   string  required
     * name                 string  required
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

    public function jobs_arriving()
    {
        return $this->hasMany(Job::class, "city_destination_id");
    }

    public function jobs_departing()
    {
        return $this->hasMany(Job::class, "city_departure_id");
    }

    public function companies()
    {
        return $this->hasMany(InGameCompany::class, "city_id");
    }
}
