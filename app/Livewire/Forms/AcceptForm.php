<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Throwable;

final class AcceptForm extends Form
{
    #[Locked]
    public Invitation $invitation;

    #[Validate(['required', 'string', 'max:254'])]
    public string $name = '';

    #[Validate(['required', 'string', 'min:8', 'confirmed'])]
    public string $password = '';

    public string $password_confirmation = '';

    public function acceptInvitation(): ?User
    {
        $this->validate();

        try {
            return DB::transaction(function (): User {
                $user = User::query()->create([
                    'name' => $this->name,
                    'email' => $this->invitation->email,
                    'email_verified_at' => now(),
                    'password' => bcrypt($this->password),
                    'role' => $this->invitation->role,
                ]);

                $this->invitation->delete();

                return $user;
            });
        } catch (Throwable $throwable) {
            report($throwable);

            return null;
        }
    }
}
