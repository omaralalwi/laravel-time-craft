<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Omaralalwi\LaravelTimeCraft\Traits\HasReadableDates;

class SoftOrder extends Model
{
    use SoftDeletes;
    use HasReadableDates;

    protected $table = 'soft_orders';

    protected $guarded = [];
}
