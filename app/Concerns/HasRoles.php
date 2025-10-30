<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enums\Role;

trait HasRoles
{
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isConsultant(): bool
    {
        return $this->role === Role::CONSULTANT;
    }

    public function isOwner(): bool
    {
        return $this->role === Role::OWNER;
    }

    public function isCfo(): bool
    {
        return $this->role === Role::CFO;
    }

    public function isGm(): bool
    {
        return $this->role === Role::GM;
    }

    public function isGsm(): bool
    {
        return $this->role === Role::GSM;
    }

    public function isManager(): bool
    {
        return $this->role === Role::MANAGER;
    }

    public function isEmployee(): bool
    {
        return $this->role === Role::EMPLOYEE;
    }

    public function isPorter(): bool
    {
        return $this->role === Role::PORTER;
    }

    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function canManageDealerships(): bool
    {
        return $this->hasAnyRole([Role::ADMIN, Role::CONSULTANT]);
    }

    public function isStoreLevel(): bool
    {
        return $this->hasAnyRole([
            Role::OWNER,
            Role::CFO,
            Role::GM,
            Role::GSM,
            Role::MANAGER,
            Role::EMPLOYEE,
            Role::PORTER,
        ]);
    }
}
