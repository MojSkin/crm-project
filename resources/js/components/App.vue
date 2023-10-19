<template>
    <RouterView v-slot="{ Component }">
        <component :is="Component" />
    </RouterView>
</template>

<script>

export default {
    name: "App",
    inject: ['base_url'],
    created() {
        this.$store.commit('setStateData', { basePath: import.meta.env.VITE_APP_URL, logo: import.meta.env.VITE_LOGO })
        this.EventBus.on('settingsChanged', () => {
            this.handleDocumentTitle(this.$route?.meta?.pageTitle)
        })
    },
    watch: {
        $route(to) {
            this.handleDocumentTitle(to.meta.pageTitle || null)
        },
    },
    methods: {
        handleDocumentTitle(pageTitle = null) {
            let title = this.$settings?.site_name || import.meta.env.VITE_APP_NAME
            if (this.$settings?.update_page_title == true) {
                title += ((pageTitle != null && pageTitle.length > 0) ? ' | ' + pageTitle : '')
            }
            document.title = title
        }
    }
}
</script>

<style scoped>

</style>
