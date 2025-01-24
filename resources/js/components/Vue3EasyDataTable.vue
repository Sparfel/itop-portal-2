<template>

    <EasyDataTable
        :headers="headers"
        :items="items"
        :sort-by="sortBy"
        :sort-type="sortType"
        v-model:server-options="serverOptions"
        :rows-per-page-options="[5, 10, 15]"

        >

    </EasyDataTable>
</template>

<script lang="ts" setup>
import EasyDataTable, { Header, Item } from "vue3-easy-data-table";
import {ref} from "vue";

const sortBy: string[] = ["ref", "id"];
const sortType: SortType[] = ["desc", "asc"];


</script>

<style scoped>

</style>
