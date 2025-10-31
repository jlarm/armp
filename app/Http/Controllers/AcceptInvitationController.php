<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AcceptInvitationController extends Controller
{
    public function __invoke(Request $request): Factory|View|RedirectResponse
    {
        $invitation = Invitation::query()->where('token', $request->token)->first();

        if (! $invitation) {
            return to_route('login');
        }

        if (User::query()->where('email', $invitation->email)->exists()) {
            return to_route('login');
        }

        return view('invitation.accept', [
            'invitation' => $invitation,
        ]);
    }
}
