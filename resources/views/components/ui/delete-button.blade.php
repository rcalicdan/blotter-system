@props(['id', 'name', 'resource' => 'item', 'wire' => 'delete', 'size' => 'sm'])

<x-ui.button variant="danger" :size="$size" x-data="{
    resourceId: {{ $id }},
    resourceName: '{{ $name }}',
    resourceType: '{{ $resource }}'
}"
    @click="confirmAction({
        title: `Delete ${resourceType}`,
        message: `Are you sure you want to delete ${resourceName}? This action cannot be undone and all associated data will be permanently removed.`,
        confirmText: `Delete ${resourceType}`,
        cancelText: 'Cancel',
        variant: 'danger',
        onConfirm: () => $wire.{{ $wire }}(resourceId)
    })"
    {{ $attributes }}>
    <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </x-slot:icon>
    {{ $slot->isEmpty() ? 'Delete' : $slot }}
</x-ui.button>
