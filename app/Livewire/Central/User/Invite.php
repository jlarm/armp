<?php

declare(strict_types=1);

namespace App\Livewire\Central\User;

use App\Livewire\Forms\Central\User\InviteForm;
use App\Models\Invitation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Invite extends Component
{
    public InviteForm $form;

    public function sendInvite(): void
    {
        $this->authorize('invite-consultant', Invitation::class);

        $this->form->save();

        $this->form->reset();
    }

    public function render(): Factory|View
    {
        return view('livewire.central.user.invite');
    }
}
