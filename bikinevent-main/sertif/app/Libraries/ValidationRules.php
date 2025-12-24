<?php

namespace App\Libraries;

use DateTime;

class ValidationRules
{
    public function validateDateAfter(string $date, string $params, array $data): bool
    {
        $startDate = $data[$params];
        $endDate = $date;

        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        return $endDateTime > $startDateTime;
    }
}
