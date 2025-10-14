<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'key',
        'value',
        'description'
    ];

    /**
     * Get a setting value by category and key
     */
    public static function getValue($category, $key, $default = null)
    {
        $setting = static::where('category', $category)
                        ->where('key', $key)
                        ->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue($category, $key, $value, $description = null)
    {
        return static::updateOrCreate(
            ['category' => $category, 'key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }

    /**
     * Get all settings for a category
     */
    public static function getCategory($category)
    {
        return static::where('category', $category)
                    ->pluck('value', 'key')
                    ->toArray();
    }

    /**
     * Set multiple settings for a category
     */
    public static function setCategory($category, array $settings)
    {
        foreach ($settings as $key => $value) {
            static::setValue($category, $key, $value);
        }
    }
}