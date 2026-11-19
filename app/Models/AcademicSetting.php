<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSetting extends Model
{
    use HasFactory;

    protected $table = 'academic_settings';

    protected $fillable = [
        'key',
        'value',
        'data_type',
        'group',
        'description',
    ];

    /**
     * Helper cepat untuk mendapatkan nilai setting berdasarkan key
     */
    public static function getVal(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return match ($setting->data_type) {
            'number'  => is_numeric($setting->value) ? (float) $setting->value : $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    /**
     * Helper cepat untuk set / update nilai setting
     */
    public static function setVal(string $key, $value, ?string $dataType = null, ?string $group = null, ?string $description = null)
    {
        $payload = ['value' => is_array($value) ? json_encode($value) : (string) $value];
        if ($dataType) $payload['data_type'] = $dataType;
        if ($group) $payload['group'] = $group;
        if ($description) $payload['description'] = $description;

        return static::updateOrCreate(['key' => $key], $payload);
    }
}
