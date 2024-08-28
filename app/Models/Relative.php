<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
// @property string name
/**
 * @property int id
 * @property int student_id
 * @property string father_name
 * @property string mother_name
 * @property string phone_father
 * @property string phone_mother
 * @property string email
 * @property string password
 * @property string address
 * @property string job_father
 * @property string job_mother
 * @property string cin_father
 * @property string cin_mother
 * @property string notes
 * @property string created_at
 * @property string updated_at
 * @property string parent_name
 *
 * @proprety \Illuminate\Support\Collection students
 */
class Relative  extends Authenticatable implements  HasName
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getFilamentName(): string
    {
        return "{$this->father_name} {$this->mother_name}";
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    protected function parentName(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $this->father_name??$this->mother_name,
        );
    }
    public function isRelative(): bool
    {
        return  $this->email = DB::table('relative')->exists();
    }

}
