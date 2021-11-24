<?php

namespace App\Services;

use Carbon\Carbon;

class BusinessHoursService
{
    public function now()
    {
        $businessDays = [1, 2, 3, 4, 5];
        $marketOpen = \DateTime::createFromFormat('H:i:s', '16:30:00');
        $marketClose = \DateTime::createFromFormat('H:i:s', '23:00:00');
        $now = \DateTime::createFromFormat('H:i:s', Carbon::now()->toTimeString());
        if (in_array(Carbon::now()->dayOfWeek, $businessDays)) {
            if ($now > $marketOpen && $now < $marketClose) {
                return true;
            }
        }
    }
}
