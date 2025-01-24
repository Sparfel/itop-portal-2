<template>
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i>
                {{ title }}
            </h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="max-height:400px; max-width:400px; margin: auto;">
                <canvas ref="barCanvas"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables, ChartDataLabels);

export default defineComponent({
    props: {
        title: String,
        labels: String,
        dataProp: String
    },
    setup(props) {
        const barCanvas = ref(null);

        const chartDataSet = computed(() => {
            const json = JSON.parse(props.dataProp);
            const colors = ['#e7b042', '#72b9d6', '#af007c', '#666666', '#a2c037', '#d2767d', '#e5e5e5'];
            return json.map((obj, i) => ({
                label: 'Ticket ' + obj.status,
                backgroundColor: colors[i % colors.length],
                data: obj.datas,
                borderWidth: 1
            }));
        });

        const renderChart = () => {
            if (barCanvas.value) {
                new Chart(barCanvas.value.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: JSON.parse(props.labels),
                        datasets: chartDataSet.value
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                display: false
                            },
                            title: {
                                display: false
                            }
                        },
                        scales: {
                            x: { stacked: true },
                            y: { stacked: true }
                        }
                    }
                });
            }
        };

        onMounted(() => {
            renderChart();
        });

        watch(() => props.dataProp, renderChart);

        return { barCanvas };
    }
});
</script>
