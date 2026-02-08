<div x-data="{
    activeSlide: 0,
    slides: [
        { image: 'https://img.upanh.tv/2024/02/banner1.jpg', link: '#' },
        { image: 'https://img.upanh.tv/2024/02/banner2.jpg', link: '#' }
    ],
    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
}" x-init="setInterval(() => next(), 5000)" class="relative w-full overflow-hidden rounded-lg shadow-sm">
    <div class="relative h-[300px] md:h-[400px]">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0" class="absolute inset-0">
                <img :src="slide.image" class="w-full h-full object-cover">
            </div>
        </template>
    </div>

    <button @click="prev()"
        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 text-white p-2 rounded-full hover:bg-black/50">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button @click="next()"
        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 text-white p-2 rounded-full hover:bg-black/50">
        <i class="fas fa-chevron-right"></i>
    </button>

    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <div @click="activeSlide = index" :class="activeSlide === index ? 'bg-[#ee4d2d] w-6' : 'bg-white/50 w-2'"
                class="h-2 rounded-full cursor-pointer transition-all duration-300">
            </div>
        </template>
    </div>
</div>
