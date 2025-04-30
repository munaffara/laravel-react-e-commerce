<?php

namespace App;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case VENDOR = 'vendor';
    case USER = 'user';
}
