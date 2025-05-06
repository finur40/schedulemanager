<?php

namespace App\Controllers;

use App\Models\CourseModel;
use CodeIgniter\RESTful\ResourceController;

class CourseController extends ResourceController
{
    protected $modelName = 'App\Models\CourseModel';
    protected $format    = 'json';

    public function index()
{
    $semesterId = $this->request->getGet('semester_id');

    $db = \Config\Database::connect();
    $builder = $db->table('courses');
    $builder->select("
    courses.id,
    courses.name,
    days.name as day,
    DATE_FORMAT(courses.time_start, '%H:%i') as time_start,
    DATE_FORMAT(courses.time_end, '%H:%i') as time_end,
    rooms.name as room,
    professors.name as professor
");
    $builder->join('days', 'days.id = courses.day_id');
    $builder->join('rooms', 'rooms.id = courses.room_id');
    $builder->join('professors', 'professors.id = courses.professor_id');

    if ($semesterId) {
        $builder->where('courses.semester_id', $semesterId);
    }

    $query = $builder->get();
    $data = $query->getResult();

    return $this->respond($data);
}

    public function show($id = null)
    {
        $data = $this->model->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Record not found');
    }

    public function create()
    {
        $data = $this->request->getPost();
        if ($this->model->insert($data)) {
            return $this->respondCreated($data);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        if ($this->model->update($id, $data)) {
            return $this->respond($data);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return $this->respondDeleted([
              'message' => 'Record deleted successfully',
              'id' => $id]);
        }
        return $this->failNotFound('Record not found');
    }
}
