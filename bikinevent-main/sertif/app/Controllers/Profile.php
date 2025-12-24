<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id'); // Ubah dari user_id ke id
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => [
                'name' => $user['name'] ?? '',
                'email' => $user['email'] ?? '',
                'phone_number' => $user['phone_number'] ?? '',
                'role' => $user['role'] ?? ''
            ]
        ];

        return view('pages/profile/index', $data);
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id'); // Ubah dari user_id ke id
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone_number' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number')
        ];

        if ($this->userModel->update($userId, $data)) {
            // Update session name
            session()->set('name', $data['name']);
            return redirect()->to('/profile')->with('message', 'Profil berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui profil');
    }

    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id'); // Ubah dari user_id ke id
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan');
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password saat ini tidak sesuai');
        }

        if ($this->userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)])) {
            return redirect()->to('/profile')->with('message', 'Password berhasil diubah');
        }

        return redirect()->back()->with('error', 'Gagal mengubah password');
    }
} 