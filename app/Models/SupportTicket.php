<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SupportTicket extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = ['customer_id', 'description'];
}
