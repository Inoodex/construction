<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_code',
        'full_name',
        'father_name',
        'mother_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'phone',
        'email',
        'nid_number',
        'present_address',
        'permanent_address',
        'designation',
        'department',
        'employment_type',
        'joining_date',
        'basic_salary',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo',
    ];

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
