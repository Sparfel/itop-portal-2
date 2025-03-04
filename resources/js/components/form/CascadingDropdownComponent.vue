<script setup>
import { ref, watch, onMounted, defineExpose , nextTick} from "vue";
import axios from "axios";

// Références réactives
const organizations = ref([]);
const locations = ref([]);
const selectedOrganization = ref("");
const selectedLocation = ref("");
const loadingLocations = ref(false);
// Flag pour éviter que le watcher ne se déclenche lors de la mise à jour de l'organisation et de la localisation
let skipWatcher = false;

const emit = defineEmits(["update:selectedOrganization", "update:selectedLocation"]);

// Récupération des organisations
const getOrganizations = () => {
    axios.get("/getOrganizations").then((res) => {
        organizations.value = res.data;
    });
};

// Récupération des localisations en fonction de l'organisation sélectionnée
const getOrganizationLocations = () => {
    if (!selectedOrganization.value) {
        locations.value = [];
        selectedLocation.value = "";
        return;
    }

    loadingLocations.value = true;

    axios.get("/getOrganizationLocations", { params: { organization: selectedOrganization.value } })
        .then((res) => {
            locations.value = Array.isArray(res.data) ? res.data : []; // Vérifie que res.data est un tableau
            if (!locations.value.some(loc => loc.id === selectedLocation.value)) {
                selectedLocation.value = "";
            }
        })
        .finally(() => {
            loadingLocations.value = false;
        });
};


// Watchers pour mettre à jour les valeurs
watch(selectedOrganization, (newValue) => {
    if (skipWatcher) return; // Ne rien faire si skipWatcher est vrai (mise à jour externe)
    emit("update:selectedOrganization", newValue);
    getOrganizationLocations();

});

watch(selectedLocation, (newValue) => {
    emit("update:selectedLocation", newValue);
});

// Méthode exposée pour mettre à jour les valeurs depuis l'extérieur
const updateValues = (orgId, locId, locList) => {
    skipWatcher = true; // Désactive temporairement le watcher
    selectedOrganization.value = orgId;
    locations.value = locList || [];
    console.log('localisation '+locId);
    selectedLocation.value = locId;
    // Réactive le watcher après un délai
    nextTick(() => {
        skipWatcher = false;
    });

};

// Expose cette méthode pour qu'on puisse l'appeler de l'extérieur
defineExpose({ updateValues });

// Charger les données au montage
onMounted(() => {
    getOrganizations();
});
</script>

<template>

    <div class="row">
        <div class="form-group  col-sm-6">
            <label>{{ ('Organization') }}</label>

                <div class="input-group">
                    <div id="organizationLoader" class="input-group-prepend"></div>
                    <select v-model="selectedOrganization" class="form-control" id="organization">
                        <option value="">-- Select an Organization --</option>
                        <option v-for="org in organizations" :key="org.id" :value="org.id">
                            {{ org.name }}
                        </option>
                    </select>
                </div>

        </div>

        <div class="form-group col-sm-6">
            <label>{{ ('Location') }}</label>

                <div class="input-group">
                    <div id="locationLoader" class="input-group-prepend"></div>
                        <select v-model="selectedLocation" :disabled="!selectedOrganization" class="form-control" id="location">
                            <option value="">-- Select a Location --</option>
                            <option v-for="loc in locations" :key="loc.id" :value="loc.id">
                                {{ loc.name }}
                            </option>
                        </select>
                        <span v-if="loadingLocations">
                            <img style="height:24px;" src="/img/sm_loader.gif" alt="Loading..." />
                        </span>
                </div>

        </div>
    </div>

</template>
