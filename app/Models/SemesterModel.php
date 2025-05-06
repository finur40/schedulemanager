<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'start_date', 'end_date'];

    protected $validationRules = [
      'name' => 'required|max_length[50]',
      'start_date' => 'required',
      'end_date' => 'required'
    ];
}
