<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Feature;

use Carbon\Carbon;
use Omaralalwi\LaravelTimeCraft\Test\Support\Order;
use Omaralalwi\LaravelTimeCraft\Test\TestCase;

class HasDateTimeScopesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Freeze "now" so every relative scope is deterministic.
        Carbon::setTestNow(Carbon::parse('2024-08-27 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function orderAt(string $dateTime): Order
    {
        return Order::create([
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);
    }

    public function test_today_scope_returns_only_todays_records(): void
    {
        $today = $this->orderAt('2024-08-27 08:00:00');
        $this->orderAt('2024-08-26 08:00:00');

        $results = Order::today()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($today));
    }

    public function test_yesterday_scope_returns_only_yesterdays_records(): void
    {
        $yesterday = $this->orderAt('2024-08-26 23:00:00');
        $this->orderAt('2024-08-27 01:00:00');

        $results = Order::yesterday()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($yesterday));
    }

    public function test_one_week_ago_matches_exactly_seven_days_ago(): void
    {
        $exact = $this->orderAt('2024-08-20 10:00:00'); // exactly 7 days before now
        $this->orderAt('2024-08-21 10:00:00');          // 6 days ago — excluded

        $results = Order::oneWeekAgo()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($exact));
    }

    public function test_last7days_scope_returns_a_range(): void
    {
        $this->orderAt('2024-08-25 10:00:00'); // within range
        $this->orderAt('2024-08-21 10:00:00'); // within range (>= 7 days ago)
        $this->orderAt('2024-08-15 10:00:00'); // older — excluded

        $results = Order::last7Days()->get();

        $this->assertCount(2, $results);
    }

    public function test_last_days_scope_respects_custom_day_count(): void
    {
        $this->orderAt('2024-08-20 10:00:00'); // 7 days ago — included for 12
        $this->orderAt('2024-08-10 10:00:00'); // 17 days ago — excluded

        $results = Order::lastDays(null, 12)->get();

        $this->assertCount(1, $results);
    }

    public function test_current_month_scope(): void
    {
        $this->orderAt('2024-08-01 10:00:00');
        $this->orderAt('2024-08-27 10:00:00');
        $this->orderAt('2024-07-30 10:00:00'); // last month — excluded

        $this->assertCount(2, Order::currentMonth()->get());
    }

    public function test_last_month_scope(): void
    {
        $this->orderAt('2024-07-15 10:00:00'); // last month
        $this->orderAt('2024-08-15 10:00:00'); // current month — excluded

        $results = Order::lastMonth()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('2024-07-15', $results->first()->created_at->toDateString());
    }

    public function test_current_year_scope(): void
    {
        $this->orderAt('2024-01-01 10:00:00');
        $this->orderAt('2023-12-31 10:00:00'); // last year — excluded

        $this->assertCount(1, Order::currentYear()->get());
    }

    public function test_between_dates_scope_is_inclusive(): void
    {
        $this->orderAt('2024-08-10 10:00:00');
        $this->orderAt('2024-08-20 10:00:00');
        $this->orderAt('2024-08-25 10:00:00'); // outside range

        $results = Order::betweenDates('2024-08-01', '2024-08-21')->get();

        $this->assertCount(2, $results);
    }

    public function test_between_dates_accepts_carbon_instances(): void
    {
        $this->orderAt('2024-08-10 10:00:00');
        $this->orderAt('2024-08-25 10:00:00');

        $results = Order::betweenDates(
            Carbon::parse('2024-08-01'),
            Carbon::parse('2024-08-15')
        )->get();

        $this->assertCount(1, $results);
    }

    public function test_scope_can_target_a_custom_field_per_call(): void
    {
        $order = $this->orderAt('2024-08-01 10:00:00');
        $order->updated_at = '2024-08-27 09:00:00';
        $order->save();

        // Default field (created_at) is in August but not "today".
        $this->assertCount(0, Order::today()->get());
        // updated_at is today.
        $this->assertCount(1, Order::today('updated_at')->get());
    }
}
