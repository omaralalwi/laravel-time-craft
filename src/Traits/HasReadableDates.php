<?php

namespace Omaralalwi\LaravelTimeCraft\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Adds human-readable, non-destructive date accessors to any Eloquent model.
 *
 * Unlike overriding the real timestamp attributes, this trait exposes
 * separate `readable_*` accessors so `$model->created_at` keeps returning a
 * Carbon instance (sorting, comparisons and casting stay intact) while
 * `$model->readable_created_at` gives you a formatted string such as
 * "April 30, 2023 3:49 PM".
 *
 * The format is resolved with a three-level fallback (first match wins):
 *   1. The model's `protected $readableDateFormat` property.
 *   2. The `readable_datetime_format` value from the published config.
 *   3. The built-in default, "F j, Y g:i A".
 */
trait HasReadableDates
{
    /**
     * Resolve the format string used by the readable date accessors.
     */
    public function getReadableDateFormat(): string
    {
        return $this->readableDateFormat
            ?? config('laravel-time-craft.readable_datetime_format', 'F j, Y g:i A');
    }

    /**
     * Format any date-like value (Carbon, DateTime or parseable string) using
     * the readable format. Returns null for empty/unset values.
     *
     * Values that are already DateTime instances (e.g. Eloquent's cast
     * timestamps) are formatted directly, avoiding a redundant re-parse.
     *
     * @param  \DateTimeInterface|string|null  $value
     */
    public function toReadableDate($value): ?string
    {
        if (! $value) {
            return null;
        }

        $date = $value instanceof \DateTimeInterface ? $value : Carbon::parse($value);

        return $date->format($this->getReadableDateFormat());
    }

    /**
     * Format an arbitrary date attribute on the model, e.g.
     * `$post->readableDate('published_at')`.
     */
    public function readableDate(string $field): ?string
    {
        return $this->toReadableDate($this->getAttribute($field));
    }

    /**
     * Human-readable created_at, e.g. "April 30, 2023 3:49 PM".
     * Null when the model does not maintain timestamps or the value is unset.
     */
    public function getReadableCreatedAtAttribute(): ?string
    {
        if (! $this->usesTimestamps() || ! $this->getCreatedAtColumn()) {
            return null;
        }

        return $this->toReadableDate($this->getAttribute($this->getCreatedAtColumn()));
    }

    /**
     * Human-readable updated_at, e.g. "April 30, 2023 3:49 PM".
     * Null when the model does not maintain timestamps or the value is unset.
     */
    public function getReadableUpdatedAtAttribute(): ?string
    {
        if (! $this->usesTimestamps() || ! $this->getUpdatedAtColumn()) {
            return null;
        }

        return $this->toReadableDate($this->getAttribute($this->getUpdatedAtColumn()));
    }

    /**
     * Human-readable deleted_at, e.g. "April 30, 2023 3:49 PM".
     * Null unless the model uses SoftDeletes (detected recursively, so it also
     * works when the trait is applied via a parent class or another trait).
     */
    public function getReadableDeletedAtAttribute(): ?string
    {
        if (! in_array(SoftDeletes::class, class_uses_recursive($this))) {
            return null;
        }

        return $this->toReadableDate($this->getAttribute($this->getDeletedAtColumn()));
    }
}
