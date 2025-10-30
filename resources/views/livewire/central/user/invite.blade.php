<div>
    <flux:modal.trigger name="invite-employee">
        <flux:button size="sm" variant="primary">Invite Employee</flux:button>
    </flux:modal.trigger>

    <flux:modal name="invite-employee" class="md:w-96">
        <form wire:submit.prevent="sendInvite" class="space-y-6">
            <div>
                <flux:heading size="lg">Invite Employee</flux:heading>
            </div>

            <flux:input wire:model="form.email" type="email" placeholder="example@email.com" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Send</flux:button>
            </div>
        </form>
    </flux:modal>

</div>
