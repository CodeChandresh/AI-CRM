<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Contact extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection name for the model.
     *
     * @var string
     */
    protected $collection = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int|string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
    ];

    /**
     * Get chart data for contacts.
     *
     * @return array
     */
    public static function chartData()
    {
        return [
            ['month' => 'Jan', 'count' => 10],
            ['month' => 'Feb', 'count' => 15],
            ['month' => 'Mar', 'count' => 20],
        ];
    }
}
