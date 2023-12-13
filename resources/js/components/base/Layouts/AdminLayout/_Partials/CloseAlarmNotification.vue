<template>
    <span class="button is-small has-background-grey-lighter is-light is-rounded alarm-close-btn" @mouseenter="text='متوجه شدم'" @mouseleave="text='x'" @click="closeAlarmNotification"></span>
</template>

<script>
import {toast} from "vue3-toastify";

export default {
    name: "CloseAlarmNotification",
    props: ['alarmId'],
    data() {
        return {
            text: 'x'
        }
    },
    methods: {
        closeAlarmNotification() {
            toast.remove('alarm-'+this.alarmId)
        }
    }
}
</script>

<style>
.alarm-close-btn {
    position: absolute;
    display: inline-block;
    width: 20px;
    overflow: hidden;
    transition: all 0.15s ease-in-out;
    z-index: 2;
    left:8px;
}
.alarm-close-btn:hover {
    width: 75px;
}
.alarm-close-btn:after {
    position: absolute;
    inset: 0;
    display: inline-block;
    content: '×';
    font-family: 'VazirMatn';
    padding-top: 4px;
    font-size:20px;
    font-weight: 700;
    top: -6px;
}
.alarm-close-btn:hover:after {
    position: absolute;
    inset: 0;
    display: inline-block;
    content: 'متوجه شدم';
    font-size:11px;
    top: 0;
}
</style>
