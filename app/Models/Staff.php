<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * @property int id
 * @property string name
 * @property string phone
 * @property string email
 * @property string password
 * @property string created_at
 * @property string updated_at
 * @property Course course
 * @property Comment comment
 */

class Staff extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function course()
    {
        return $this->belongsToMany(Course::class,'course_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class,'comment_id');
    }
    public function isStaff(): bool
    {
        return  $this->email = DB::table('staff')->exists();
    }
}
