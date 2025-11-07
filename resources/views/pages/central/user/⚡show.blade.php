<?php

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public User $user;

    public function render()
    {
        return $this->view()
            ->title($this->user->name);
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex justify-between items-center">
        <flux:heading size="xl">{{ $user->name }}</flux:heading>
        <flux:button wire:navigate :href="route('user.index')" variant="ghost" icon="arrow-left">Back</flux:button>
    </div>
    <flux:separator variant="subtle" class="my-4" />
    <div>

    </div>
</div>
