<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('User Permissions')]
class UserRoleManager extends Component
{
    public ?int $editingUserId = null;
    public string $search = '';
    /** @var array<int> */
    public array $selectedRoles = [];
    public bool $showForm = false;

    public function render(): View
    {
        $users = User::with('roles')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        return view('livewire.user-role-manager', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function edit(int $id): void
    {
        $user = User::with('roles')->findOrFail($id);
        $this->editingUserId = $user->id;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function save(): void
    {
        $user = User::findOrFail($this->editingUserId);
        $user->roles()->sync($this->selectedRoles);

        $this->dispatch('notify', message: 'Roles updated.');
        $this->showForm = false;
        $this->editingUserId = null;
    }
}
