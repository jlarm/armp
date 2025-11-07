<?php

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public User $user;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $user->name }}</flux:table.cell>
    <flux:table.cell>{{ $user->email }}</flux:table.cell>
    <flux:table.cell>
        <flux:badge size="sm" :color="$user->role->color()" inset="top bottom">{{ $user->role->label() }}</flux:badge>
    </flux:table.cell>
</flux:table.row>
