<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string value
 */

class Configs extends Model
{
    use HasFactory;
    protected $guarded = [];
}
