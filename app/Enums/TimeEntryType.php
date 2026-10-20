<?php

namespace App\Enums;

enum TimeEntryType: string
{
    case Entry = 'entry';
    case Exit = 'exit';
}
