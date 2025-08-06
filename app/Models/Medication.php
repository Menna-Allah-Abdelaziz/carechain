<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_code',
        'name',
        'dosage',
        'quantity',
        'times_per_day',
        'first_dose_time',
    ];
}

