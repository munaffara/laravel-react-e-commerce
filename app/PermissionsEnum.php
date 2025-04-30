<?php

namespace App;

enum PermissionsEnum: string
{
    case ApproveVendors = 'approve vendor';
    case SellProducts = 'sell products';
    case ByProducts = 'buy products';
}
