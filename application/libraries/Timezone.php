<?php
use Carbon\Carbon;

class Timezone {
    public function convertUtc($value)
    {
        $utc = Carbon::parse($value, 'UTC');
        $timezone = Carbon::parse($value, $_SESSION['timezone']);

        $difference = $timezone->diffInHours($utc);

        return $utc->addHours($difference);
    }

    public function convertUtcAdd2Day($value) {
        $utc = Carbon::parse($value, 'UTC');
        $timezone = Carbon::parse($value, $_SESSION['timezone']);

        $difference = $timezone->diffInHours($utc);
        $utc->addHours($difference);
        return $utc->addDays(1);
    }
}