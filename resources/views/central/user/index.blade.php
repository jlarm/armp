<x-layouts.app :title="__('Users')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-between items-center">
            <flux:heading size="xl">Consultants</flux:heading>
            <livewire:central.user.invite />
        </div>
        <flux:separator variant="subtle" class="my-4" />
    </div>
</x-layouts.app>
