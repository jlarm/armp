<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $sortBy = 'name';
    public string $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->centralUsers()
            ->select(['id', 'name', 'email', 'role'])
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(5);
    }
};
?>

<div>
    <flux:table :paginate="$this->users">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Role</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($this->users as $user)
                <livewire:central.user.index-item :key="$user->id" :$user />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
