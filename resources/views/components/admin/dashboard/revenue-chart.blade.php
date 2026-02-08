@props(['daily', 'weekly', 'monthly'])

<div {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-sm']) }}
    x-data="revenueChartHandler({
        daily: {{ json_encode($daily) }},
        weekly: {{ json_encode($weekly) }},
        monthly: {{ json_encode($monthly) }}
    })">

    {{-- Card Header: Tiêu đề và Nút chuyển đổi --}}
    <div class="p-6 flex flex-row items-center justify-between pb-2">
        <h3 class="text-white text-lg font-semibold tracking-tight">Revenue Analytics</h3>

        <div class="flex gap-2">
            <template x-for="p in ['daily', 'weekly', 'monthly']">
                <button @click="setPeriod(p)" type="button"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all border duration-200"
                    :class="period === p ?
                        'bg-blue-600 text-white border-blue-600 shadow-md' :
                        'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700 hover:text-white'"
                    x-text="p.charAt(0).toUpperCase() + p.slice(1)"></button>
            </template>
        </div>
    </div>

    {{-- Card Content: Biểu đồ --}}
    <div class="p-6">
        <div class="h-[300px] w-full relative">
            <canvas id="revenueLineChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('revenueChartHandler', (chartData) => ({
            period: 'daily',
            chart: null,

            init() {
                const ctx = document.getElementById('revenueLineChart').getContext('2d');

                // Tạo Linear Gradient cho đường Revenue
                const revenueGradient = ctx.createLinearGradient(0, 0, 0, 300);
                revenueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                revenueGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

                // Logic hiệu ứng vẽ từ trái sang phải
                const totalDuration = 1000; // Tổng thời gian vẽ (1 giây)
                const delayBetweenPoints = (ctx) => {
                    // Chia nhỏ thời gian delay dựa trên số lượng điểm dữ liệu
                    const dataPoints = chartData[this.period].length;
                    return ctx.index * (totalDuration / dataPoints);
                };

                const progressiveAnimation = {
                    x: {
                        type: 'number',
                        easing: 'linear',
                        duration: totalDuration / chartData[this.period].length,
                        from: NaN, // Bắt đầu từ giá trị rỗng để tạo cảm giác vẽ nối tiếp
                        delay: delayBetweenPoints
                    },
                    y: {
                        type: 'number',
                        easing: 'linear',
                        duration: totalDuration / chartData[this.period].length,
                        from: (ctx) => ctx.chart.scales.y.getPixelForValue(
                        0), // Vẽ từ trục 0 đi lên theo tiến trình ngang
                        delay: delayBetweenPoints
                    }
                };

                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData[this.period].map(d => d.name),
                        datasets: [{
                                label: 'revenue',
                                data: chartData[this.period].map(d => d.revenue),
                                borderColor: '#3b82f6',
                                backgroundColor: revenueGradient,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#3b82f6',
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'orders',
                                data: chartData[this.period].map(d => d.orders),
                                borderColor: '#8b5cf6',
                                borderWidth: 3,
                                fill: false,
                                tension: 0.4,
                                pointBackgroundColor: '#8b5cf6',
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        animations: progressiveAnimation, // Thay thế animation mặc định bằng hiệu ứng vẽ ngang
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
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
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: '#334155',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            },

            setPeriod(newPeriod) {
                this.period = newPeriod;
                const newData = chartData[this.period];

                this.chart.data.labels = newData.map(d => d.name);
                this.chart.data.datasets[0].data = newData.map(d => d.revenue);
                this.chart.data.datasets[1].data = newData.map(d => d.orders);

                // Khi cập nhật dữ liệu mới, chạy lại hiệu ứng vẽ mượt mà
                this.chart.update();
            }
        }));
    });
</script>
