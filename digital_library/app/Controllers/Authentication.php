<?php

namespace App\Controllers;

use App\Models\AuthModel;
use Config\Services;
use CodeIgniter\I18n\Time;

class Authentication extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new AuthModel();
    }

    public function login()
    {
        return view('auth/login');
    }

    public function login_controller()
    {
        helper(['form']);
        $validation = Services::validation();

        // Enhanced validation rules
        $validation->setRules([
            'identifier' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Username atau email wajib diisi.',
                    'min_length' => 'Username atau email harus terdiri dari minimal 3 karakter.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password harus terdiri dari minimal 6 karakter.'
                ]
            ]
        ]);

        if ($this->request->getPost('LOGIN') == null) {
            return redirect()->to('login');
        } else {
            if ($validation->withRequest($this->request)->run()) {
                $identifier = $this->request->getPost('identifier');
                $password = $this->request->getPost('password');

                // Rate limiting: check the session for login attempts
                $session = session();
                $attempts = $session->get('login_attempts') ?? 0;
                $lastAttempt = $session->get('last_login_attempt');

                if ($attempts >= 5 && $lastAttempt && $lastAttempt->isAfter(Time::now()->subMinutes(2))) {
                    return redirect()->to('login')->with('msg', 'Terlalu banyak upaya masuk. Harap coba lagi nanti.')->with('error', true);
                }

                $success = $this->userModel->login($identifier, $password);

                if ($success) {
                    // Reset login attempts on successful login
                    $session->remove(['login_attempts', 'last_login_attempt']);

                    // Log the successful login
                    log_message('info', "User $identifier logged in successfully.");

                    return redirect()->to('universal/home');
                } else {
                    // Increment login attempts
                    $session->set('login_attempts', $attempts + 1);
                    $session->set('last_login_attempt', Time::now());

                    // Log the failed login attempt
                    log_message('warning', "Failed login attempt for user $identifier.");

                    return redirect()->to('login')->withInput()->with('msg', 'Username atau password salah.')->with('error', true);
                }
            } else {
                $errors = $validation->getErrors();
                $errorMsg = implode('<br>', $errors); // Combine all validation errors
                return redirect()->to('login')->withInput()->with('msg', $errorMsg)->with('error', true);
            }
        }
    }

    public function register()
    {
        return view('auth/register');
    }

    public function register_controller()
    {
        helper(['form']);
        $validation = Services::validation();

        // Enhanced validation rules
        $validation->setRules([
            'username' => [
                'rules' => 'required|min_length[3]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'min_length' => 'Username harus terdiri dari minimal 3 karakter.',
                    'is_unique' => 'Username sudah ada. Silakan pilih yang lain.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email sudah terdaftar.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password harus terdiri dari minimal 6 karakter.'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak cocok dengan password.'
                ]
            ]
        ]);

        if ($this->request->getPost('REGISTER') == null) {
            return redirect()->to('register');
        } else {
            if ($validation->withRequest($this->request)->run()) {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                $email = $this->request->getPost('email');

                // Check for existing account
                if ($this->userModel->where('username', $username)->orWhere('email', $email)->first()) {
                    return redirect()->to('register')->withInput()->with('msg', 'Akun sudah ada dengan username atau email ini.')->with('error', true);
                }

                $success = $this->userModel->register($username, $password, $email);

                if ($success) {
                    return redirect()->to('login')->with('msg', 'Registrasi berhasil. Silakan login.');
                } else {
                    return redirect()->to('register')->with('msg', 'Registrasi gagal.')->with('error', true);
                }
            } else {
                $errors = $validation->getErrors();
                $errorMsg = implode('<br>', $errors); // Combine all validation errors
                return redirect()->to('register')->withInput()->with('msg', $errorMsg)->with('error', true);
            }
        }
    }

    public function showResetForm()
    {
        return view('auth/reset_password');
    }

    public function resetPassword()
    {
        // Retrieve the email/username and new password from the request
        $identifier = $this->request->getPost('identifier');
        $newPassword = $this->request->getPost('password');

        // Find user by email or username
        $user = $this->userModel->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Update the user's password
        $this->userModel->update($user['id'], ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);

        return redirect()->to('/login')->with('success', 'Password has been reset.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
