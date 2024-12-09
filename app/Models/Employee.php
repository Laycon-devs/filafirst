<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'department_id',
        'state_id',
        'firstname',
        'lastname',
        'avatar',
        'date_of_birth',
        'salary',
        'address',
        'bio',
        'zipcode',
    ];

    public function country() : BelongsTo {
        return $this->belongsTo(Country::class);
    }
    public function state() : BelongsTo {
        return $this->belongsTo(State::class);
    }
    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }
}
