<?php

namespace App\Enums;

enum WorkspaceType: string
{
    case Campaign = 'campaign';
    case Mandate = 'mandate';
}