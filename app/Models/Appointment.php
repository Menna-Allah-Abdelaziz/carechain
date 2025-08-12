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
        'patient_id',
        'appointment_time',
        'location',
        'notes',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
    ];

    // علاقة الميعاد بالمريض (user)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // علاقة لجلب كل المستخدمين بنفس family_code (علاقة hasMany)
    public function users()
    {
        return $this->hasMany(User::class, 'family_code', 'family_code');
    }
}
