<template>
    <nav class="navbar is-white">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item brand-text" href="#">
                    پنل دانش‌آموز
                </a>
                <div class="navbar-burger burger" :class="{ 'is-active': isActive }" @click="isActive = !isActive" aria-label="menu" :aria-expanded="isActive" data-target="navMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div id="navMenu" class="navbar-menu" :class="{ 'is-active': isActive }">
                <div class="navbar-start">
                    <RouterLink class="navbar-item" to="/student/dashboard">پیشخوان</RouterLink>
                    <a class="navbar-item" href="admin.html">
                        Orders
                    </a>
                    <a class="navbar-item" href="admin.html">
                        Payments
                    </a>
                    <a class="navbar-item" href="admin.html">
                        Exceptions
                    </a>
                    <a class="navbar-item" href="admin.html">
                        Reports
                    </a>
                </div>

            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "TopNav",
    props: {},
    data() {
        return {
            isActive: false,
        }
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

</style>
