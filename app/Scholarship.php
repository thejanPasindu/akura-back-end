<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'user_id',
        'school',
        'university',
        'faculty',
        'grade',
        'academicyear',
        'semester',
        'addrboarding',
        'guardian_name',
        'relationship',
        'guardian_tp',
        'guardian_address',
        'family_anual_income',
        'family_anual_expence',
        'birth_certificate_1', 
        'birth_certificate_2',
        'confirm_letter',
        'gn_certificate',
        'approve',
        'reject',
        'has_sponsor'
    ];
}
