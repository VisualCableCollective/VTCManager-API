<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    /*
     * VALUES
     * id                                       string required
     * game_item_translation_id                 string required
     */

    //string as id
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'game_item_translation_id',
    ];

    public function jobs(){
        return $this->hasMany(Job::class);
    }

    public function game_item_translation()
    {
        return $this->belongsTo(GameItemTranslation::class);
    }
}
