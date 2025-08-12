<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalFile extends Model
{
    protected $fillable = ['file_path', 'note', 'file_type', 'family_code', 'patient_id'];
}