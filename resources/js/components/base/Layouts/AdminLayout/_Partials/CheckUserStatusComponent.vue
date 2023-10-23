<template>
    <div class="w-100">
        <progress class="progress is-success is-tiny" :value="uploadProgress" max="100" v-if="saving"/>
    </div>
</template>

<script>
import router from "@/router";

export default {
    name: "CheckUserStatusComponent",
    props: {},
    data() {
        return {}
    },
    created() {
        this.checkUserStatus()
    },
    mounted() {
    },
    computed: {},
    watch: {
        $route() {
            this.checkUserStatus()
        }
    },
    methods: {
        checkUserStatus() {
            console.log('I`m checking')
            let crmState = localStorage?.crmState
            crmState = (crmState) ? JSON.parse(localStorage?.crmState || '{}') : {}
            const currentUser = crmState?.userData
            Requests.checkUserStatus(currentUser.username).then(res => {
                if (res === 403) {
                    localStorage.removeItem('crmState')
                    router.push('/login')
                }
                // OR  BE  HAPPY!!!
            })
        }
    }
}
</script>

<style scoped>

</style>
