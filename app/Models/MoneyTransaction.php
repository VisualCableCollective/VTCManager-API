<?php

namespace App\Models;

use App\Enums\MoneyTransactionStatus;
use App\Enums\MoneyTransactionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransaction extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'status' => MoneyTransactionStatus::class,
        'type' => MoneyTransactionType::class
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }
}
