<section class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
    {{-- Banner 1: Summer 2026 --}}
    <div onclick="filterCollection(event, 'summer')"
        class="relative h-[250px] md:h-[350px] rounded-xl overflow-hidden group cursor-pointer">
        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=1000"
            class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black/30 flex flex-col justify-center items-center text-white p-6">
            <h3 class="text-3xl md:text-4xl font-black uppercase tracking-tighter mb-2">Phong cách mùa hè 2026</h3>
            <p class="text-lg font-light tracking-widest opacity-90">Rực rỡ & Phóng khoáng</p>
            <button class="mt-4 px-6 py-2 bg-white text-black font-bold text-sm uppercase rounded-full">Khám phá
                ngay</button>
        </div>
    </div>

    {{-- Banner 2: Minimal Streetwear --}}
    <div onclick="filterCollection(event, 'minimal')"
        class="relative h-[250px] md:h-[350px] rounded-xl overflow-hidden group cursor-pointer">
        <img src="https://images.unsplash.com/photo-1552066344-2464c9732609?q=80&w=1000"
            class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-8 text-white">
            <h3 class="text-3xl md:text-4xl font-black uppercase leading-none">Minimal –<br>Streetwear</h3>
            <p class="mt-2 text-gray-300">Đơn giản tạo nên đẳng cấp</p>
            <div class="w-12 h-1 bg-[#ee4d2d] mt-4"></div>
        </div>
    </div>
</section>

<script>
    function filterCollection(e, slug) {
        e.preventDefault();
        const wrapper = document.getElementById('product-wrapper');
        const title = document.getElementById('current-category-name');

        wrapper.style.opacity = '0.5';
        title.innerText = 'Bộ sưu tập: ' + slug.toUpperCase();

        fetch(`/collection/${slug}?ajax=1`)
            .then(response => response.text())
            .then(html => {
                wrapper.innerHTML = html;
                wrapper.style.opacity = '1';
                window.history.pushState({}, '', `/collection/${slug}`);
            });
    }
</script>
