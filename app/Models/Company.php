<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    /*
     * VALUES
     * id               integer     auto
     * name             string      required
     * status           string      required    active/inactive/deleted
     * about_us         text        nullable
     * logo_url         string      required    default:
     * bank_balance     bigint      required    default: 0
     * created_at       datetime    auto
     * updated_at       datetime    auto
     * owner_id         uint        required
     */

    protected $attributes = [
        'status' => 'active',
        'logo_url' => '',
        'bank_balance' => 0,
    ];

    protected $fillable  = [
        "name",
        "owner_id",
    ];

    public function job_applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function jobs(){
        return $this->hasMany(Job::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, "owner_id");
    }
}
