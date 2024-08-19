<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string level
 * @property int staff_id
 * @property string created_at
 * @property string updated_at
 *
 * @property Student student
 * @property Objective objective
 * @property Staff staff
 */
class Course extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function course()
    {
        return $this->hasMany(Student::class,'student_id');
    }

    public function objective()
    {
        return $this->hasMany(Objective::class,'objective_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class,'staff_id');
    }


}
