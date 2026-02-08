@props(['data' => []])

<div {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-sm']) }}>
    <div class="p-6 border-b border-slate-800">
        <h3 class="text-white text-lg font-semibold tracking-tight">Doanh số theo danh mục</h3>
    </div>
    <div class="p-6">
        <div class="h-[300px] w-full relative">
            <canvas id="categoryBarChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryBarChart').getContext('2d');
        const chartData = @json($data);

        // Kiểm tra nếu không có dữ liệu để tránh lỗi script
        if (!chartData || chartData.length === 0) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                // Sử dụng 'category' làm nhãn trục X
                labels: chartData.map(d => d.category),
                datasets: [{
                        label: 'Sales',
                        data: chartData.map(d => d.sales),
                        backgroundColor: '#3b82f6',
                        borderRadius: 8,
                        borderSkipped: 'bottom',
                        barPercentage: 0.7,
                        categoryPercentage: 0.6
                    },
                    {
                        label: 'Orders',
                        data: chartData.map(d => d.orders),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 8,
                        borderSkipped: 'bottom',
                        barPercentage: 0.7,
                        categoryPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                // HIỆU ỨNG MỌC LẦN LƯỢT TỪ TRÁI SANG PHẢI
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart',
                    delay: (context) => {
                        // Tạo độ trễ tăng dần dựa trên chỉ số của cột (index)
                        return context.dataIndex * 150;
                    }
                },

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            usePointStyle: true,
                            padding: 20
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
                },
                scales: {
                    y: {
                        grid: {
                            color: '#334155',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            // Thêm ký tự $ cho doanh thu nếu cần
                            callback: (value) => value.toLocaleString()
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
