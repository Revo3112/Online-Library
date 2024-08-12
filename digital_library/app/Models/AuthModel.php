<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role', 'created_at', 'updated_at'];
    protected $useTimestamps = true; // Enable automatic timestamps
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    public function login($identifier, $password)
    {
        // Set timezone to Indonesia (Western Indonesia Time)
        date_default_timezone_set('Asia/Jakarta');

        // Determine if the identifier is an email or username
        $field = (strpos($identifier, '@') !== false) ? 'email' : 'username';

        // Use Query Builder to fetch the user with parameter binding
        $user = $this->where($field, $identifier)->first();

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session data
                $sess_data = [
                    'user_id' => $user['id'],
                    'name' => $user['username'],
                    'role' => $user['role'],
                    'logged_in' => true
                ];
                session()->set($sess_data);

                // Update last_active time using Query Builder
                $this->update($user['id'], ['updated_at' => date('Y-m-d H:i:s')]);

                return true; // Login successful
            }
        }
        return false; // Login failed
    }

    public function register($username, $password, $email)
    {
        // Set timezone to Indonesia (Western Indonesia Time)
        date_default_timezone_set('Asia/Jakarta');

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the data into the database
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Use the insert method with Query Builder
        return $this->insert($data) !== false; // Return true if inserted successfully, otherwise false
    }

    public function findUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);
    }
}
