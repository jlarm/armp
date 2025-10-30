<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class InvitationPolicy
{
    use HandlesAuthorization;

    public function inviteConsultant(User $user): bool
    {
        return $user->role === Role::ADMIN;
    }
}
