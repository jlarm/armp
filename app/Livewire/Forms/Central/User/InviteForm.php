<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Central\User;

use App\Enums\Role;
use App\Models\Invitation;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class InviteForm extends Form
{
    #[Validate(['required', 'email', 'unique:users,email', 'unique:invitations,email'])]
    public string $email = '';

    public function save(): Invitation
    {
        $this->validate();

        return Invitation::query()->create([
            'email' => $this->email,
            'token' => Invitation::generateToken(),
            'role' => Role::CONSULTANT,
            'invited_by' => auth()->id(),
        ]);
    }
}
