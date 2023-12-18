<template>
    <div class="w-100">
        <progress class="progress is-success is-tiny" :value="uploadProgress" max="100" v-if="saving"/>
    </div>
</template>

<script>
import router from "@/router";
import {toast} from "vue3-toastify";
import { h } from 'vue';
import { useToast } from "vue-toastification";

import AlarmToastContent from "./AlarmToastContent.vue";

export default {
    name: "CheckUserStatusComponent",
    props: {},
    inject: ['base_url'],
    components: {
        AlarmToastContent
    },
    data() {
        return {
            toastOptions:{
                multiple                : true,
                limit                   : 5,
                newestOnTop             : true,
                dangerouslyHTMLString   : true,
                icon                    : undefined,
                rtl                     : true,
                position                : toast.POSITION.TOP_LEFT,
                autoClose               : false,
                transition              : toast.TRANSITIONS.SLIDE,
                hideProgressBar         : true,
                pauseOnHover            : true,
                pauseOnFocusLoss        : true,
                closeOnClick            : false,
                theme                   : 'auto',
                type                    : 'default',
                title                   : null,
            },
        }
    },
    created() {
        this.checkUserStatus()
        let crmState = localStorage?.crmState
        crmState = (crmState) ? JSON.parse(localStorage?.crmState || '{}') : {}
        const currentUser = crmState?.userData
        Echo.private('user-alarms-'+currentUser.username).listen('AlarmEvent', (event) => {
            const alarms = event?.alarms || {}
            for (const alarmKey in alarms) {
                const alarm = alarms[alarmKey]
                const alarmContent = {
                    component: AlarmToastContent,
                    props: {
                        alarm: alarm
                    },
                }
                useToast().info(alarmContent, {
                    position: 'top-left',
                    rtl: true,
                    maxToasts: 10,
                    draggable: false,
                    closeOnClick: false,
                    timeout: false,
                    icon: 'fas fa-alarm-clock fa-2x has-text-light',
                    toastClassName: 'p-3',
                    closeButton: false,
                    showCloseButtonOnHover: false
                });
            }
        })
    },
    mounted() {
        window.Echo.connector.pusher.connection.bind('message', (payload) => {
            console.log('message', payload);
        });
        window.Echo.connector.pusher.connection.bind('unavailable', (payload) => {
            console.log('unavailable', payload);
        });

        window.Echo.connector.pusher.connection.bind('failed', (payload) => {
            console.log('failed', payload);
        });

        Echo.connector.pusher.connection.bind('connected', (socketId) => {
            console.log(socketId, 'connected')
        });

        Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('disconnected')
        });
    },
    beforeUnmount() {
    },
    computed: {},
    watch: {
        $route() {
            this.checkUserStatus()
        }
    },
    methods: {
        checkUserStatus() {
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
        },
    }
}
</script>

<style>
.Toastify__toast-body {
    width: calc( 100% - 85px) !important;
    position: relative;
    z-index: 1;
}
</style>
