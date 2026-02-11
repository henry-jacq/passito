<?php

namespace App\Enum;

enum ResourceVisibility: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
