<template>
    <BlankLayoutComponent class="full">
        <div class="payment-status mt-5">
            <div class="card">
                <div class="card-content is-flex is-flex-direction-column is-align-items-center">
                    <div class="is-flex is-flex-direction-column is-align-items-center" v-if="loading">
                        <span class="mb-6">درحال دریافت اطلاعات پرداخت</span>
                        <i class="fal fa-spinner-third fa-spin fa-4x has-text-grey-light"></i>
                    </div>
                    <div class="is-flex is-flex-direction-column is-align-items-center" v-else>
                        <i class="far fa-check fa-5x has-text-success" v-if="result?.status"></i>
                        <i class="far fa-times fa-5x has-text-danger" v-else></i>
                        <span class="mt-6 has-text-centered" v-if="result?.status">
                            <span class="is-block mb-3">با تشکر از شما، عملیات پرداخت موفقیت‌آمیز بود.</span>
                            <span class="is-block is-weight-700 has-text-success-dark mb-3">شماره پیگیری: {{ result.message }}</span>
                            <span class="is-block mb-3" v-if="result?.form"><br><span>کد پیگیری فرم شما:</span><span class="is-weight-700 has-text-danger-dark ml-3">{{ result.form }}</span></span>
                        </span>
                        <span class="mt-6 has-text-centered has-text-warning-dark" v-else>{{ result.message }}</span>
                        <RouterLink to="/" class="button is-light mt-6 py-5 px-6" v-if="!action?.action">
                            <i class="fal fa-home mr-2"></i>
                            <span>بازگشت به صفحه نخست</span>
                        </RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </BlankLayoutComponent>
</template>

<script>

export default {
    name: "PaymentCallbackComponent",
    data() {
        return {
            query: this.$route.query,
            loading: true,
            result: null,
        }
    },
    mounted() {
        this.verify()
    },
    methods: {
        verify() {
            Requests.verifyInvoice(this.query.Authority).then(res => {
                this.result = res;
            }).finally(res => {
                this.loading = false;
            })
        }
    }
};
</script>

<style scoped>
.full {
    display: flex;
    flex-direction: column;
    position: absolute;
    inset: 0;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
}
.payment-status {
    min-width: 360px;
    max-width: 360px;
}
</style>
