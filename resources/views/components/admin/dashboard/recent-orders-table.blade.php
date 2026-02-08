@props(['orders' => []])

<div {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-sm']) }}>
    <div class="p-6 border-b border-slate-800 flex items-center justify-between">
        <h3 class="text-white text-lg font-semibold">Recent Orders</h3>
        <a href="{{ route('admin.orders.index') }}"
            class="text-blue-500 hover:text-blue-400 text-sm font-medium transition-colors">
            View All Orders
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-850/50 border-b border-slate-800">
                    <th class="px-6 py-4 text-slate-400 font-medium text-xs uppercase tracking-wider">Order Code</th>
                    <th class="px-6 py-4 text-slate-400 font-medium text-xs uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-slate-400 font-medium text-xs uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-slate-400 font-medium text-xs uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-slate-400 font-medium text-xs uppercase tracking-wider text-right">Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse ($orders as $order)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        {{-- Sử dụng object notation -> thay vì array [] cho Eloquent Model --}}
                        <td class="px-6 py-4 text-sm font-medium text-blue-400">
                            #{{ $order->order_code }}
                        </td>
                        <td class="px-6 py-4 text-sm text-white">
                            {{ $order->user->name ?? 'Guest' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-white">
                            {{ number_format($order->total_amount) }}₫
                        </td>
                        <td class="px-6 py-4">
                            {{-- Tận dụng Enum để lấy màu và nhãn --}}
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $order->status->badgeClass() }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-slate-500 hover:text-white transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 text-sm">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
