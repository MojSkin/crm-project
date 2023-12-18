<template>
    <div class="w-100">
        <progress class="progress is-success is-tiny" :value="uploadProgress" max="100" v-if="saving"/>
    </div>
    <audio ref="notification">
        <source :src="audio+'.ogg'" type="audio/ogg">
        <source :src="audio+'.mp3'" type="audio/mpeg">
    </audio>
</template>

<script>
import router from "@/router";
import {toast} from "vue3-toastify";
import { h } from 'vue';

import CloseAlarmNotification from "./CloseAlarmNotification.vue";

export default {
    name: "CheckUserStatusComponent",
    props: {},
    inject: ['base_url'],
    components: {
        CloseAlarmNotification
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
            audio: this.base_url+'/assets/sounds/notification'
        }
    },
    created() {
        this.checkUserStatus()
        let crmState = localStorage?.crmState
        crmState = (crmState) ? JSON.parse(localStorage?.crmState || '{}') : {}
        const currentUser = crmState?.userData
        console.log(currentUser.username)
        Echo.private('user-alarms-'+currentUser.username).listen("AlarmEvent", (event) => {
            const alarms = event.alarms
            for (const alarmKey in alarms) {
                const alarm = alarms[alarmKey]
                let htmlMessage
                htmlMessage = '<div class="is-weight-700 is-size-6 is-font-alt"><i class="fad fa-alarm-clock fa-2x mr-2 has-text-warning-dark"></i>'+alarm.title+'</div><hr class="mt-0 mb-2"><p class="is-weight-400 is-size-6 has-text-justified mb-3 is-flex">'
                if (alarm.alarm_date) {
                    htmlMessage += '<span>تاریخ: '+this.$helpers.jDate(alarm.alarm_date)+'</span>'
                } else {
                    htmlMessage += '<span>امروز</span>'
                }
                htmlMessage += '<span class="ml-auto">ساعت: '+this.$helpers.jDate(alarm.alarm_time, 'H:mm')+'</span></p><p class="is-weight-400 is-size-6 has-text-justified mb-0">'+alarm.description+'</p>'
                this.toastOptions.closeButton = (props) => h(CloseAlarmNotification, {alarmId: alarm.id})
                this.toastOptions.title = ''
                this.toastOptions.toastId = 'alarm-'+alarm.id
                // this.$refs.notification.play()
                toast(htmlMessage, this.toastOptions)
            }
        })
    },
    mounted() {
        // this.$refs.notification.load()
    },
    beforeUnmount() {
        // this.$refs.notification.pause()
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
