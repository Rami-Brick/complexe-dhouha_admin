<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property string first_name
 * @property string last_name
 * @property string birth_date
 * @property int course_id
 * @property int gender
 * @property int relative_id
 * @property string payment_status
 * @property string comments
 * @property string event_participation
 * @property string leave_with
 * @property string created_at
 * @property string updated_at
 *
 * @property Relative relative
 */
class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
    ];

//    public function getBirthDateAttribute($value)
//    {
//        return $value.$value;
//    }
//    protected function birthDate(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => $value.$value,
//            set: fn (string $value) => date('Y-m-d', strtotime($value)),
//        );
//    }

//    public $sortable = ['id',
//        'first_name',
//        'last_name',
//        'birth_date',
//        'gender',
//        'course.name',
//        'created_at'
//    ];

    /**
     * @return BelongsTo
     */
    public function relative()
    {
        return $this->belongsTo(Relative::class,'relative_id');
    }

    /**
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function event()
    {
        return $this->belongsToMany(Event::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class,'comment_id');
    }

    public function bill()
    {
        return $this->hasMany(Bill::class,'bill_id');
    }



}
