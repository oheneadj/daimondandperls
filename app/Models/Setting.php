<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'string',
            'type' => \App\Enums\SettingType::class,
        ];
    }

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            \App\Enums\SettingType::Integer => (int) $value,
            \App\Enums\SettingType::Boolean => (bool) $value,
            \App\Enums\SettingType::Json => json_decode($value, true),
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : (string) $value;
    }
}
