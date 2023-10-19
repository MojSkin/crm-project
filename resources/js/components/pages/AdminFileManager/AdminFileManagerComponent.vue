<template>
    <file-manager>

    </file-manager>
</template>

<script>

export default {
    name: "AdminFileManagerComponent",
    props: {},
    data() {
        return {
            settings: {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Authorization: `Bearer ${Requests.token()}`,
                },
                windowsConfig: 2,                           // overwrite config
            }
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
