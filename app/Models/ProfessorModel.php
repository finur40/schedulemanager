<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfessorModel extends Model
{
    protected $table = 'professors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    protected $validationRules = [
        'name' => 'required|max_length[100]'
    ];
}
