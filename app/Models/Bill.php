<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bill extends Model
{
    use HasFactory;

    const STATUS_UNPAID = 'not paid';
    const STATUS_PAID = 'paid';
    const STATUS_PARTIALLY_PAID = 'partial';

    protected $guarded = [];

    protected $defaultOrder = ['reference' => 'desc'];

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
        $currentDate = now()->format('Ym'); // Get the current date in Ymd format (e.g., 20230821)
        $currentMonth = now()->format('Y-m'); // Get the current year-month (e.g., 2023-08)

        // Find the last bill created in the current month
        $lastBill = self::where('created_at', 'like', $currentMonth . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine the next bill number
        $nextBillNumber = $lastBill ? ((int) substr($lastBill->reference, -4) + 1) : 1;
        $paddedBillNumber = str_pad($nextBillNumber, 4, '0', STR_PAD_LEFT);

        // Generate the reference
        $reference = $currentDate . '-' . $paddedBillNumber;

        return $reference;
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

}
