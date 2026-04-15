<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Laravel AI CRM Lead Model
 * 
 * MongoDB-powered model for storing leads with dynamic fields,
 * AI scoring, status tracking, and source information.
 */
class Lead extends Model
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
    protected $collection = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int|string>
     */
    protected $fillable = [
        'ai_score',
        'status',
        'source',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ai_score' => 'float',
        'data' => 'array',
    ];

    /**
     * Get the data attribute as an array.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getDataAttribute($value): array
    {
        return $value ?: [];
    }

    /**
     * Set the data attribute as an array.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setDataAttribute($value): void
    {
        $this->attributes['data'] = is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Get the lead's AI score with formatting.
     *
     * @return string
     */
    public function getFormattedAiScoreAttribute(): string
    {
        return number_format($this->ai_score, 2) . '%';
    }

    /**
     * Scope to filter leads by status.
     *
     * @param  \MongoDB\Laravel\Eloquent\Builder  $query
     * @param  string  $status
     * @return \MongoDB\Laravel\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter leads with high AI score.
     *
     * @param  \MongoDB\Laravel\Eloquent\Builder  $query
     * @return \MongoDB\Laravel\Eloquent\Builder
     */
    public function scopeHighScore($query)
    {
        return $query->where('ai_score', '>', 80);
    }
}