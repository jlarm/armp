<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoleFilteredUsers
{
    abstract public function users(): BelongsToMany;

    public function usersByRole(Role $role): BelongsToMany
    {
        return $this->users()->where('role', $role);
    }

    public function owners(): BelongsToMany
    {
        return $this->usersByRole(Role::OWNER);
    }

    public function cfos(): BelongsToMany
    {
        return $this->usersByRole(Role::CFO);
    }

    public function gms(): BelongsToMany
    {
        return $this->usersByRole(Role::GM);
    }

    public function gsms(): BelongsToMany
    {
        return $this->usersByRole(Role::GSM);
    }

    public function managers(): BelongsToMany
    {
        return $this->usersByRole(Role::MANAGER);
    }

    public function employees(): BelongsToMany
    {
        return $this->usersByRole(Role::EMPLOYEE);
    }

    public function porters(): BelongsToMany
    {
        return $this->usersByRole(Role::PORTER);
    }

    public function consultants(): BelongsToMany
    {
        return $this->usersByRole(Role::CONSULTANT);
    }

    public function admins(): BelongsToMany
    {
        return $this->usersByRole(Role::ADMIN);
    }
}
