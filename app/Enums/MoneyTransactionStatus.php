<?php

namespace App\Enums;

enum MoneyTransactionStatus
{
    case Pending;
    case PendingSystem;
    case Transferred;
}
