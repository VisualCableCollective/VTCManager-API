<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InGameCompany extends Model
{
    /*
     * VALUES                                   SCHEMA
     * id                   string  required    company_id.city_id
     * name                 string  required
     * city_id              string  required
     */

    use HasFactory;

    //string as id
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'city_id',
    ];

    public function jobs_arriving()
    {
        return $this->hasMany(Job::class, "company_destination_id");
    }

    public function jobs_departing()
    {
        return $this->hasMany(Job::class, "company_departure_id");
    }

    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
}
