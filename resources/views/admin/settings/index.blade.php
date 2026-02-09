@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống Pro')

@section('content')
    <div class="p-6 min-h-screen" x-data="{
        activeCategory: 'general',
        {{-- Đồng bộ tiêu đề động dựa trên tab đang chọn --}}
        get currentTitle() {
            const titles = {
                general: 'Cài đặt chung',
                store: 'Thông tin cửa hàng',
                users: 'Phân quyền người dùng',
                security: 'Bảo mật hệ thống',
                notifications: 'Cấu hình thông báo',
                payments: 'Cổng thanh toán',
                shipping: 'Vận chuyển & Logistics',
                integrations: 'Tích hợp API',
                appearance: 'Giao diện & UI',
                localization: 'Bản địa hóa',
                backup: 'Sao lưu dữ liệu',
                advanced: 'Cấu hình nâng cao'
            };
            return titles[this.activeCategory] || 'Cài đặt';
        }
    }">

        {{-- 1. Master Header --}}
        <div class="mb-10 space-y-2">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-user-cog text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white uppercase tracking-tighter" x-text="currentTitle"></h1>
                    <div class="flex items-center gap-2 text-slate-500 text-xs font-bold uppercase tracking-widest">
                        <span>Hệ thống</span>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <span class="text-blue-400" x-text="currentTitle"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Main Layout Architecture --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- SIDEBAR: Thành phần thứ 13 --}}
            <div class="w-full lg:w-72 sticky top-6">
                <x-admin.settings.settings-sidebar />
            </div>

            {{-- CONTENT PANEL: Chứa 12 linh kiện con --}}
            <div class="flex-1 w-full min-w-0">
                <div class="relative transition-all duration-500">

                    {{-- Tab 1: Chung --}}
                    <div x-show="activeCategory === 'general'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.general-settings />
                    </div>

                    {{-- Tab 2: Cửa hàng --}}
                    <div x-show="activeCategory === 'store'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.store-settings />
                    </div>

                    {{-- Tab 3: Phân quyền --}}
                    <div x-show="activeCategory === 'users'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.user-role-settings />
                    </div>

                    {{-- Tab 4: Bảo mật --}}
                    <div x-show="activeCategory === 'security'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.security-settings />
                    </div>

                    {{-- Tab 5: Thông báo --}}
                    <div x-show="activeCategory === 'notifications'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.notification-settings />
                    </div>

                    {{-- Tab 6: Thanh toán --}}
                    <div x-show="activeCategory === 'payments'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.payment-settings />
                    </div>

                    {{-- Tab 7: Vận chuyển --}}
                    <div x-show="activeCategory === 'shipping'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.shipping-settings />
                    </div>

                    {{-- Tab 8: Tích hợp --}}
                    <div x-show="activeCategory === 'integrations'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.integration-settings />
                    </div>

                    {{-- Tab 9: Giao diện --}}
                    <div x-show="activeCategory === 'appearance'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.appearance-settings />
                    </div>

                    {{-- Tab 10: Bản địa hóa --}}
                    <div x-show="activeCategory === 'localization'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.localization-settings />
                    </div>

                    {{-- Tab 11: Sao lưu --}}
                    <div x-show="activeCategory === 'backup'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.backup-settings />
                    </div>

                    {{-- Tab 12: Nâng cao --}}
                    <div x-show="activeCategory === 'advanced'" x-transition:enter="transition duration-300" x-cloak>
                        <x-admin.settings.advanced-settings />
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Style bổ sung để ẩn các thành phần chưa load (tránh nháy giao diện) --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
@endsection
