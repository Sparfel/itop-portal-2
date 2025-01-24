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
        </div>
    </div>
    <div v-else>
        <canvas :id="uuid"></canvas>
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

        // Convertir les données en pourcentages
        const convertToPercentages = (data) => {
            const total = data.reduce((sum, value) => sum + value, 0);
            return data.map((value) => ((value / total) * 100).toFixed(1)); // 1 décimales
        };

        // Initialiser les données
        const rawData = ref(JSON.parse(props.dataProp));
        const chartData = ref(convertToPercentages(rawData.value));
        const parsedLabels = ref(JSON.parse(props.labels));

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
                            label: '',
                            data: chartData.value,
                            backgroundColor: [
                                '#EA7D1E', // $itop (orange)
                                '#007bff', // $blue
                                '#ffc107', // $yellow
                                '#28a745', // $green
                                '#6f42c1', // $purple
                                '#20c997', // $teal
                                '#dc3545', // $red
                                '#e83e8c', // $pink
                                '#6610f2'  // $indigo,
                            ],
                        },
                    ],
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                            labels: {
                                color: 'black',
                                font: 'Roboto',
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 8,
                            },
                        },
                        datalabels: {
                            formatter: (value) => `${value}%`, // Affiche directement les pourcentages
                            color: '#fff',
                        },
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        };

        // Regarder les changements sur `dataProp`
        watch(
            () => props.dataProp,
            (newData) => {
                rawData.value = JSON.parse(newData);
                chartData.value = convertToPercentages(rawData.value); // Reconversion en pourcentages
                nextTick(() => renderChart());
            }
        );

        // Initialisation du graphique au montage
        onMounted(() => {
            renderChart();
        });

        return {
            uuid,
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
</style>
