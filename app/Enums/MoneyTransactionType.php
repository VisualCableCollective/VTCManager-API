<?php

namespace App\Enums;

enum MoneyTransactionType
{
    case Common;
    case JobPayout;
    case Salary;
}
