<x-filament-panels::page>
    <section class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="grid flex-1 gap-y-1">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Visualisasi Clustering Pelanggan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Berdasarkan total belanja dan jumlah item dibeli
                    </p>
                </div>
            </div>
        </header>

        <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
            <div class="fi-section-content p-6">
                <div wire:ignore>
                    <canvas id="clusteringChart" style="height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dataPoints = @json($dataPoints);

        const clusters = {};
        dataPoints.forEach(point => {
            if (!clusters[point.cluster]) clusters[point.cluster] = [];
            clusters[point.cluster].push({ x: point.x, y: point.y, label: point.name });
        });

        const colors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)'];

        const datasets = Object.entries(clusters).map(([cluster, points], index) => ({
            label: cluster,
            data: points,
            backgroundColor: colors[index % colors.length]
        }));

        new Chart(document.getElementById('clusteringChart'), {
            type: 'scatter',
            data: { datasets },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.raw.label} (Belanja: ${ctx.raw.x}, Item: ${ctx.raw.y})`
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white',
                    },
                    legend: {
                        labels: {
                            color: 'white', // <<< legend text color
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Total Belanja',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Item',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    </script>
</x-filament-panels::page>