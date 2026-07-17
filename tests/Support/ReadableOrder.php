<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Support;

use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LaravelTimeCraft\Traits\HasReadableDates;

class ReadableOrder extends Model
{
    use HasReadableDates;

    protected $table = 'orders';

    protected $guarded = [];
}
