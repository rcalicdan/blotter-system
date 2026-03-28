<div x-data="confirmationModal()" x-on:show-confirmation.window="show($event.detail)" x-show="open" x-cloak
    class="relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity">
    </div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="open" @click.away="close()" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon -->
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                            :class="{
                                'bg-red-100': variant === 'danger',
                                'bg-yellow-100': variant === 'warning',
                                'bg-blue-100': variant === 'info'
                            }">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                :class="{
                                    'text-red-600': variant === 'danger',
                                    'text-yellow-600': variant === 'warning',
                                    'text-blue-600': variant === 'info'
                                }">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" x-text="title"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" @click="confirm()" :disabled="loading"
                        class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm disabled:opacity-75 disabled:cursor-not-allowed sm:ml-3 sm:w-auto"
                        :class="{
                            'bg-red-600 hover:bg-red-700': variant === 'danger',
                            'bg-yellow-600 hover:bg-yellow-700': variant === 'warning',
                            'bg-blue-600 hover:bg-blue-700': variant === 'info'
                        }">
                        <template x-if="!loading">
                            <span x-text="confirmText"></span>
                        </template>
                        <template x-if="loading">
                            <span class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Processing...
                            </span>
                        </template>
                    </button>
                    <button type="button" @click="close()" :disabled="loading"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed sm:mt-0 sm:w-auto"
                        x-text="cancelText">
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmationModal() {
        return {
            open: false,
            loading: false,
            title: '',
            message: '',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: null,

            show(data) {
                this.title = data.title || 'Confirm Action';
                this.message = data.message || 'Are you sure?';
                this.confirmText = data.confirmText || 'Confirm';
                this.cancelText = data.cancelText || 'Cancel';
                this.variant = data.variant || 'danger';
                this.onConfirm = data.onConfirm || null;
                this.open = true;
                this.loading = false;
            },

            async confirm() {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    this.loading = true;
                    try {
                        await this.onConfirm();
                    } catch (error) {
                        console.error('Confirmation error:', error);
                    } finally {
                        this.loading = false;
                    }
                }
                this.close();
            },

            close() {
                this.open = false;
                this.loading = false;
                setTimeout(() => {
                    this.title = '';
                    this.message = '';
                    this.confirmText = 'Confirm';
                    this.cancelText = 'Cancel';
                    this.variant = 'danger';
                    this.onConfirm = null;
                }, 300);
            }
        }
    }
</script>
