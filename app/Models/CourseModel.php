<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name', 'day_id', 'time_start', 'time_end', 'room_id', 'professor_id', 'semester_id'];

    protected $validationRules = [
        'code' => 'required|max_length[20]',
        'name' => 'required|max_length[100]',
        'day_id' => 'required|integer',
        'time_start' => 'required',
        'time_end' => 'required',
        'room_id' => 'required|integer',
        'professor_id' => 'required|integer',
        'semester_id' => 'required|integer'
    ];
}
