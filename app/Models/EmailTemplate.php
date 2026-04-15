<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\EmailTemplate
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property array $placeholders
 * @property bool $is_active
 * @property bool $is_ai_generated
 * @property int|null $user_id
 * @property string|null $prompt
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Database\Factories\EmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate active()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereIsAiGenerated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate wherePlaceholders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUserId($value)
 * @mixin \Eloquent
 */
class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'subject',
        'body',
        'placeholders',
        'is_active',
        'is_ai_generated',
        'user_id',
        'prompt',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'placeholders' => 'array',
        'is_active' => 'boolean',
        'is_ai_generated' => 'boolean',
    ];

    /**
     * Get the user that owns the template.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter active templates.
     */
    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Replace placeholders in the template body with actual values.
     */
    public function replacePlaceholders(array $data): string
    {
        $body = $this->body;
        foreach ($this->placeholders as $placeholder) {
            if (isset($data[$placeholder])) {
                $body = str_replace("{{$placeholder}}", $data[$placeholder], $body);
            }
        }
        return $body;
    }

    /**
     * Get validation rules for creating/updating templates.
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'placeholders' => 'array',
            'is_active' => 'boolean',
            'is_ai_generated' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
            'prompt' => 'nullable|string',
        ];
    }
}