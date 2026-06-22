<?php

namespace Omaralalwi\LaravelTimeCraft\Test\Support;

use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LaravelTimeCraft\Traits\HasDateTimeScopes;

class Order extends Model
{
    use HasDateTimeScopes;

    protected $table = 'orders';

    protected $guarded = [];
}
