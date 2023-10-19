<template>
    <section class="hero">
        <div class="hero-body is-flex is-justify-content-center is-align-items-center">
            <div class="container-fluid is-flex is-flex-direction-column is-justify-content-center is-align-items-center">
                <img src="/resources/assets/images/svg/error-500.svg" alt="404">
                <div class="is-flex is-flex-direction-row is-justify-content-center is-align-items-center">
                    <h3>خطای 404</h3>
                    <h1>|</h1>
                    <p class="has-text-primary is-weight-700">شما به دنبال چیزی می‌گردید که وجود ندارد<br>یا ممکن است حذف شده باشد!</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "404",
    props: {},
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
