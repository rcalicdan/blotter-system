@props(['id', 'name', 'wire' => 'approve', 'size' => 'sm'])

<x-ui.button variant="success" :size="$size" x-data="{
    resourceId: {{ $id }},
    resourceName: '{{ $name }}',
}"
    @click="confirmAction({
        title: `Approve Account`,
        message: `Are you sure you want to approve ${resourceName}? They will be granted access to the system.`,
        confirmText: 'Approve Account',
        cancelText: 'Cancel',
        variant: 'info',
        onConfirm: () => $wire.{{ $wire }}(resourceId)
    })"
    {{ $attributes }}>
    <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </x-slot:icon>
    {{ $slot->isEmpty() ? 'Approve' : $slot }}
</x-ui.button>
