<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true; // Enables soft deletes
    protected $useTimestamps = true; // Enables automatic handling of created_at and updated_at fields
    protected $deletedField = 'deleted_at'; // The column to track deleted records

    protected $allowedFields = [
        'title',
        'category_id',
        'description',
        'quantity',
        'file_path',
        'cover_image',
        'created_by',
    ];

    public function getBooksWithAuthorsAndCategories($userId = null, $onlyActive = true)
    {
        $builder = $this->db->table($this->table);
        $builder->select('books.*, users.username as author_name, categories.name as category_name');
        $builder->join('users', 'users.id = books.created_by', 'left');
        $builder->join('categories', 'categories.id = books.category_id', 'left');

        if ($onlyActive) {
            $builder->where('books.deleted_at', null);
        }

        if ($userId !== null) {
            $builder->where('books.created_by', $userId);
        }

        return $builder->get()->getResult();
    }
}
