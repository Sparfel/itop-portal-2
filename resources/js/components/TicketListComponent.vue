<template>

        <!-- Barre de recherche -->
        <!-- Barre de recherche avec Bootstrap 5 -->
        <div class="d-flex justify-content-end">
            <div class="input-group input-group-sm w-auto mb-3">

                <input
                    v-model="searchQuery"
                    type="text"
                    class="form-control"
                    placeholder="Rechercher..."
                    aria-label="Rechercher"
                    aria-describedby="basic-addon1"
                />
                <span class="input-group-text" id="basic-addon1">
                    <i class="fa fa-search"></i> <!-- Icône de loupe -->
                </span>
            </div>
        </div>
        <EasyDataTable
            :headers="headers"
            :items="paginatedItems"
            :sort-by="sortBy"
            :sort-type="sortType"
            :server-items-length="totalItems"
            :loading="loading"
            :buttons-pagination="buttonsPagination"
            :alternating="alternating"
            :multi-sort="multiSort"
            :theme-color="themeColor"
            :server-options="serverOptions"
            @update:sortBy="sortChange"
            @update:sortType="sortChange"
            @update:server-options="handleServerOptionsUpdate"
            @click-row="showRow"

        >
            <template #loading>
                <img
                    src="https://i.pinimg.com/originals/94/fd/2b/94fd2bf50097ade743220761f41693d5.gif"
                    style="width: 100px; height: 80px;"
                />
            </template>
            <!-- Slot pour la colonne Status -->
            <template v-slot:item-status="{ status }">
                <span v-html="getStatusIcon(status)"></span>
            </template>
        </EasyDataTable>

    row clicked:<br />
    <div id="row-clicked"></div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from 'axios';

export default {
    components: {
        EasyDataTable,
    },
    props: {
        themeColor: {
            type: String,
            default: '#EA7D1E', // Valeur par défaut
        },
        headers: {
            type: Array,
            required: true,
        },
        items: {
            type: Array,
            required: true,
        },
        buttonsPagination: {
            type: Boolean,
            default: false,
        },
        alternating: {
            type: Boolean,
            default: false,
        },
        multiSort: {
            type: Boolean,
            default: false,
        },
    },
    setup() {
        //console.log("Le composant a été monté");
        const headers = ref([
            { text: 'Ref', value: 'ref', sortable: true },
            { text: 'Status', value: 'status', sortable: true },
            { text: 'Title', value: 'title', sortable: false },
            { text: 'Start Date', value: 'start_date', sortable: true },
            { text: 'Caller', value: 'caller_id_friendlyname', sortable: false },
            { text: 'Agent', value: 'agent_id_friendlyname', sortable: false },
        ]);

        const items = ref([]);
        const searchQuery = ref(""); // Variable pour la recherche
        const sortBy = ref('id');
        const sortType = ref('desc');
        const serverOptions = ref({
             page: 1,
             rowsPerPage: 5,
        });

        const loading = ref(false);
        const totalItems = ref(0);

        // Filtre les items selon la requête de recherche
        const filteredItems = computed(() => {
            return items.value.filter((item) => {
                // Recherche sur toutes les colonnes (tu peux adapter en fonction des colonnes pertinentes)
                return Object.values(item).some(val =>
                    String(val).toLowerCase().includes(searchQuery.value.toLowerCase())

                );
            });
        });

        // Propriété calculée pour les éléments paginés côté client
        const paginatedItems = computed(() => {
            const start = (serverOptions.value.page - 1) * serverOptions.value.rowsPerPage;
            const end = start + serverOptions.value.rowsPerPage;
            //console.log("Page:", serverOptions.value.page, "Lignes par page:", serverOptions.value.rowsPerPage);

            return filteredItems.value.slice(start, end);
        });

        const fetchData = async () => {
            loading.value = true;
            try {
                const url = `https://portal.debian/listrequests`;
                const response = await axios.get(url);
                items.value = response.data;
                totalItems.value = items.value.length; // Mise à jour du nombre total d'éléments
            } catch (error) {
                console.error('Erreur lors de la récupération des données :', error);
            } finally {
                loading.value = false;
            }
        };

        const sortChange = ({ sortBy, sortType }) => {
            items.value.sort((a, b) => {
                if (sortType === 'asc') {
                    return a[sortBy] > b[sortBy] ? 1 : -1;
                } else if (sortType === 'desc') {
                    return a[sortBy] < b[sortBy] ? 1 : -1;
                }
                return 0;
            });
        };

        const handleServerOptionsUpdate = (newServerOptions) => {
            //console.log("Options de pagination mises à jour : ", newServerOptions);
            serverOptions.value = newServerOptions;

        };

        const showRow = (item) => {
            document.getElementById('row-clicked').innerHTML = JSON.stringify(item);

        };


        onMounted(() => {

            fetchData();

        });

        return {
            headers,
            paginatedItems,
            sortBy,
            sortType,
            loading,
            totalItems,
            serverOptions,

            sortChange,
            handleServerOptionsUpdate,
            searchQuery, // Ajout de la variable de recherche
            filteredItems, // Les items filtrés
        };
    },

    methods: {
        showRow(item){
            document.getElementById('row-clicked').innerHTML = JSON.stringify(item);

        },

        getStatusIcon(status) {
            try{
                switch (status) {
                    case 'new':
                        return '<i class="fa-solid fa-plus-circle text-danger" title="New"></i>';
                    case 'assigned':
                        return '<i class="fa-solid fa-user-check text-info" title="Assigned"></i>';
                    case 'pending':
                        return '<i class="fa-solid fa-hourglass-half text-warning" title="Pending"></i>';
                    case 'resolved':
                        return '<i class="fa-solid fa-thumbs-up text-success" title="Solved"></i>';
                    case 'closed':
                        return '<i class="fa-solid fa-check-circle text-secondary" title="Closed"></i>';
                    default:
                        return status;
                }
            } catch (error) {
                console.error('Erreur lors de l\'accès à status:', error);
                return 'Erreur'; // Optionnel, selon ce que tu veux afficher en cas d'erreur
            }
        },
    },
};
</script>

<style scoped>
/* Placer l'input en haut à droite */
.d-flex {
    position: absolute;
    top: 10px;
    right: 10px;
}

/* Optionnel : ajuster la taille du champ pour qu'il soit plus petit */
input[type="text"] {
    max-width: 200px; /* Ajuste selon ta préférence */
}
</style>
