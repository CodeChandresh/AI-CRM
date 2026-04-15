<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Activity model for tracking CRM interactions with AI features
 *
 * @property string $id
 * @property string $type
 * @property string $subject
 * @property string $description
 * @property \DateTime $date
 * @property int $user_id
 * @property float $sentiment_score
 * @property string $sentiment_label
 * @property string $ai_summary
 * @property array $details
 * @property \App\Models\User $user
 */
class Activity extends Model
{
    use SoftDeletes;

    /**
     * Available activity types
     */
    public const TYPE_CALL = 'call';
    public const TYPE_EMAIL = 'email';
    public const TYPE_MEETING = 'meeting';
    public const TYPE_NOTE = 'note';

    /**
     * Database connection
     */
    protected $connection = 'mongodb';

    /**
     * Database collection
     */
    protected $collection = 'activities';

    /**
     * Fillable fields
     */
    protected $fillable = [
        'type',
        'subject',
        'description',
        'date',
        'user_id',
        'sentiment_score',
        'sentiment_label',
        'ai_summary',
        'details',
    ];

    /**
     * Type castings
     */
    protected $casts = [
        'date' => 'datetime',
        'details' => 'array',
    ];

    /**
     * Get the user that owns the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}