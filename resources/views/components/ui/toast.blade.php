<div x-data="{
    show: false,
    message: '',
    type: 'success',
    progress: 100,
    interval: null,
    init() {
        @if(session()->has('notify'))
            this.showNotification(
                '{{ session('notify.message') }}',
                '{{ session('notify.type', 'success') }}'
            );
        @endif

        Livewire.on('notify', (event) => {
            this.showNotification(event.message, event.type || 'success');
        });
    },
    showNotification(message, type) {
        this.message = message;
        this.type = type;
        this.show = true;
        this.progress = 100;

        if (this.interval) clearInterval(this.interval);

        const duration = 2500;
        const step = 100 / (duration / 50);
        this.interval = setInterval(() => {
            this.progress -= step;
            if (this.progress <= 0) {
                this.show = false;
                clearInterval(this.interval);
            }
        }, 50);
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-x-full scale-95"
    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
    x-transition:leave-end="opacity-0 translate-x-full scale-95" class="fixed top-6 right-6 z-[9999] w-full max-w-sm"
    style="display: none;" @mouseenter="if(interval) clearInterval(interval)"
    @mouseleave="
        if(show && progress > 0) {
            const step = 100 / (5000 / 50);
            interval = setInterval(() => {
                progress -= step;
                if (progress <= 0) {
                    show = false;
                    clearInterval(interval);
                }
            }, 50);
        }
    ">

    <div class="relative overflow-hidden rounded-2xl shadow-2xl backdrop-blur-sm"
        :class="{
            'bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200': type === 'success',
            'bg-gradient-to-br from-red-50 to-rose-50 border border-red-200': type === 'error',
            'bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-200': type === 'warning',
            'bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200': type === 'info'
        }">

        <!-- Progress Bar -->
        <div class="absolute top-0 left-0 h-1 transition-all duration-100 ease-linear" :style="`width: ${progress}%`"
            :class="{
                'bg-gradient-to-r from-green-500 to-emerald-600': type === 'success',
                'bg-gradient-to-r from-red-500 to-rose-600': type === 'error',
                'bg-gradient-to-r from-yellow-500 to-amber-600': type === 'warning',
                'bg-gradient-to-r from-blue-500 to-indigo-600': type === 'info'
            }">
        </div>

        <div class="p-4 pt-5">
            <div class="flex items-start gap-4">
                <!-- Icon Container -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <!-- Success Icon -->
                        <div x-show="type === 'success'"
                            class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center ring-4 ring-green-50">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <!-- Error Icon -->
                        <div x-show="type === 'error'"
                            class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center ring-4 ring-red-50">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <!-- Warning Icon -->
                        <div x-show="type === 'warning'"
                            class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center ring-4 ring-yellow-50">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <!-- Info Icon -->
                        <div x-show="type === 'info'"
                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center ring-4 ring-blue-50">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h4 class="text-sm font-semibold mb-0.5"
                        :class="{
                            'text-green-900': type === 'success',
                            'text-red-900': type === 'error',
                            'text-yellow-900': type === 'warning',
                            'text-blue-900': type === 'info'
                        }">
                        <span x-show="type === 'success'">Success</span>
                        <span x-show="type === 'error'">Error</span>
                        <span x-show="type === 'warning'">Warning</span>
                        <span x-show="type === 'info'">Information</span>
                    </h4>
                    <p class="text-sm leading-relaxed"
                        :class="{
                            'text-green-700': type === 'success',
                            'text-red-700': type === 'error',
                            'text-yellow-700': type === 'warning',
                            'text-blue-700': type === 'info'
                        }"
                        x-text="message"></p>
                </div>

                <!-- Close Button -->
                <button @click="show = false; if(interval) clearInterval(interval)"
                    class="flex-shrink-0 rounded-lg p-1.5 transition-all duration-200 hover:scale-110"
                    :class="{
                        'text-green-400 hover:text-green-600 hover:bg-green-100': type === 'success',
                        'text-red-400 hover:text-red-600 hover:bg-red-100': type === 'error',
                        'text-yellow-400 hover:text-yellow-600 hover:bg-yellow-100': type === 'warning',
                        'text-blue-400 hover:text-blue-600 hover:bg-blue-100': type === 'info'
                    }">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>