<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name'];

    protected $validationRules = [
      'code' => 'required|max_length[20]',
      'name' => 'required|max_length[100]'
    ];
}
