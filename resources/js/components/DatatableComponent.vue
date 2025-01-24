<script setup>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';

DataTable.use(DataTablesCore);

// Définir les props que le composant va recevoir
const props = defineProps({
    headers: Array,
    data: Array
});
</script>

<template>
    <DataTable :data="props.data" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>

            <th v-for="(header, headerIndex) in props.headers" :key="headerIndex">{{ header }} </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(row, rowIndex) in props.data" :key="rowIndex">

            <td v-for="(cell, cellIndex) in row" :key="cellIndex" v-html="cell"></td>
        </tr>
        </tbody>
    </DataTable>
</template>

<style>
@import 'datatables.net-bs5';
</style>
