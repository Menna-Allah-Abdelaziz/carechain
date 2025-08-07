<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'doctor_name',
        'family_code',
        'appointment_time',
        'location',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'family_code');
    }
}
