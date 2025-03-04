<template>
<!--    <div v-if="rows.length > 0" class="card card-outline card-warning">-->
<!--        <div class="card-header">-->
<!--            <h3 class="card-title">-->
<!--                <i class="fas fa-bullhorn"></i>-->
<!--                {{ title }}-->
<!--            </h3>-->
<!--            <div class="card-tools">-->
<!--                <button type="button" class="btn btn-tool" @click="toggleCollapse" title="Collapse">-->
<!--                    <i :class="isCollapsed ? 'fas fa-plus' : 'fas fa-minus'"></i>-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div v-show="!isCollapsed" class="card-body">-->
            <div class="tab-content p-0">
                <div v-for="data in rows" :key="data.id" :class="getClass(data.Communication.icon)">
                    <h5 :class="getTextClass(data.Communication.icon)">{{ data.Communication.title }}</h5>
                    <span v-html="data.Communication.message"></span>
                </div>
            </div>
<!--        </div>-->
<!--    </div>-->
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

export default {
    name: "Communication",
    props: {
        title: {
            type: String,
            required: true
        },
    },
    setup(props) {
        const rows = ref([]);
        const isCollapsed = ref(false);

        const getClass = (key) => {
            switch (key) {
                case "Information":
                    return "callout callout-info";
                case "Neutral":
                    return "callout";
                case "Breaking news":
                    return "callout callout-warning";
                case "Tip":
                    return "callout callout-primary";
                case "Warning":
                    return "callout callout-danger";
                default:
                    return "callout";
            }
        };

        const getTextClass = (key) => {
            switch (key) {
                case "Information":
                    return "text-info";
                case "Neutral":
                    return "text-black";
                case "Breaking news":
                    return "text-warning";
                case "Tip":
                    return "text-primary";
                case "Warning":
                    return "text-danger";
                default:
                    return "text-black";
            }
        };

        const fetchCommunications = async () => {
            try {
                const response = await axios.get(process.env.MIX_API_URL+'/getcommunications');
                rows.value = response.data;
            } catch (error) {
                console.error("Failed to fetch communications:", error);
            }
        };

        const toggleCollapse = () => {
            isCollapsed.value = !isCollapsed.value;
        };

        onMounted(fetchCommunications);

        return {
            rows,
            isCollapsed,
            getClass,
            getTextClass,
            toggleCollapse,
        };
    },
};
</script>

<style scoped>
/* Ajoutez ici vos styles personnalisés si nécessaire */
</style>
