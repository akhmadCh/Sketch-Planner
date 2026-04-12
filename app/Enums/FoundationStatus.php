<?php

namespace App\Enums;

enum FoundationStatus: string
{
    case SAFE    = 'LAYAK';
    case WARNING = 'MARGIN';
    case DANGER  = 'BAHAYA';
}
