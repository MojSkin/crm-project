<template>
    <MainLayoutComponent class="mt-6">
        <b-card no-footer no-header>
            <div class="columns is-multiline">
                <div class="column is-6 is-4-desktop is-4-widescreen">
                    <b-input
                        label="کد پیگیری"
                        description="کد پیگیری که در زمان ثبت‌نام دریافت کرده‌اید را وارد کنید."
                        v-model="tCode"
                        placeholder="مثلا: JY1O6S9GXOID"
                        :disabled="loading"
                    >

                    </b-input>
                </div>
                <div class="column is-12-mobile is-3 is-flex is-align-items-center" @click="submit">
                    <button class="button is-info px-5" :class="{'is-loading': loading }" :disabled="loading">بررسی کد</button>
                </div>
            </div>
            <Transition mode="in-out" type="transition">
                <div class="columns is-multiline" v-if="fields?.length">
                    <div class="column is-12">
                        <span>تاریخ ثبت فرم: </span>
                        <span v-html="form.date"></span>
                    </div>
                    <div class="column is-12 is-flex is-align-items-center mb-2">
                        <div class="">
                            <span class="mr-2">وضعیت ثبت‌نام:</span>
                            <span :class="form.state.class">{{ form.state.text }}</span>
                        </div>
                        <div v-if="form.rejects?.length" class="is-block rem-90 ml-2">
                            <i class="fal fa-exclamation-circle has-text-info"></i>
                            <span class="mx-2">در تاریخ</span>
                            <bdi class="is-weight-700 has-text-info">{{ $helpers.jDate(form.rejects[form.rejects.length-1].created_at, 'jYYYY/jMM/jDD HH:MM') }}</bdi>
                            <span class="mr-2 ml-1">:</span>
                            <span class="is-weight-700">{{ form.rejects[form.rejects.length-1].comment }}</span>
                        </div>

                    </div>
                    <div class="column is-12 is-flex is-align-items-center is-flex is-align-items-center" v-if="form.invoice">
                        <span class="mr-2">وضعیت پرداخت:</span>
                        <span v-if="form.invoice?.id" class="is-flex is-align-items-center">
                            <span class="tag is-success is-rounded" v-if="form.invoice.status===1">موفقیت‌آمیز</span>
                            <span class="tag is-info is-outlined is-rounded" v-else-if="form.invoice.status===0">منتظر پرداخت</span>
                            <span class="tag is-danger is-rounded" v-else>خطای پرداخت</span>
                            <span class="ml-2 rem-90" v-if="form.invoice.status===1">
                                <span class="mr-2">شماره پیگیری:</span><span class="is-weight-700">{{ form.invoice.callbackId }}</span>
                                <span class="mx-2">-</span>
                                <span class="mr-2">تاریخ پرداخت:</span><bdi class="is-weight-700">{{ $helpers.jDate(form.invoice.created_at, 'jYYYY/jMM/jDD HH:MM') }}</bdi>
                            </span>
                        </span>
                        <span v-else>
                            <span class="tag is-danger is-outlined is-rounded">فاقد اطلاعات پرداخت</span>
                        </span>
                    </div>
                    <FormContentComponent
                        v-model="fields"
                        :errors="[]"
                        :editable="false"
                        printable
                    />
                </div>
            </Transition>
        </b-card>
    </MainLayoutComponent>
</template>

<script>

export default {
    name: "PreRegTrackingComponent",
    data() {
        return {
            loading: false,
            tCode: null,
            form: {},
            fields: []
        }
    },
    methods: {
        submit() {
            if (!this.loading) {
                if (this.tCode?.length) {
                    this.loading = true;
                    this.form = {}
                    this.fields = []
                    Requests.trackForm(this.tCode, 1).then(res => {
                        if (res?.status) {
                            this.form = res.result
                            if (this.form?.id) {
                                this.form.date = '<bdi>'+this.$helpers.jDate(this.form.date, 'jYYYY/jMM/jDD HH:MM')+'</bdi>'
                                this.fields = this.form.fields || []
                                for (let i = 0; i < this.fields?.length; i++) {
                                    if (this.fields[i].type === 3 && this.fields[i].options=='true') {
                                        this.fields[i].value = !isNaN(this.fields[i].value) ? parseInt(this.fields[i].value).toLocaleString() : this.fields[i].value
                                    } else if (this.fields[i].type === 4) {
                                        this.fields[i].value = '<bdi>'+this.$helpers.jDate(this.fields[i].value, 'jYYYY/jMM/jDD')+'</bdi>'
                                    } else if (this.fields[i].type === 5) {
                                        this.fields[i].value = '<bdi>'+this.$helpers.jDate(this.fields[i].value, 'jYYYY/jMM/jDD HH:MM')+'</bdi>'
                                    } else if (this.fields[i].type === 31) {
                                        this.fields[i].value = !isNaN(parseInt(this.fields[i].value)) ? parseInt(this.fields[i].value).toLocaleString() : this.fields[i].value
                                    }
                                }
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام پیگیری فرم', {type: 'error'})
                        }
                    }).catch(res => {
                        this.$helpers.notify('خطای سرور', res?.message || 'بروز خطا هنگام پیگیری فرم', {type: 'error'})
                    }).finally(res => {
                        this.loading = false
                    })
                } else {
                    this.$helpers.notify('خطا', 'ورود کد پیگیری الزامی است', {type: 'error'})
                }
            }
        },
    }
};
</script>

<style scoped>

</style>
