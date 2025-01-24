<template>
    <div>
        <h1>{{ message }}</h1>
        <button @click="changeMessage">Changer le message</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: 'Bonjour de Vue 3!'
        };
    },
    methods: {
        changeMessage() {
            this.message = 'Le message a été changé!';
        }
    }
}
</script>

<style scoped>
h1 {
    color: blue;
}
</style>
