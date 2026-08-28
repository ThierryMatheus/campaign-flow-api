<?php

namespace App\Enums;

enum WorkspaceStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
}