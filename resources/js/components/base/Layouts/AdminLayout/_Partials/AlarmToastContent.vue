<template>
    <div class="w-100 has-background-info-light p-3 overflow-hidden rounded">
        <h3 class="has-text-danger-dark is-light" v-text="alarm?.title || 'آلارم'"/>
        <div class="w-100 is-flex is-flex-direction-column has-text-weight-bold mb-3 small">
            <span class="ml-auto" v-if="alarm.alarm_date">{{ jDate(alarm.alarm_date) }}</span>
            <span class="ml-auto" v-else>امروز</span>
            <span class="ml-auto">{{ jDate(alarm.alarm_time, 'H:mm') }}</span>
        </div>
        <p class="has-text-dark has-text-justified" v-text="alarm?.description || ''"></p>
        <div class="w-100 is-flex has-text-weight-bold mt-3 mb-0">
            <button class="button is-small is-success has-text-dark is-rounded ml-auto" @click="closeToast">
                <i class="fal fa-times mr-2 has-text-danger"></i>
                <span>متوجه شدم</span>
            </button>
        </div>
    </div>
</template>

<script>
import moment from "moment-jalaali";
export default {
    name: "AlarmToastContent",
    props: ['toastId', 'alarm'],
    methods: {
        jDate(date, format) {
            return moment(date)?.locale("fa")?.format(format)
        },
        closeToast() {
            this.$emit('close-toast')
        }
    }
}
</script>

<style scoped>

</style>
