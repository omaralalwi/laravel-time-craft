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

    // --- Regression: currentMonth must also constrain the year ---
    public function test_current_month_excludes_the_same_month_in_other_years(): void
    {
        $this->orderAt('2024-08-10 10:00:00'); // current month & year
        $this->orderAt('2023-08-10 10:00:00'); // same month, previous year — excluded
        $this->orderAt('2022-08-10 10:00:00'); // same month, two years back — excluded

        $this->assertCount(1, Order::currentMonth()->get());
    }

    // --- Regression: lastMonth must also constrain the year ---
    public function test_last_month_excludes_the_same_month_in_other_years(): void
    {
        $this->orderAt('2024-07-10 10:00:00'); // last month & year
        $this->orderAt('2023-07-10 10:00:00'); // same month, previous year — excluded

        $this->assertCount(1, Order::lastMonth()->get());
    }

    // --- Regression: lastMonth must honor a per-call field on BOTH clauses ---
    public function test_last_month_respects_a_custom_field_per_call(): void
    {
        // created_at in the current month, updated_at in last month.
        Order::create([
            'created_at' => '2024-08-15 10:00:00',
            'updated_at' => '2024-07-15 10:00:00',
        ]);

        // Targeting updated_at, the record IS in last month.
        $this->assertCount(1, Order::lastMonth('updated_at')->get());
        // Targeting the default created_at, it is NOT in last month.
        $this->assertCount(0, Order::lastMonth()->get());
    }

    public function test_current_month_respects_a_custom_field_per_call(): void
    {
        // created_at last month, updated_at current month.
        Order::create([
            'created_at' => '2024-07-15 10:00:00',
            'updated_at' => '2024-08-15 10:00:00',
        ]);

        $this->assertCount(1, Order::currentMonth('updated_at')->get());
        $this->assertCount(0, Order::currentMonth()->get());
    }

    // --- Regression: month-end "now" must not overflow into the wrong month ---
    public function test_last_month_handles_month_end_now_without_overflow(): void
    {
        // On 31 Mar, a naive subMonth() can land back in March; "last month"
        // must resolve to February.
        Carbon::setTestNow(Carbon::parse('2024-03-31 12:00:00'));

        $this->orderAt('2024-02-15 10:00:00'); // February — the real last month
        $this->orderAt('2024-03-10 10:00:00'); // March — current month, excluded

        $results = Order::lastMonth()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('2024-02-15', $results->first()->created_at->toDateString());
    }

    // --- Coverage for previously untested scopes ---
    public function test_current_week_and_last_week_scopes(): void
    {
        // now = Tue 2024-08-27; the two dates fall in the current and previous
        // week regardless of whether the week starts on Sunday or Monday.
        $this->orderAt('2024-08-27 10:00:00'); // this week
        $this->orderAt('2024-08-21 10:00:00'); // last week

        $this->assertCount(1, Order::currentWeek()->get());
        $this->assertCount(1, Order::lastWeek()->get());
    }

    public function test_one_month_ago_matches_exactly_thirty_days_ago(): void
    {
        $this->orderAt('2024-07-28 10:00:00'); // exactly 30 days before now
        $this->orderAt('2024-07-29 10:00:00'); // 29 days — excluded

        $this->assertCount(1, Order::oneMonthAgo()->get());
    }

    public function test_one_year_ago_matches_exactly_one_year_ago(): void
    {
        $this->orderAt('2023-08-27 10:00:00'); // one year before now
        $this->orderAt('2023-08-28 10:00:00'); // excluded

        $this->assertCount(1, Order::oneYearAgo()->get());
    }

    public function test_last_year_scope(): void
    {
        $this->orderAt('2023-01-01 10:00:00'); // last year
        $this->orderAt('2023-12-31 10:00:00'); // last year
        $this->orderAt('2024-01-01 10:00:00'); // current year — excluded

        $this->assertCount(2, Order::lastYear()->get());
    }

    public function test_last_30_days_scope_range(): void
    {
        $this->orderAt('2024-08-01 10:00:00'); // within 30 days
        $this->orderAt('2024-07-28 10:00:00'); // exactly 30 days ago — within
        $this->orderAt('2024-07-01 10:00:00'); // older — excluded

        $this->assertCount(2, Order::last30Days()->get());
    }

    public function test_per_model_date_field_property_is_used(): void
    {
        // An anonymous Order subclass that points scopes at updated_at.
        $model = new class extends Order {
            protected $dateField = 'updated_at';
        };

        Order::create([
            'created_at' => '2024-01-01 10:00:00', // not today
            'updated_at' => '2024-08-27 09:00:00', // today
        ]);

        $this->assertCount(1, $model->newQuery()->today()->get());
    }
}
