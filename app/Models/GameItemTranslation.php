<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * This model stores information related to the translation of a game item (like cargo names, etc.)
 */
class GameItemTranslation extends Model
{
    use HasFactory;

    /*
     * VALUES
     * id                                   string              not unique
     * language_code                        string
     * value                                text
     */

    protected $fillable = [
        "id",
        "language_code",
        "value",
    ];

    //id is a string
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

}
