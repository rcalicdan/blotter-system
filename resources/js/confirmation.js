window.confirmAction = function(options) {
    window.dispatchEvent(new CustomEvent('show-confirmation', {
        detail: {
            title: options.title || 'Confirm Action',
            message: options.message || 'Are you sure?',
            confirmText: options.confirmText || 'Confirm',
            cancelText: options.cancelText || 'Cancel',
            variant: options.variant || 'danger',
            onConfirm: options.onConfirm
        }
    }));
};