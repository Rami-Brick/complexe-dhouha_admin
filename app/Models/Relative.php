<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int student_id
 * @property string father_name
 * @property string mother_name
 * @property string phone_father
 * @property string phone_mother
 * @property string email
 * @property string address
 * @property string job_father
 * @property string job_mother
 * @property string cin_father
 * @property string cin_mother
 * @property string notes
 * @property string created_at
 * @property string updated_at
 *
 * @proprety Student student
 */
class Relative extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->hasMany(Student::class,'student_id');
    }
}
