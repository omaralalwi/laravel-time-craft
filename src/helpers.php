<?php

use Carbon\Carbon;

if (! function_exists('formatDate')) {
    /**
     * Format a given date to "Y-m-d" format.
     *
     * @param \DateTime|string $date
     * @return string
     */
    function formatDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        }

        return date('Y-m-d', strtotime($date));
    }
}

if (! function_exists('formatTime')) {
    /**
     * Format a given time to "h:i:s A" format.
     *
     * @param \DateTime|string $time
     * @return string
     */
    function formatTime($time)
    {
        if ($time instanceof \DateTime) {
            return $time->format('h:i:s A');
        }

        return date('h:i:s A', strtotime($time));
    }
}

if (! function_exists('getHumanDateTime')) {
    /**
     * Format the created_at date time to "Y-m-d H:i:s A" format.
     *
     * @param Carbon|string $createdAt
     * @return string
     */
    function getHumanDateTime($createdAt)
    {
        if ($createdAt instanceof Carbon) {
            return $createdAt->format('Y-m-d H:i:s A');
        }

        return $createdAt;
    }
}

if (! function_exists('formatDateTime')) {
    /**
     * Format a given date and time to "Y-m-d H:i:s A" format.
     *
     * @param \DateTime|string $dateTime
     * @return string
     */
    function formatDateTime($dateTime)
    {
        if ($dateTime instanceof \DateTime) {
            return $dateTime->format('Y-m-d H:i:s A');
        }

        return date('Y-m-d H:i:s A', strtotime($dateTime));
    }
}

if (! function_exists('formatTimeAgo')) {
    /**
     * Get a human-readable "time ago" format for a given date.
     *
     * @param Carbon|string $dateTime
     * @return string
     */
    function formatTimeAgo($dateTime)
    {
        if ($dateTime instanceof Carbon) {
            return $dateTime->diffForHumans();
        }

        return Carbon::parse($dateTime)->diffForHumans();
    }
}

if (! function_exists('startOfDay')) {
    /**
     * Get the start of the day for a given date.
     *
     * @param \DateTime|string $date
     * @return string
     */
    function startOfDay($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d 00:00:00');
        }

        return date('Y-m-d 00:00:00', strtotime($date));
    }
}

if (! function_exists('endOfDay')) {
    /**
     * Get the end of the day for a given date.
     *
     * @param \DateTime|string $date
     * @return string
     */
    function endOfDay($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d 23:59:59');
        }

        return date('Y-m-d 23:59:59', strtotime($date));
    }
}

if (! function_exists('isWeekend')) {
    /**
     * Check if a given date is on the weekend.
     *
     * @param \DateTime|string $date
     * @return bool
     */
    function isWeekend($date)
    {
        if ($date instanceof \DateTime) {
            $dayOfWeek = $date->format('N');
        } else {
            $dayOfWeek = date('N', strtotime($date));
        }

        return in_array($dayOfWeek, [6, 7]); // 6 = Saturday, 7 = Sunday
    }
}

if (! function_exists('addDays')) {
    /**
     * Add a specified number of days to a given date.
     *
     * @param \DateTime|string $date
     * @param int $days
     * @return string
     */
    function addDays($date, $days)
    {
        if ($date instanceof \DateTime) {
            return $date->modify("+$days days")->format('Y-m-d');
        }

        return date('Y-m-d', strtotime("+$days days", strtotime($date)));
    }
}

if (! function_exists('subtractDays')) {
    /**
     * Subtract a specified number of days from a given date.HasDateTimeFrameScopes.php
     *
     * @param \DateTime|string $date
     * @param int $days
     * @return string
     */
    function subtractDays($date, $days)
    {
        if ($date instanceof \DateTime) {
            return $date->modify("-$days days")->format('Y-m-d');
        }

        return date('Y-m-d', strtotime("-$days days", strtotime($date)));
    }
}
