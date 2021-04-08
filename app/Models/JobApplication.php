<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    /*
     * VALUES
     * id                   integer     auto
     * applicant_id         integer     required
     * company_id           integer     required
     * status               string      required    pending/accepted/declined   default:pending
     * application_text     text        required
     * job_ad_id            integer     nullable
     * applies_for_role_id  integer     required
     */

    protected $attributes = [
        'status' => 'pending',
        'job_ad_id' => null,
        'applies_for_role_id' => 1,
    ];

    protected $fillable = [
        'application_text',
        'company_id',
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class, "applicant_id");
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id");
    }
}
