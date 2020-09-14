<?php


namespace App\Doctrine\DateTimeForMidas;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class DateTimeMidas extends DateTimeType
{
    private $dateTimeFormatString = 'Y-m-d H:i:s.000';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format($this->dateTimeFormatString) : null;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}