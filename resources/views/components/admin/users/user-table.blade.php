@props(['users' => []])

<div class="bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-lg">
    {{-- Thanh thao tác hàng loạt (Bulk Actions Bar) --}}
    <div x-show="selectedUsers.length > 0" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-blue-600/10 border-b border-blue-500/20 px-6 py-3 flex items-center justify-between" x-cloak>
        <p class="text-blue-400 text-sm font-medium">
            <span x-text="selectedUsers.length"></span> người dùng đã được chọn
        </p>
        <div class="flex items-center gap-2">
            <button
                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95">
                <i class="fas fa-file-export mr-1"></i> Xuất phần đã chọn
            </button>
            <button
                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95">
                <i class="fas fa-trash-alt mr-1"></i> Xóa phần đã chọn
            </button>
        </div>
    </div>

    {{-- Bảng dữ liệu --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-800 bg-slate-900/50">
                    <th class="py-4 px-6 w-12 text-center">
                        <input type="checkbox" @click="toggleSelectAll"
                            :checked="selectedUsers.length === paginatedUsers.length && paginatedUsers.length > 0"
                            class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900">
                    </th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Người dùng</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Email</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Vai trò</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Trạng thái</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Đơn hàng</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider">Tổng chi</th>
                    <th class="py-4 px-4 text-slate-400 font-semibold text-xs uppercase tracking-wider text-center">Ngày
                        gia nhập</th>
                    <th class="py-4 px-6 text-slate-400 font-semibold text-xs uppercase tracking-wider text-right">Thao
                        tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <template x-for="user in paginatedUsers" :key="user.id">
                    <tr class="hover:bg-slate-800/30 transition-all cursor-pointer group" @click="viewUser(user)">
                        {{-- Checkbox chọn từng dòng --}}
                        <td class="py-4 px-6 text-center" @click.stop>
                            <input type="checkbox" :value="user.id" x-model="selectedUsers"
                                class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900">
                        </td>

                        {{-- Thông tin người dùng --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg border-2 border-slate-700">
                                    <span class="text-white font-bold text-xs"
                                        x-text="user.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()"></span>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <p class="text-white font-bold text-sm truncate" x-text="user.name"></p>
                                    <p class="text-slate-500 text-xs truncate" x-text="user.phone"></p>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="py-4 px-4 text-slate-300 text-sm italic" x-text="user.email"></td>

                        {{-- Vai trò (Badges) --}}
                        <td class="py-4 px-4 text-xs font-bold uppercase tracking-tighter">
                            <span class="px-2.5 py-0.5 rounded-full border"
                                :class="{
                                    'bg-purple-500/10 text-purple-500 border-purple-500/20': user.role === 'admin',
                                    'bg-blue-500/10 text-blue-500 border-blue-500/20': user.role === 'staff',
                                    'bg-slate-700/50 text-slate-400 border-slate-600': user.role === 'customer'
                                }"
                                x-text="user.role"></span>
                        </td>

                        {{-- Trạng thái (Badges) --}}
                        <td class="py-4 px-4 text-xs font-bold uppercase tracking-tighter">
                            <span class="px-2.5 py-0.5 rounded-full border"
                                :class="{
                                    'bg-green-500/10 text-green-500 border-green-500/20': user.status === 'active',
                                    'bg-orange-500/10 text-orange-500 border-orange-500/20': user.status === 'inactive',
                                    'bg-red-500/10 text-red-500 border-red-500/20': user.status === 'banned'
                                }"
                                x-text="user.status === 'active' ? 'Hoạt động' : (user.status === 'inactive' ? 'Chờ' : 'Đã khóa')"></span>
                        </td>

                        {{-- Số lượng đơn hàng --}}
                        <td class="py-4 px-4">
                            <div class="flex flex-col">
                                <span class="text-white font-bold text-sm" x-text="user.orders"></span>
                                <span class="text-slate-500 text-[10px] uppercase">đơn hàng</span>
                            </div>
                        </td>

                        {{-- Tổng chi tiêu --}}
                        <td class="py-4 px-4 text-blue-400 font-bold text-sm" x-text="user.totalSpent"></td>

                        {{-- Ngày gia nhập --}}
                        <td class="py-4 px-4 text-center text-slate-500 text-xs font-medium" x-text="user.createdAt">
                        </td>

                        {{-- Thao tác (Dropdown) --}}
                        <td class="py-4 px-6 text-right" @click.stop>
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open"
                                    class="text-slate-500 hover:text-white p-1.5 hover:bg-slate-700 rounded-lg transition-all">
                                    <i class="fas fa-ellipsis-v text-sm"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden"
                                    x-cloak>
                                    <button @click="viewUser(user); open = false"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left">
                                        <i class="far fa-eye w-5 text-blue-500"></i> Xem chi tiết
                                    </button>
                                    <button @click="viewUser(user); open = false"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left border-t border-slate-700/50">
                                        <i class="far fa-edit w-5 text-green-500"></i> Sửa thành viên
                                    </button>
                                    <button @click="deleteUser(user)"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-all text-left border-t border-slate-700/50">
                                        <i class="far fa-trash-alt w-5 text-red-500"></i> Xóa thành viên
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
