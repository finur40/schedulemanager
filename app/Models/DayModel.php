<?php

namespace App\Models;

use CodeIgniter\Model;

class DayModel extends Model
{
    protected $table = 'days';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    protected $validationRules = [
        'name' => 'required|max_length[10]'
    ];
}
