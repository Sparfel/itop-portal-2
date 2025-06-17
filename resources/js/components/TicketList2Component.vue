
<template>

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
    <div>
    <EasyDataTable
        :headers="headers"
        :items="filteredItems"
        :sort-by="sortBy"
        :sort-type="sortType"
        :rows-per-page="10"
        buttons-pagination
        :theme-color="themeColor"
        alternating
        multi-sort
        :rows-items="[10,25,50]"
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
    </div>
</template>

<script>
import {onMounted, computed, ref} from 'vue';
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from "axios";

export default {
    components: {
        EasyDataTable,
    },
    props: {
        themeColor: {
            type: String,
            default: '#28b4e6', // Valeur par défaut
        }
    },
   setup() {
        const items = ref([]);
        const totalItems = ref(0);
        const loading = ref(false);
        const sortBy = ref("ref");
        const sortType = ref("desc");
        const headers = ref([
            { text: 'Ref', value: 'ref', sortable: true },
            { text: 'Status', value: 'status', sortable: true },
            { text: 'Title', value: 'title', sortable: true },
            { text: 'Start Date', value: 'start_date', sortable: true },
            { text: 'Caller', value: 'caller_id_friendlyname', sortable: true },
            { text: 'Agent', value: 'agent_id_friendlyname', sortable: true },
        ]);


       const searchQuery = ref(""); // Variable pour la recherche
       // Filtre les items selon la requête de recherche
       const filteredItems = computed(() => {
           return items.value.filter((item) => {
               // Recherche sur toutes les colonnes (tu peux adapter en fonction des colonnes pertinentes)
               return Object.values(item).some(val =>
                   String(val).toLowerCase().includes(searchQuery.value.toLowerCase())

               );
           });
       });



        const fetchData = async () => {
            console.log('Objet monté from setup');
            loading.value = true;
            try {
                // const url = `https://portal.debian/listrequests`;
                const url = '/listrequests';
                const response = await axios.get(url);
                items.value = response.data;
                //console.log(items);
                totalItems.value = items.value.length; // Mise à jour du nombre total d'éléments
            } catch (error) {
                console.error('Erreur lors de la récupération des données :', error);
            } finally {
                loading.value = false;
            }
        };
        // Appeler fetchData lors du montage du composant
        onMounted(() => {
            console.log('OnMounted');
            fetchData();
        });
        return {
            headers,
            items,
            sortBy,
            sortType,
            loading,
            searchQuery, // Ajout de la variable de recherche
            filteredItems, // Les items filtrés
        };

    },

    methods: {
        showRow(item){
            //document.getElementById('row-clicked').innerHTML = JSON.stringify(item);
            console.log(item);
            console.log('/openedrequest/'+item.id);
            window.location.href = '/openedrequest/'+item.id;
        },
        getStatusIcon(status) {
            try {
                switch (status) {
                    case 'new':
                        // Nouveau ticket
                        return '<i class="fa-solid fa-comment-dots text-danger" title="New"></i>';
                    case 'assigned':
                        // Ticket assigné
                        return '<i class="fa-solid fa-user-check text-info" title="Assigned"></i>';
                    case 'pending':
                        // En attente
                        return '<i class="fa-solid fa-hourglass-half text-warning" title="Pending"></i>';
                    case 'resolved':
                        // Résolu
                        return '<i class="fa-regular fa-thumbs-up text-success" title="Resolved"></i>';
                    case 'closed':
                        // Fermé
                        return '<i class="fa-solid fa-lock text-secondary" title="Closed"></i>';
                    case 'escalated_tto':
                        // Escalade liée au temps pour prise en charge (TTO)
                        return '<i class="fa-solid fa-hourglass-start text-warning" title="Escalated TTO"></i>';
                    case 'escalated_ttr':
                        // Escalade liée au temps pour résolution (TTR)
                        return '<i class="fa-solid fa-hourglass-end text-danger" title="Escalated TTR"></i>';
                    case 'escalated':
                        // Escalation générale
                        return '<i class="fa-solid fa-arrow-up text-danger" title="Escalated"></i>';
                    case 'waiting_for_approval':
                        // En attente d'approbation
                        return '<i class="fa-solid fa-clock text-info" title="Waiting for Approval"></i>';
                    case 'rejected':
                        // Rejeté
                        return '<i class="fa-solid fa-times-circle text-danger" title="Rejected"></i>';
                    case 'approved':
                        // Approuvé
                        return '<i class="fa-solid fa-check-circle text-success" title="Approved"></i>';
                    default:
                        // Statut non défini
                        return htmlspecialchars($status);
                }
            } catch (error) {
                // Gestion des erreurs
                console.error('Erreur lors de l\'accès à status:', error);
                return '<i class="fa-solid fa-exclamation-triangle text-danger" title="Error"></i>';
            }

            // try{
            //     switch (status) {
            //         case 'new':
            //             return ' <i class="fa-solid fa-regular fa-comment-dots text-danger" title="New"></i>';
            //         case 'assigned':
            //             return '<i class="fa-solid fa-user-check text-info" title="Assigned"></i>';
            //         case 'pending':
            //             return '<i class="fa-solid fa-hourglass-half text-warning" title="Pending"></i>';
            //         case 'resolved':
            //             return '<i class="fa-regular fa-thumbs-up text-success" title="Solved"></i>';
            //         case 'closed':
            //             return '<i class="fa-solid fa-check-circle text-secondary" title="Closed"></i>';
            //         default:
            //             return status;
            //     }
            // } catch (error) {
            //
            //     return 'Erreur'; // Optionnel, selon ce que tu veux afficher en cas d'erreur
            // }
        },

    },
    mounted(){
        console.log('Objet monté');

    },
    created() {
        console.log('Objet créé');
    }
}
</script>

<style scoped>
/* Vous pouvez ajouter des styles ici si nécessaire */
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
