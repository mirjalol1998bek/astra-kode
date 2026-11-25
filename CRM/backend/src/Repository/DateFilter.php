<?php

namespace App\Repository;

class DateFilter
{
    // frontenddan sana "2024-11-25" ko'rinishida keladi
    public static function parse(?string $value): ?\DateTimeImmutable
    {
        if (!$value) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        return $date ?: null;
    }
}
