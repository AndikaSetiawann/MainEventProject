<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('pages/auth/login');
    }

    public function attemptLogin()
    {
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('msg', 'Incorrect password.');
                return redirect()->to('/login');
            }
        } else {
            session()->setFlashdata('msg', 'Email not found.');
            return redirect()->to('/login');
        }
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'phone_number' => $user['phone_number'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        return view('pages/auth/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'name'          => 'required|min_length[3]|max_length[255]',
            'email'         => 'required|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[6]',
            'confirm_password' => 'matches[password]'
        ];

        if ($this->validate($rules)) {
            $model = new UserModel();
            $data = [
                'name'          => $this->request->getPost('name'),
                'email'         => $this->request->getPost('email'),
                'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'phone_number'  => $this->request->getPost('phone_number'),
                'role'          => 'participant',
            ];
            $model->save($data);
            session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->to('/login');
        } else {
            $data['validation'] = $this->validator;
            return view('pages/auth/register', $data);
        }
    }

    public function createAdmin()
    {
        log_message('info', 'CreateAdmin method called - Method: ' . $this->request->getMethod());

        // Only allow admins to create other admin accounts
        $isLoggedIn = session()->get('isLoggedIn');
        $role = session()->get('role');
        log_message('info', 'Session check - isLoggedIn: ' . ($isLoggedIn ? 'true' : 'false') . ', role: ' . $role);

        if (! $isLoggedIn || $role !== 'admin') {
            log_message('error', 'Unauthorized access to createAdmin');
            return redirect()->to('/no_access');
        }

        $method = $this->request->getMethod();
        log_message('info', 'Request method check: ' . $method . ' (comparing with "post")');

        if (strtolower($method) === 'post') {
            // Debug dengan session flashdata
            session()->setFlashdata('debug', 'POST request received! Data: ' . json_encode($this->request->getPost()));

            log_message('info', 'CreateAdmin POST request received');
            log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

            $rules = [
                'name'          => 'required|min_length[3]|max_length[255]',
                'email'         => 'required|valid_email|is_unique[users.email]',
                'password'      => 'required|min_length[6]',
                'confirm_password' => 'matches[password]'
            ];

            if ($this->validate($rules)) {
                log_message('info', 'Validation passed for createAdmin');
                $model = new UserModel();
                $data = [
                    'name'          => $this->request->getPost('name'),
                    'email'         => $this->request->getPost('email'),
                    'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'phone_number'  => $this->request->getPost('phone_number'),
                    'role'          => 'admin',
                ];

                if ($model->save($data)) {
                    log_message('info', 'Admin account created successfully: ' . $data['email']);
                    session()->setFlashdata('success', 'Admin account created successfully!');
                    return redirect()->to('/dashboard');
                } else {
                    log_message('error', 'Failed to create admin account: ' . json_encode($model->errors()));
                    session()->setFlashdata('error', 'Failed to create admin account: ' . implode(', ', $model->errors()));
                    return redirect()->back()->withInput();
                }
            } else {
                // Jika validasi gagal, kembalikan ke tampilan dengan error
                log_message('error', 'Validation failed for createAdmin: ' . json_encode($this->validator->getErrors()));
                $data = [
                    'title' => 'Buat Akun Admin Baru',
                    'page' => 'auth/create_admin',
                    'validation' => $this->validator
                ];
                return view('layout/main', $data);
            }
        } else {
            // Jika permintaan adalah GET, tampilkan formulir
            log_message('info', 'Showing createAdmin form');
            $data = [
                'title' => 'Buat Akun Admin Baru',
                'page' => 'auth/create_admin', // Tampilan untuk formulir
            ];
            return view('layout/main', $data);
        }
    }
}
