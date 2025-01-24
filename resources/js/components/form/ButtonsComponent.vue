<template>
    <div v-if="display === 'small'" class="btn-group">
        <button v-if="data.update" @click="update" type="button" id="validtop" class="btn btn-outline-primary" :title="translate('update')">
            <i class="fas fa-check"></i>
        </button>
        <button v-if="data.resolve" @click="resolve" type="button" id="solvetop" class="btn btn-outline-primary" :title="translate('resolve')">
            <i class="far fa-thumbs-up"></i>
        </button>
        <button v-if="data.reopen" @click="reopen" type="button" id="reopentop" class="btn btn-outline-primary" :title="translate('reopen')">
            <i class="fas fa-unlock-alt"></i>
        </button>
        <button v-if="data.close" @click="close" type="button" id="closetop" class="btn btn-outline-primary" :title="translate('close')">
            <i class="fas fa-lock"></i>
        </button>
    </div>

    <div v-else class="callout callout-primary">
        <button v-if="data.update" @click="update" type="button" id="valid" class="btn btn-primary btn-block">
            <i class="fas fa-check"></i> {{ translate('update') }}
        </button>
        <button v-if="data.resolve" @click="resolve" type="button" id="solve" class="btn btn-primary btn-block">
            <i class="far fa-thumbs-up"></i> {{ translate('resolve') }}
        </button>
        <button v-if="data.reopen" @click="reopen" type="button" id="reopen" class="btn btn-primary btn-block">
            <i class="fas fa-unlock-alt"></i> {{ translate('reopen') }}
        </button>
        <button v-if="data.close" @click="close" type="button" id="close" class="btn btn-primary btn-block">
            <i class="fas fa-lock"></i> {{ translate('close') }}
        </button>
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import toastr from 'toastr';

export default {
    props: {
        message: String,
        buttons: String,
        display: String,
        locale: String
    },
    setup(props) {
        const data = ref({});
        let formClean = '';

        const buttonData = () => {
            data.value = JSON.parse(props.buttons);
        };

        const update = () => {
            const formDirty = $("#newLog").serialize();
            console.log(formClean);
            console.log(formDirty);
            if (formClean !== formDirty) {
                document.getElementById("changeStatus").value = "update";
                $("#newLog").submit();
            } else {
                toastr['warning'](translate('No change. Please modify something'), translate('No change'));
            }
        };

        const resolve = () => {
            document.getElementById("changeStatus").value = "solve";
            $("#newLog").submit();
        };

        const close = () => {
            document.getElementById("changeStatus").value = "close";
            $("#newLog").submit();
        };

        const reopen = () => {
            document.getElementById("changeStatus").value = "reopen";
            $("#newLog").submit();
        };

        const storeFormSerial = (formSerial) => {
            formClean = formSerial;
            console.log('event ' + formClean);
        };

        const translate = (key) => {
            const translations = {
                en: {
                    update: 'Update',
                    resolve: 'Resolve',
                    close: 'Close',
                    reopen: 'Re-open',
                    'No change. Please modify something': 'No change. Please modify something',
                },
                fr: {
                    update: 'Mise à jour',
                    resolve: 'Résoudre',
                    close: 'Fermer',
                    reopen: 'Ré-ouvrir',
                    'No change. Please modify something': 'Pas de changement. Merci de modifier quelque chose.',
                }
            };

            return translations[props.locale]?.[key] || translations['en'][key];
        };

        onMounted(() => {
            buttonData();
            window.addEventListener('change-form', (event) => storeFormSerial(event.detail));
        });

        return {
            data,
            update,
            resolve,
            close,
            reopen,
            translate
        };
    }
};
</script>
