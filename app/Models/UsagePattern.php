<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class UsagePattern extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = ['customer_id', 'value'];
}
