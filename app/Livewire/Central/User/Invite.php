<?php

declare(strict_types=1);

namespace App\Livewire\Central\User;

use App\Livewire\Forms\Central\User\InviteForm;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

final class Invite extends Component
{
    public InviteForm $form;

    public function sendInvite(): void
    {
        $this->authorize('invite-consultant', Invitation::class);

        $invitation = $this->form->save();

        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        $this->form->reset();
    }

    public function render(): Factory|View
    {
        return view('livewire.central.user.invite');
    }
}
