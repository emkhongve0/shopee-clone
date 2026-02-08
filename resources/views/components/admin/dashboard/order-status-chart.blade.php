@props(['data' => []])

<div {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-sm']) }}>
    {{-- Header của Card --}}
    <div class="p-6 pb-2">
        <h3 class="text-white text-lg font-semibold tracking-tight">Phân tích trạng thái đơn hàng</h3>
    </div>

    {{-- Nội dung Card --}}
    <div class="p-6">
        {{-- Vùng chứa biểu đồ --}}
        <div class="h-[280px] w-full relative mb-6">
            <canvas id="orderStatusPieChart"></canvas>
        </div>

        {{-- Grid hiển thị thông số chi tiết --}}
        <div class="grid grid-cols-2 gap-3 mt-4">
            @if (isset($data['details']) && count($data['details']) > 0)
                @foreach ($data['details'] as $item)
                    <div
                        class="bg-slate-800/50 rounded-lg p-3 border border-slate-800/30 hover:bg-slate-800 transition-colors">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                            <span class="text-slate-400 text-sm capitalize">{{ $item['label'] }}</span>
                        </div>
                        <p class="text-white text-xl font-bold mt-1 tracking-tight">{{ $item['count'] }}</p>
                    </div>
                @endforeach
            @else
                <div class="col-span-2 text-center py-8">
                    <p class="text-slate-400">Chưa có dữ liệu đơn hàng</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('orderStatusPieChart').getContext('2d');
        const chartDataObj = @json($data);

        // Nếu không có dữ liệu
        if (!chartDataObj.labels || chartDataObj.labels.length === 0) {
            return;
        }

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartDataObj.labels,
                datasets: [{
                    data: chartDataObj.data,
                    backgroundColor: chartDataObj.backgroundColor,
                    borderWidth: 2,
                    borderColor: '#1e293b',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: false,
                    duration: 2000,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    });
</script>
