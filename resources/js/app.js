import './bootstrap';


/* For VueJS */
import { createApp } from 'vue';

import DatatableComponent from './components/DatatableComponent.vue';
import TestComponent from './components/TestComponent.vue';
import BarChartComponent from './components/BarChartComponent.vue';
import PieChartComponent from './components/PieChartComponent.vue';

//import Vue3EasyDataTable from 'vue3-easy-data-table';
//import Vue3EasyDataTable from './components/Vue3EasyDataTable.vue';
import 'vue3-easy-data-table/dist/style.css';

import TicketList from './components/TicketListComponent.vue';
import TicketList2 from './components/TicketList2Component.vue';

//Forms
import ButtonsRequest from './components/form/ButtonsComponent.vue'
import LocationList from './components/form/LocationComponent.vue'

//import basic from './components/basic.vue'; // Importez votre composant personnalisé
import 'vue3-easy-data-table/dist/style.css'; // Importez les styles de vue3-easy-data-table



const app = createApp({
    data() {
        return {
            message: 'Mon message'
        }
    }
});



app.component('test-component', TestComponent);
app.component('bar-chart', BarChartComponent);
app.component('pie-chart', PieChartComponent);
//app.component('vue-datatable', Vue3EasyDataTable);
app.component('datatable-component', DatatableComponent);
app.component('ticket-list', TicketList);
app.component('ticket-list2', TicketList2);

app.component('buttons-request',ButtonsRequest);
app.component('location',LocationList);

//app.component('dashboard-tickets', basic);


app.mount('#app');
