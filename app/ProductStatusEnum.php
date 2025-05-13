<?php

namespace App;

enum ProductStatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';

    public static function label(): array
    {
        return [
            self::Draft->value => __('Draft'),
            self::Published->value => __('Published'),
        ];
    }

    public static function colors(): array
    {
        return [
            'gray' => self::Draft->value,
            'success' => self::Published->value,
        ];
    }

}

