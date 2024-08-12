<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'user_id']; // Ensure 'user_id' is an allowed field

    /**
     * Get categories for a specific user or all categories for admins.
     * 
     * @param int|null $userId
     * @param bool $isAdmin
     * @return array
     */
    public function getUserCategories($userId = null, $isAdmin = false)
    {
        $builder = $this->builder();

        if (!$isAdmin && $userId !== null) {
            $builder->where('user_id', $userId);
        }

        return $builder->get()->getResultArray();
    }

    public function getCategory($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        } else {
            return $this->getWhere(['id' => $id])->getRowArray();
        }
    }

    public function saveCategory($data)
    {
        return $this->insert($data) ? true : false;
    }

    public function updateCategory($data, $id)
    {
        return $this->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->delete($id);
    }
}
