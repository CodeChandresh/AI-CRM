<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Builder;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case TRIAL = 'trial';
    case PAST_DUE = 'past_due';
    case CANCELLED = 'cancelled';
    case UNPAID = 'unpaid';
}

class Customer extends EloquentModel
{
    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int|string>
     */
    protected $fillable = [
        'name',
        'email',
        'company',
        'churn_risk_score',
        'lifetime_value',
        'subscription_status',
        'last_contacted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'churn_risk_score' => 'integer',
        'lifetime_value' => 'float',
        'subscription_status' => SubscriptionStatus::class,
        'last_contacted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int>
     */
    protected $appends = ['churn_risk_percentage'];

    /**
     * Get formatted churn risk percentage.
     */
    public function getChurnRiskPercentageAttribute(): string
    {
        return number_format($this->churn_risk_score / 100, 2) . '%';
    }

    /**
     * Scope to filter high risk customers (churn risk > 70%).
     */
    public function scopeHighRisk(Builder $query): void
    {
        $query->where('churn_risk_score', '>', 70);
    }

    /**
     * Scope to filter customers with active subscriptions.
     */
    public function scopeActiveSubscription(Builder $query): void
    {
        $query->where('subscription_status', SubscriptionStatus::ACTIVE->value);
    }

    /**
     * Check if customer is at high risk of churn.
     */
    public function isHighRisk(): bool
    {
        return $this->churn_risk_score > 70;
    }

    /**
     * Check if customer has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === SubscriptionStatus::ACTIVE;
    }
}