<?php

namespace Omaralalwi\LaravelTimeCraft\Traits;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait HasDateTimeScopes
{
    protected function getDateField($dateField = null): string
    {
        return $dateField ?: ($this->dateField ?? config('laravel-time-craft.default_field'));
    }

    /**
     * Scope a query to include only records created today.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeToday($query, $dateField = null): Builder
    {        return $query->whereDate($this->getDateField($dateField), today());
    }

    /**
     * Scope a query to include only records created yesterday.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeYesterday($query, $dateField = null): Builder
    {
        return $query->whereDate($this->getDateField($dateField), now()->subDay()->toDateString());
    }

    /**
     * Scope a query to include records created in the last seven days.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeOneWeekAgo($query, $dateField = null): Builder
    {
        return $query->whereDate($this->getDateField($dateField), '>=', now()->subDays(7));
    }

    /**
     * Scope a query to include records created in the last week.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeLastWeek($query, $dateField = null): Builder
    {
        return $query->whereBetween($this->getDateField($dateField), [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to include records created in the current week.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeCurrentWeek($query, $dateField = null): Builder
    {
        return $query->whereBetween($this->getDateField($dateField), [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to include records created in the last 30 days.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeOneMonthAgo($query, $dateField = null): Builder
    {
        return $query->whereDate($this->getDateField($dateField), '>=', now()->subDays(30));
    }

    /**
     * Scope a query to include records created last month.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeLastMonth($query, $dateField = null): Builder
    {
        return $query->whereMonth($this->getDateField(), now()->subMonth()->month)
            ->whereYear($this->getDateField($dateField), now()->subMonth()->year);
    }

    /**
     * Scope a query to only include records from the current month.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentMonth($query, $dateField = null): Builder
    {
        return $query->whereMonth($this->getDateField($dateField), now()->month);
    }

    /**
     * Scope a query to include records created in the last year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeLastYear($query, $dateField = null): Builder
    {
        return $query->whereYear($this->getDateField($dateField), now()->subYear()->year);
    }

    /**
     * Scope a query to include records created exactly one year ago.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeOneYearAgo($query, $dateField = null): Builder
    {
        return $query->whereDate($this->getDateField($dateField), '=', now()->subYear()->toDateString());
    }

    /**
     * Scope a query to include records created in the current year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeCurrentYear($query, $dateField = null): Builder
    {
        return $query->whereYear($this->getDateField($dateField), now()->year);
    }

    /**
     * Scope a query to include records within a given date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon|string $startDate The start date of the range.
     * @param \Carbon\Carbon|string $endDate The end date of the range.
     * @param string $dateField
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    public function scopeBetweenDates($query, $startDate, $endDate, $dateField = null): Builder
    {
        $start = $startDate instanceof Carbon ? $startDate->toDateString() : $startDate;
        $end = $endDate instanceof Carbon ? $endDate->toDateString() : $endDate;

        return $query->whereBetween($this->getDateField($dateField), [$start, $end]);
    }
}
