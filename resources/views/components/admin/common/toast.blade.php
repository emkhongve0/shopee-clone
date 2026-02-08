<div x-data="{
    toasts: [],
    addToast(message, type = 'success') {
        const id = Date.now();
        this.toasts.push({ id, message, type, visible: true });
        setTimeout(() => {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }, 3000);
    }
}" @notify.window="addToast($event.detail.message, $event.detail.type)"
    class="fixed bottom-5 right-5 z-[200] flex flex-col gap-3">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-md min-w-[300px]"
            :class="{
                'bg-emerald-500/10 border-emerald-500/20 text-emerald-500': toast.type === 'success',
                'bg-red-500/10 border-red-500/20 text-red-500': toast.type === 'error'
            }">

            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-white/10">
                <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
            </div>

            <div class="flex-1">
                <p class="text-sm font-bold" x-text="toast.type === 'success' ? 'Thành công' : 'Thất bại'"></p>
                <p class="text-xs opacity-80" x-text="toast.message"></p>
            </div>

            <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                class="opacity-50 hover:opacity-100 transition-opacity">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </template>
</div>
