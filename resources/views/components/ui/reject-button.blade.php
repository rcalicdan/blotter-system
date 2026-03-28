@props(['id', 'name', 'wire' => 'reject', 'size' => 'sm'])

<x-ui.button variant="danger" :size="$size" x-data="{
    resourceId: {{ $id }},
    resourceName: '{{ $name }}',
}"
    @click="confirmAction({
        title: `Reject Account`,
        message: `Are you sure you want to reject ${resourceName}? They will be denied access to the system.`,
        confirmText: 'Reject Account',
        cancelText: 'Cancel',
        variant: 'danger',
        onConfirm: () => $wire.{{ $wire }}(resourceId)
    })"
    {{ $attributes }}>
    <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </x-slot:icon>
    {{ $slot->isEmpty() ? 'Reject' : $slot }}
</x-ui.button>