<?php

namespace crudle\setup\enums;

use Yii;

class Type_Compression
{
    const None  = 'none';
    const Gzip  = 'gzip';
    const Zip   = 'zip';

    public static function enums()
    {
        return [
            self::None  => Yii::t('app', 'None'),
            self::Gzip  => Yii::t('app', 'Gzip'),
            self::Zip   => Yii::t('app', 'Zip'),
        ];
    }
}