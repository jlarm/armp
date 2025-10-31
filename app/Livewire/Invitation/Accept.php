<?php

declare(strict_types=1);

namespace App\Livewire\Invitation;

use App\Livewire\Forms\AcceptForm;
use App\Models\Invitation;
use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class Accept extends Component
{
    #[Locked]
    public Invitation $invitation;

    public AcceptForm $form;

    public function mount(): void
    {
        $this->form->invitation = $this->invitation;
    }

    public function accept(): void
    {
        $user = $this->form->acceptInvitation();

        if (! $user instanceof User) {
            Flux::toast(text: 'An error occurred while creating your account. Please try again.', variant: 'danger');

            return;
        }

        Auth::login($user);

        Flux::toast(text: 'Welcome! Your account has been created.', variant: 'success');

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render(): Factory|View
    {
        return view('livewire.invitation.accept');
    }
}
