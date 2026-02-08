<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Thêm CSRF Token để bảo mật --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AdminHub - @yield('title')</title>

    {{-- 1. Tailwind & Custom Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            750: '#1e293b',
                            850: '#0f172a',
                            950: '#020617'
                        }
                    }
                }
            }
        }
    </script>

    {{-- 2. Chart.js (Phải đặt ở đây để các Component phía dưới nhận được) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- 3. Alpine.js (Dùng defer để không làm chậm việc load trang) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- 4. FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        /* Hiệu ứng mượt cho các thành phần chuyển động */
        .transition-all {
            transition-duration: 300ms;
        }
    </style>
</head>

<body class="bg-[#0f172a] text-slate-200" x-data="{ sidebarCollapsed: false }">
    <div class="flex h-screen overflow-hidden"> {{-- Khối cha Flex --}}

        {{-- 1. Sidebar bên trái: Cố định chiều cao h-screen --}}
        @include('admin.partials.sidebar')

        {{-- 2. Khối bên phải: Chứa Header + Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Header luôn ở trên cùng --}}
            @include('admin.partials.header')

            {{-- Main Content có thể cuộn độc lập --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 space-y-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
