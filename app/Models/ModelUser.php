<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = [
        'nama_user',
        'email',
        'password',
        'nohp',
        'status',
        'nosim'
    ];

    public function getUser()
    {
        return $this->orderBy('status', 'ASC')->findAll();
    }

    public function insertData($data)
    {
        return $this->insert($data);
    }

    public function deleteuser($id_user)
    {
        return $this->delete($id_user);
    }

    public function updatedatauser($data, $id_user)
    {
        return $this->update($id_user, $data);
    }

    public function cek_login($email)
    {
        return $this->where('email', $email)->first();
    }
}