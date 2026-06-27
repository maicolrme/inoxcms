<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Role Manager')]
class RoleManager extends Component
{
    public bool $showForm = false;
    public ?int $editingRoleId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    /** @var array<int> */
    public array $selectedPermissions = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . ($this->editingRoleId ?? 'NULL') . ',id',
            'description' => 'nullable|string|max:1000',
            'selectedPermissions' => 'array',
        ];
    }

    public function render(): View
    {
        return view('livewire.role-manager', [
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'permissionGroups' => Permission::all()->groupBy('group'),
        ]);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingRoleId = null;
    }

    public function edit(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->description = $role->description ?? '';
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $role = $this->editingRoleId
            ? Role::findOrFail($this->editingRoleId)
            : new Role;

        $role->name = $this->name;
        $role->slug = $this->slug;
        $role->description = $this->description;
        $role->guard_name = 'web';
        $role->save();

        $role->permissions()->sync($this->selectedPermissions);

        $this->dispatch('notify', message: 'Role saved.');
        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $role = Role::findOrFail($id);

        if ($role->slug === 'super-admin') {
            $this->dispatch('notify', message: 'Cannot delete super-admin role.');
            return;
        }

        $role->delete();
        $this->dispatch('notify', message: 'Role deleted.');
    }

    protected function resetForm(): void
    {
        $this->editingRoleId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->selectedPermissions = [];
    }
}
