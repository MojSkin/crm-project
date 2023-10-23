<template>
    <section class="hero">
        <div class="hero-body is-flex is-justify-content-center is-align-items-center">
            <div class="container-fluid is-flex is-flex-direction-column is-justify-content-center is-align-items-center">
                <img :src="base_url+'/assets/img/illustrations/placeholders/error-2.svg'" alt="404">
                <div class="is-flex is-flex-direction-row is-justify-content-center is-align-items-center">
                    <h3>خطای 404</h3>
                    <h1>|</h1>
                    <p class="has-text-primary is-weight-700">ارتباط با سرور با مشکل مواجه شد!</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "404",
    inject: ['base_url'],
    data() {
        return {}
    },
    created() {
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {}
}
</script>

<style scoped>
.hero {
    height: 100vh;
}
img {
    height: 45vh;
    margin-bottom: 1rem;
}
</style>
