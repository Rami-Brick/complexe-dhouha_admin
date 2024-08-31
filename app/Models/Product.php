<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    const TYPE_REGISTRATION = 1;
    const TYPE_SCHOLARSHIP = 2;
    const TYPE_OPTION = 3;

    public function scopeType(Builder $builder, $type)
    {
        return $builder->where('type', $type);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    /**
     * @param int $type
     * @return array
     */
    public static function list($type = 0)
    {
        return self::query()->when($type, function($query) use($type) {
            $query->type($type);
        })->pluck('name', 'id')->toArray();
    }

    public function toArray()
    {
        return ['name' => $this->name, 'fee' => $this->fee];
    }
}
