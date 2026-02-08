<div class="bg-[#1e293b] border border-slate-800 rounded-lg px-6 py-4 flex flex-col md:flex-row items-center justify-between mt-6 gap-4"
    x-data="{
        // Logic tạo mảng số trang có dấu ba chấm (Y hệt React)
        getPageNumbers() {
            const pages = [];
            const maxVisible = 5;
            // Hỗ trợ cả filteredProducts (products) và filteredUsers (customers)
            const filtered = typeof filteredProducts !== 'undefined' ? filteredProducts : filteredUsers;
            const total = Math.ceil(filtered.length / itemsPerPage);

            if (total <= maxVisible) {
                for (let i = 1; i <= total; i++) pages.push(i);
            } else {
                if (currentPage <= 3) {
                    for (let i = 1; i <= 4; i++) pages.push(i);
                    pages.push('...');
                    pages.push(total);
                } else if (currentPage >= total - 2) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = total - 3; i <= total; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    pages.push(currentPage - 1);
                    pages.push(currentPage);
                    pages.push(currentPage + 1);
                    pages.push('...');
                    pages.push(total);
                }
            }
            return pages;
        }
    }">

    {{-- Bên trái - Thông tin dòng hiển thị --}}
    <div class="flex items-center gap-6">
        <p class="text-slate-400 text-sm">
            Hiển thị từ <span class="text-white font-bold" x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
            đến <span class="text-white font-bold"
                x-text="Math.min(currentPage * itemsPerPage, (typeof filteredProducts !== 'undefined' ? filteredProducts.length : filteredUsers.length))"></span>
            trong tổng số <span class="text-white font-bold"
                x-text="(typeof filteredProducts !== 'undefined' ? filteredProducts.length : filteredUsers.length)"></span>
            <span x-text="typeof filteredProducts !== 'undefined' ? 'sản phẩm' : 'người dùng'"></span>
        </p>

        {{-- Lựa chọn số dòng mỗi trang --}}
        <div class="flex items-center gap-2">
            <span class="text-slate-400 text-sm">Số dòng:</span>
            <div class="relative">
                <select x-model.number="itemsPerPage" @change="currentPage = 1; applyPagination()"
                    class="w-20 bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-1 px-2 appearance-none focus:outline-none focus:border-blue-500 transition-all text-sm cursor-pointer">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <i
                    class="fas fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
            </div>
        </div>
    </div>

    {{-- Bên phải - Nút điều hướng trang --}}
    <div class="flex items-center gap-1.5">
        {{-- Nút Quay lại --}}
        <button @click="if(currentPage > 1) { currentPage--; applyPagination(); window.scrollTo(0,0); }"
            :disabled="currentPage === 1"
            class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>

        {{-- Danh sách số trang --}}
        <template x-for="(page, index) in getPageNumbers()" :key="index">
            <div class="flex items-center">
                <template x-if="page === '...'">
                    <span class="text-slate-500 px-2 font-bold">...</span>
                </template>

                <template x-if="page !== '...'">
                    <button @click="currentPage = page; applyPagination(); window.scrollTo(0,0);"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold transition-all border"
                        :class="currentPage === page ?
                            'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-500/20' :
                            'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700 hover:text-white'"
                        x-text="page"></button>
                </template>
            </div>
        </template>

        {{-- Nút Tiếp theo --}}
        <button
            @click="if(currentPage < Math.ceil((typeof filteredProducts !== 'undefined' ? filteredProducts.length : filteredUsers.length) / itemsPerPage)) { currentPage++; applyPagination(); window.scrollTo(0,0); }"
            :disabled="currentPage === Math.ceil((typeof filteredProducts !== 'undefined' ? filteredProducts.length : filteredUsers
                .length) / itemsPerPage)"
            class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
    </div>
</div>
