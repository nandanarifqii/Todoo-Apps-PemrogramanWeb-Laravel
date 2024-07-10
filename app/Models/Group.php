<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


// Testing new
class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'joincode',
        'user_id',
    ];

    protected static function booted()
    {
        static::creating(function ($group) {
            $group->joincode = $group->generateUniqueJoinCode();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function generateUniqueJoinCode()
    {
        $code = Str::substr($this->name, 0, 1) . mt_rand(1000000, 9999999);


        while (self::where('joincode', $code)->exists()) {
            $code = Str::substr($this->name, 0, 1) . mt_rand(1000000, 9999999);
        }

        return $code;
    }

    public function taskGroups()
    {
        return $this->hasMany(TaskGroup::class);
    }
}



// class Group extends Model
// {
//     use HasFactory;

//     protected $fillable = ['name', 'description', 'user_id'];

//     public function users()
//     {
//         return $this->hasMany(User::class);
//     }

//     public function createdBy()
//     {
//         return $this->belongsTo(User::class, 'user_id');
//     }

//     protected static function boot()
//     {
//         parent::boot();

//         static::creating(function ($group) {
//             $group->joincode = $group->generateUniqueJoinCode();
//         });
//     }

//     protected function generateUniqueJoinCode()
//     {
//         $joincode = Str::substr($this->name, 0, 1) . mt_rand(1000000, 9999999);

//         // Check if the generated join code already exists in the database
//         while (static::where('joincode', $joincode)->exists()) {
//             $joincode = Str::substr($this->name, 0, 1) . mt_rand(1000000, 9999999);
//         }

//         return $joincode;
//     }
// }
