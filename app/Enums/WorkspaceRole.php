<?php

namespace App\Enums;

enum WorkspaceRole: string
{
    case Candidate = 'candidate';
    case Coordinator = 'coordinator';
    case ElectoralCaptain = 'electoral_captain';
    case CabinetAdvisor = 'cabinet_advisor';
    case Volunteer = 'volunteer';
    case Admin = 'admin';
}