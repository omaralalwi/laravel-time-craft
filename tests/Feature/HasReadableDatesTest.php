<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Feature;

use Illuminate\Support\Carbon;
use Omaralalwi\LaravelTimeCraft\Test\TestCase;
use Omaralalwi\LaravelTimeCraft\Test\Support\SoftOrder;
use Omaralalwi\LaravelTimeCraft\Test\Support\ReadableOrder;

class HasReadableDatesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2023-04-30 15:49:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_exposes_readable_created_and_updated_at(): void
    {
        $order = ReadableOrder::create(['name' => 'A']);

        $this->assertEquals('April 30, 2023 3:49 PM', $order->readable_created_at);
        $this->assertEquals('April 30, 2023 3:49 PM', $order->readable_updated_at);
    }

    public function test_it_does_not_clobber_the_real_carbon_timestamps(): void
    {
        $order = ReadableOrder::create(['name' => 'A']);

        // The real attribute is still a Carbon instance, not a formatted string.
        $this->assertInstanceOf(Carbon::class, $order->created_at);
    }

    public function test_readable_date_formats_an_arbitrary_field(): void
    {
        $order = ReadableOrder::create([
            'name' => 'A',
            'shipped_at' => '2023-04-30 15:49:00',
        ]);

        $this->assertEquals('April 30, 2023 3:49 PM', $order->readableDate('shipped_at'));
        $this->assertNull((new ReadableOrder())->readableDate('shipped_at'));
    }

    public function test_readable_deleted_at_is_null_until_soft_deleted(): void
    {
        $order = SoftOrder::create(['name' => 'A']);
        $this->assertNull($order->readable_deleted_at);

        $order->delete();
        $this->assertEquals('April 30, 2023 3:49 PM', $order->readable_deleted_at);
    }

    public function test_readable_deleted_at_is_null_without_soft_deletes(): void
    {
        $order = ReadableOrder::create(['name' => 'A']);

        $this->assertNull($order->readable_deleted_at);
    }

    public function test_per_model_format_override_wins(): void
    {
        $order = new class extends ReadableOrder {
            protected $readableDateFormat = 'Y-m-d';
        };
        $order->fill(['name' => 'A'])->save();

        $this->assertEquals('2023-04-30', $order->fresh()->readable_created_at);
    }

    public function test_config_format_is_used_when_no_property(): void
    {
        config(['laravel-time-craft.readable_datetime_format' => 'd/m/Y']);

        $order = ReadableOrder::create(['name' => 'A']);

        $this->assertEquals('30/04/2023', $order->readable_created_at);
    }
}
