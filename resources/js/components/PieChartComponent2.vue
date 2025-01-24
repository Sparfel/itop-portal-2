<template>
    <div v-if="card" class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie"></i>
                {{ title }}
            </h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="max-height:400px; max-width:400px; margin: auto;">
                <canvas :id="uuid"></canvas>
            </div>
            <div class="custom-legend" v-html="customLegend"></div>
        </div>
    </div>
    <div v-else>
        <canvas :id="uuid"></canvas>
        <div class="custom-legend" v-html="customLegend"></div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, nextTick } from 'vue';
import { Chart } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(ChartDataLabels);

let uuidCounter = 0;

export default defineComponent({
    name: 'PieChart',
    props: {
        title: {
            type: String,
            required: false,
        },
        labels: {
            type: String,
            required: true,
        },
        dataProp: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: 'pie',
        },
        card: {
            type: Boolean,
            default: true,
        },
    },
    setup(props) {
        const uuid = ref(`chart-${uuidCounter++}`);
        const chartInstance = ref(null);
        const chartData = ref(JSON.parse(props.dataProp));
        const parsedLabels = ref(JSON.parse(props.labels));
        const customLegend = ref('');

        const renderChart = () => {
            if (chartInstance.value) {
                chartInstance.value.destroy();
            }

            const ctx = document.getElementById(uuid.value).getContext('2d');
            chartInstance.value = new Chart(ctx, {
                type: props.type,
                data: {
                    labels: parsedLabels.value,
                    datasets: [
                        {
                            data: chartData.value,
                            backgroundColor: [
                                '#e7b042', '#72b9d6', '#af007c', '#666666', '#a2c037', '#d2767d',
                                '#FFAEBC', '#008ddd', '#00ab94', '#85727c', '#00bbfa', '#54424b', '#efefdc',
                            ],
                        },
                    ],
                },
                options: {
                    plugins: {
                        legend: {
                            display: false, // Désactiver la légende par défaut
                        },
                        datalabels: {
                            formatter: (value, ctx) => `${value}%`,
                            color: '#fff',
                        },
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });

            // Générer la légende personnalisée
            customLegend.value = generateCustomLegend();
        };

        const generateCustomLegend = () => {
            const colors = chartInstance.value.data.datasets[0].backgroundColor;
            const labels = chartInstance.value.data.labels;
            let legendHtml = '<ul class="chart-legend">';

            labels.forEach((label, index) => {
                legendHtml += `
          <li>
            <i class="far fa-circle" style="color: ${colors[index]}; margin-right: 8px;"></i>
            ${label}
          </li>`;
            });

            legendHtml += '</ul>';
            return legendHtml;
        };

        watch(
            () => props.dataProp,
            (newData) => {
                chartData.value = JSON.parse(newData);
                nextTick(() => renderChart());
            }
        );

        onMounted(() => {
            renderChart();
        });

        return {
            uuid,
            customLegend,
        };
    },
});
</script>

<style scoped>
.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.chart-legend {
    list-style: none;
    display: flex;
    flex-direction: column;
    padding: 0;
    margin: 10px 0;
}

.chart-legend li {
    display: flex;
    align-items: center;
    font-size: 14px;
}
</style>
