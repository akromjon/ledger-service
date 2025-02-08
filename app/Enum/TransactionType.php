<?php

namespace App\Enum;

enum TransactionType: string
{
    case DEBIT="debit";
    case CREDIT="credit";
}
