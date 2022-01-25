<?php

namespace app\enums;


class Type_Model_Data
{
    const Entity    = 'Entity';
    const Transaction   = 'Transaction';
    const Settings  = 'Settings';
    const Page      = 'Page';

    public static function enums()
    {
        return [
            self::Entity,
            self::Transaction,
            self::Settings,
            self::Page,
        ];
    }
}
