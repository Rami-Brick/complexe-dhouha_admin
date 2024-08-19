<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bill extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            $bill->reference = self::generateUniqueReference();
        });
    }

    public static function generateUniqueReference()
    {
        do {
            $reference = Str::upper(Str::random(10));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }
}
