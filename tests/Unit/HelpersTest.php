<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Unit;

use Carbon\Carbon;
use Omaralalwi\LaravelTimeCraft\Test\TestCase;

class HelpersTest extends TestCase
{
    public function test_format_date_from_string_and_datetime(): void
    {
        $this->assertEquals('2024-08-25', formatDate('2024-08-25 22:38:12'));
        $this->assertEquals('2024-08-25', formatDate(new \DateTime('2024-08-25 22:38:12')));
    }

    public function test_format_time(): void
    {
        $this->assertEquals('10:38:12 PM', formatTime('2024-08-25 22:38:12'));
        $this->assertEquals('10:38:12 PM', formatTime(new \DateTime('2024-08-25 22:38:12')));
    }

    public function test_format_date_time(): void
    {
        $this->assertEquals('2017-02-15 10:38:12 PM', formatDateTime('2017-02-15 22:38:12'));
    }

    public function test_get_human_date_time_formats_carbon_but_passes_strings_through(): void
    {
        $carbon = Carbon::parse('2017-02-15 22:38:12');

        $this->assertEquals('2017-02-15 10:38:12 PM', getHumanDateTime($carbon));
        // Non-Carbon input is returned unchanged.
        $this->assertEquals('not-a-carbon', getHumanDateTime('not-a-carbon'));
    }

    public function test_format_time_ago(): void
    {
        $this->assertEquals('2 days ago', formatTimeAgo(Carbon::now()->subDays(2)));
        $this->assertStringContainsString('ago', formatTimeAgo('2000-01-01 00:00:00'));
    }

    public function test_start_and_end_of_day(): void
    {
        $this->assertEquals('2024-08-23 00:00:00', startOfDay('2024-08-23 15:30:00'));
        $this->assertEquals('2024-08-23 23:59:59', endOfDay('2024-08-23 15:30:00'));
    }

    public function test_is_weekend(): void
    {
        $this->assertTrue(isWeekend('2024-08-24'));  // Saturday
        $this->assertTrue(isWeekend('2024-08-25'));  // Sunday
        $this->assertFalse(isWeekend('2024-08-26')); // Monday
    }

    public function test_add_and_subtract_days(): void
    {
        $this->assertEquals('2024-09-02', addDays('2024-08-23', 10));
        $this->assertEquals('2024-08-13', subtractDays('2024-08-23', 10));
    }
}
