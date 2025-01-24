<template>
    <div>
        <div class="form-group">
            <label>{{ 'Location' }}</label>
            <div class="input-group" data-target-input="nearest">
                <div ref="locationLoader" class="input-group-prepend"></div>
                <select v-model="location" @change="emitLocation" class="form-control" id="location" name="location">
                    <option value="0">Select location</option>
                    <option v-for="data in locations" :key="data.id" :value="data.id">
                        {{ data.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
    name: "LocationServiceModule",
    props: {
        location_id: {
            type: Number,
            default: 0,
        },
    },
    setup(props, { emit }) {
        const location = ref(0);
        const locations = ref([]);
        const locationLoader = ref(null);

        const getLocation = () => {
            if (locationLoader.value) {
                locationLoader.value.innerHTML =
                    '<div class="input-group-text" id="loader"><img style="height:24px;" src="/img/sm_loader.gif"></div>';
            }
            axios.get("/getLocations").then((res) => {
                locations.value = res.data;
                if (locationLoader.value) locationLoader.value.innerHTML = "";
            });
        };

        const emitLocation = () => {
            emit("change-location", location.value);
            console.log("Site ID choisi : " + location.value);
        };

        onMounted(() => {
            if (props.location_id) {
                location.value = props.location_id;
                emit("change-location", location.value);
            }
            getLocation();
        });

        return {
            location,
            locations,
            locationLoader,
            emitLocation,
        };
    },
});
</script>
