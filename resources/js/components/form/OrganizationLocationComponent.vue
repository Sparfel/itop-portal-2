<template>
    <div v-if="displayasrow" class="row">
        <div class="form-group col-sm-6">
            <label>{{ ('Organization') }}</label>
            <div class="input-group">
                <div id="organizationLoader" class="input-group-prepend"></div>
                <select v-model="organization" @change="getOrganizationLocations" class="form-control" id="organization" name="organization">
                    <option value="0">-- Select Organization --</option>
                    <option v-for="data in organizations" :key="data.id" :value="data.id">{{ data.name }}</option>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-6">
            <label>{{ ('Location') }}</label>
            <div class="input-group">
                <div id="locationLoader" class="input-group-prepend"></div>
                <select v-model="location" class="form-control" @change="showLocationsDescription" id="location" name="location">
                    <option value="0">-- Select Location --</option>
                    <option v-for="data in locations" :key="data.id" :value="data.id">{{ data.name }}</option>
                </select>
            </div>
        </div>
    </div>

    <div v-else>
        <div class="form-group row">
            <label class="col-sm-4">{{ ('Organization') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div id="organizationLoader" class="input-group-prepend"></div>
                    <select v-model="organization" @change="getOrganizationLocations" class="form-control" id="organization" name="organization">
                        <option value="0">-- Select Organization --</option>
                        <option v-for="data in organizations" :key="data.id" :value="data.id">{{ data.name }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{ ('Location') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div id="locationLoader" class="input-group-prepend"></div>
                    <select v-model="location" class="form-control" @change="showLocationsDescription" id="location" name="location">
                        <option value="0">-- Select Location --</option>
                        <option v-for="data in locations" :key="data.id" :value="data.id">{{ data.name }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, watch, defineComponent,onBeforeMount, nextTick  } from 'vue';
import axios from 'axios';

export default defineComponent({
    name: 'OrganizationLocation',
    props: {
        location_id: {
            type: Number,
            default: 0,
        },
        displayasrow: {
            type: Boolean,
            default: true,
        },
        organization_id: {
            type: Number,
            default: 0,
        },
    },
    setup(props) {
        console.log('Setup');
        // const organization = ref(0);
        const organization = ref(props.organization_id); // Initialise avec la prop

        const organizations = ref([]);
        // const location = ref(0);
        const location = ref(props.location_id); // Initialise avec la prop
        const locations = ref([]);
        const display_info_organization = ref(false);
        const organization_title = ref('');
        const organization_description = ref('');
        const display_info_location = ref(false);
        const location_title = ref('');
        const location_description = ref('');
        const fill_mode_organization = ref(true);
        const fill_mode_location = ref(true);

        const getOrganizations = () => {
            console.log('#getOrganizations');
            console.log('fill_mode_organization.value '+fill_mode_organization.value);
            axios.get('/getOrganizations')
                .then((res) => {
                    organizations.value = res.data;
                    if (!fill_mode_organization.value) {
                        organization.value = 0;
                        locations.value = [];
                        location.value = 0;
                    } else {
                        fill_mode_organization.value = false;
                    }
                    display_info_organization.value = false;
                    display_info_location.value = false;
                });
            console.log('organizations.value '+organizations.value);
            console.log('fill_mode_organization.value '+fill_mode_organization.value);
        };

        const getOrganizationLocations = () => {
            console.log('#getOrganizationLocations');
            console.log('organization.value '+organization.value);
            display_info_location.value = false;

            //Avant de récupérer les nouvelles locations, on vide l'ancienne liste
            console.log('on vide la liste des locations');
            locations.value = [];

            const locationLoader = document.getElementById('locationLoader');
            locationLoader.innerHTML = '<div class="input-group-text" id="loader"><img style="height:24px;" src="/img/sm_loader.gif"></div>';

            axios.get('/getOrganizationLocations', {
                params: {organization: organization.value},
            }).then((res) => {

                locations.value = res.data;
                console.log('Liste de locations récupérée :');
                console.log(res.data);
                if (!fill_mode_location.value) {
                    location.value = 0;
                } else {
                    fill_mode_location.value = false;
                }
                console.log('Liste de locations affichée :');
                console.log(locations.value);
                console.log('fill_mode_location.value '+fill_mode_location.value);
                locationLoader.innerHTML = '';
            });



        };

        const getInfoOrganizations = () => {
            console.log('#getInfoOrganizations');
            console.log('organization.value '+ organization.value);
            if (organization.value === 0) {
                display_info_organization.value = false;
            } else {
                display_info_organization.value = true;
                const selectedOrganization = organizations.value.find(org => org.id === organization.value);
                if (selectedOrganization) {
                    organization_description.value = selectedOrganization.description;
                    organization_title.value = selectedOrganization.name;
                }
            }
        };

        const showLocationsDescription = () => {
            console.log('#showLocationsDescription');
            console.log('location.value '+ location.value);
            console.log('organization.value '+ organization.value);
            if (location.value === 0) {
                display_info_location.value = false;
            } else {
                display_info_location.value = true;
                const selectedLocation = locations.value.find(loc => loc.id === location.value);
                if (selectedLocation) {
                    location_description.value = selectedLocation.description;
                    location_title.value = selectedLocation.name;
                }
            }
        };

        watch([organization, location], () => {
            // if (organization.value !== 0) {
            //     getOrganizationLocations();
            // }
            // if (location.value !== 0) {
            //     showLocationsDescription();
            // }
        });

        onBeforeMount(() => {
            console.log('#onBeforeMount');
            const organizationInput = document.getElementById('organization');
            console.log(organizationInput);

            getOrganizations();
            console.log('#onBeforeMount...');
            console.log('organization_id = ' + props.organization_id);
            console.log('location_id = ' + props.location_id);
        });

        onMounted(() => {

            nextTick(() => {
                console.log('##nextTick');
                const organizationInput = document.getElementById('organization');
                console.log('organizationInput '+organizationInput.value); // Devrait être non nul maintenant
                if (organizationInput) {
                    organization.value = organizationInput.value;
                }

                const locationInput = document.getElementById('location');
                console.log(locationInput); // Devrait être non nul maintenant
                if (locationInput) {
                    location.value = locationInput.value;
                }
            });
            console.log('#onMounted');

            // if (props.location_id !== 0) {
            //     location.value = props.location_id;
            //     getOrganizationLocations();
            // }
            //
            // if (props.organization_id !== 0) {
            //     organization.value = props.organization_id;
            //     getOrganizationLocations();
            // }
            const organizationInput = document.getElementById('organization');
            console.log(organizationInput.value);
            if (organizationInput) {
                // Récupère la valeur du DOM réel et met à jour la ref
                organization.value = organizationInput.value;
            }

            const locationInput = document.getElementById('location');
            if (locationInput) {
                // Récupère la valeur du DOM réel et met à jour la ref
                location.value = locationInput.value;
            }


            // Sérialise le formulaire et émet un événement
            const form_clean = $("#newLog").serialize();
            console.log(form_clean);
            // this.$eventBus.$emit('change-form', form_clean);

        });

        return {
            organization,
            organizations,
            location,
            locations,
            display_info_organization,
            organization_title,
            organization_description,
            display_info_location,
            location_title,
            location_description,
            getOrganizations,
            getOrganizationLocations,
            getInfoOrganizations,
            showLocationsDescription,
        };
    },
});
</script>
