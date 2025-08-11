<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
public function user()
{
    return $this->belongsTo(User::class, 'created_by');
}

    protected $fillable = [
        'family_code',
        'name',
        'dosage',
        'quantity',
        'times_per_day',
        'first_dose_time',
        'created_by', 
    ];
}

