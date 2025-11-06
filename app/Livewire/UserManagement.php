<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $name, $email, $password, $role = 'member';
    public $user_id;
    public $isOpen = false;
    public $isEdit = false;
    public $isDeleteOpen = false;
    public $userToDelete = null;
    public $search = '';
    public $roleFilter = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:owner,karyawan,member',
    ];

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'user_id', 'isEdit']);
        $this->role = 'member';
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'email_verified_at' => now(),
        ]);

        $this->dispatch('show-toast', [
            'message' => 'User berhasil ditambahkan!',
            'type' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEdit = true;
        $this->isOpen = true;

        $this->rules['email'] = 'required|email|unique:users,email,' . $this->user_id;
        $this->rules['password'] = 'nullable|min:6';
    }

    public function update()
    {
        $this->rules['email'] = 'required|email|unique:users,email,' . $this->user_id;
        $this->rules['password'] = 'nullable|min:6';
        
        $this->validate();

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'User berhasil diupdate!',
            'type' => 'success'
        ]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            $this->dispatch('show-toast', [
                'message' => 'Tidak bisa menghapus akun sendiri!',
                'type' => 'error'
            ]);
            return;
        }

        $this->userToDelete = $user;
        $this->isDeleteOpen = true;
    }

    public function cancelDelete()
    {
        $this->isDeleteOpen = false;
        $this->userToDelete = null;
    }

    public function delete()
    {
        if (!$this->userToDelete) {
            return;
        }

        $this->userToDelete->delete();

        $this->dispatch('show-toast', [
            'message' => 'User berhasil dihapus!',
            'type' => 'success'
        ]);

        $this->isDeleteOpen = false;
        $this->userToDelete = null;
    }

    public function render()
    {
        $currentUser = auth()->user();
        
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function($query) {
                $query->where('role', $this->roleFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
            'currentUser' => $currentUser,
        ])->layout('layouts.app');
    }
}
