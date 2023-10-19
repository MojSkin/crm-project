<template>
    <div class="columns is-multiline" v-if="loading || loadAgain">
        <div class="column is-12" v-if="loading">
            <b-card class="py-6" no-footer no-header>
                <div class="my-6 w-100 is-flex is-align-items-center is-justify-content-center">
                    <i class="fal fa-spinner-third fa-spin has-text-info fa-2x mr-3"></i>
                    <p class="is-weight-700 has-text-grey">در حال دریافت اطلاعات فرم</p>
                </div>
            </b-card>
        </div>
        <div class="column is-12" v-else-if="loadAgain">
            <b-card class="py-6" no-footer no-header>
                <div class="my-6 w-100 is-flex is-align-items-center is-justify-content-center">
                    <button class="button is-light" @click="getFormDetails">تلاش مجدد</button>
                </div>
            </b-card>
        </div>
    </div>
    <div class="columns is-multiline" v-else-if="transaction_id != null">
        <div class="column is-12 is-flex is-flex-direction-column">
            <b-card class="py-6 has-background-success" no-footer no-header>
                <div class="my-6 w-100 is-flex is-flex-direction-column is-align-items-center is-justify-content-center">
                    <span class="title">شماره پیگیری شما</span>
                    <span class="transactionDetail mt-3 mb-5">{{ transaction_id }}</span>
                    <span class="has-text-dark is-weight-700 has-text-centered">برای پیگیری‌های بعدی، این کد را یادداشت نموده یا از این صفحه اسکرین شات بگیرید</span>
                    <RouterLink to="/" class="button is-light mt-6 py-5 px-6" v-if="!action?.action">
                        <i class="fal fa-home mr-2"></i>
                        <span>بازگشت به صفحه نخست</span>
                    </RouterLink>
                    <div class="w-100 is-flex is-flex-direction-column is-align-items-center is-justify-content-center" v-else>
                        <button @click="redirectToBank" class="button is-light mt-6 mb-5 py-5 px-6" ref="redirectToBank" :disabled="timeToRedirect <= 1">
                            <i class="fal fa-credit-card mr-2"></i>
                            <span>انتقال به بانک و تکمیل عملیات</span>
                        </button>
                        <span v-if="timeToRedirect > 1"><span>انتقال خودکار تا</span><span style="font-size: 1.25rem!important;" class="mx-3 has-text-primary is-weight-700">{{ timeToRedirect }}</span><span>ثانیه دیگر</span></span>
                        <span v-else>در حال انتقال به درگاه بانک</span>
                    </div>
                </div>
            </b-card>
        </div>
    </div>
    <div class="columns is-multiline" v-else>
        <div class="column is-12" v-if="!form.active">
            <b-card class="py-6 has-background-warning" no-footer no-header>
                <div class="my-6 w-100 is-flex is-flex-direction-column is-align-items-center is-justify-content-center">
                    <span class="title">فرم مورد نظر شما غیرفعال است.</span>
                    <span class="has-text-dark is-weight-700 has-text-centered mt-3 mb-5">در صورتی‌که احساس می‌کنید مشکلی پیش آمده است، با مدیر سایت تماس بگیرید.</span>
                    <RouterLink to="/" class="button is-dark mt-6 py-5 px-6">
                        <i class="fal fa-home mr-2"></i>
                        <span>بازگشت به صفحه نخست</span>
                    </RouterLink>
                </div>
            </b-card>
        </div>
        <div class="column is-12" v-else>
            <b-card>
                <template #header v-if="!hideTitle">
                    <div  class="is-flex is-flex-direction-column w-100 py-3">
                        <div class="title mb-3">{{ form.title }}</div>
                        <div class="mt-3 subtitle has-text-justified">{{ form.description }}</div>
                    </div>
                </template>
                <FormContentComponent
                    v-if="fields.length"
                    v-model="fields"
                    :errors="errors"
                    @error="setErrors"
                    key="fieldsPanel"
                    ref="form"
                />
                <template #footer-left>
                    <button class="button is-light ml-auto mr-2" :disabled="saving" @click="resetForm">فرم جدید</button>
                    <button class="button is-success" :disabled="saving" @click="saveForm" v-if="!saving">
                        <i class="fal fa-spinner-third spin" v-if="saving"></i>
                        <i class="fal fa-save" v-else></i>
                        <span class="ml-2"><span>ذخیره فرم</span></span>
                    </button>
                </template>
            </b-card>
        </div>
    </div>
</template>

<script>
export default {
    name: "FormComponent",
    props: {
        formId: {
            type: [String, Number],
            key: [String],
            required: true
        },
        hideTitle: Boolean,
    },
    data() {
        return {
            form: null,
            fields: [],
            loading: false,
            saving: false,
            loadAgain: false,
            errors: [],
            transaction_id: null,
            hasPayment: -1,
            action: null,
            timeToRedirect: 30,
        }
    },
    created() {
        this.getFormDetails()
    },
    methods: {
        getFormDetails() {
            if (!this.loading) {
                this.loadAgain = false
                this.loading = true

                Requests.getFormDetails(this.formId).then(res => {
                    if (res?.status) {
                        this.form = {
                            id: res.result.id,
                            title: res.result.title,
                            description: res.result.description,
                            active: res.result.active,
                        }
                        this.fields = []
                        for (const key in res.result.fields) {
                            this.fields.push(res.result.fields[key])
                        }
                        for (let i = 0; i < this.fields?.length; i++) {
                            if (this.fields[i]?.type === 31) {
                                this.hasPayment = parseInt(this.fields[i]?.options)
                                this.hasPayment = isNaN(this.hasPayment) ? 0 : this.hasPayment
                                break;
                            }
                        }
                    } else {
                        this.form = {
                            active: false,
                        }
                    }
                }).catch(err => {
                    this.$helpers.notify('خطا', 'خطا در دریافت اطلاعات فرم از سرور', { type: 'error' })
                }).finally(res => {
                    this.loading = false
                    this.errors = {}
                    window.scrollTo({
                        left: 0,
                        top: -100,
                        behavior: 'smooth'
                    })
                })
            }
        },
        resetForm() {
            window.scrollTo({
                top: 0
            })
            this.$nextTick(e => {
                this.getFormDetails()
            })
        },
        saveForm() {
            this.$refs.form.validateAll().then(res => {
                if (res) {
                    let fields = []
                    let form = new FormData()
                    form.append('form_id', this.form.id)
                    form.append('hasPayment', this.hasPayment)
                    for (let i = 0; i < this.fields.length; i++) {
                        const value = this.fields[i]?.type == 15 ? this.fields[i]?.name+'_attachment:'+(this.fields[i]?.value?.length || 0)
                                                                 : ((typeof this.fields[i]?.value === 'object') ? (this.fields[i]?.value?.join('|') || null)
                                                                        : (this.fields[i]?.value || null))
                        fields.push({
                            form_id: this.form.id,
                            form_field_id: this.fields[i].id,
                            value: value,
                        })
                        if (this.fields[i].type == 15 && this.fields[i].value && this.fields[i].value?.length) {
                            for (let j = 0; j < this.fields[i].value.length; j++) {
                                form.append(this.fields[i].name+'_'+(j+1), this.fields[i].value[j])
                            }
                        }
                    }
                    // const form = {
                    //     form_id: this.form.id,
                    //     fields: fields,
                    //     hasPayment: this.hasPayment
                    // }
                    form.append('fields', JSON.stringify(fields))
                    this.saving = true
                    Requests.saveFormData(form).then(res => {
                        if (res?.status) {
                            this.transaction_id = res.result
                            this.$helpers.notify(res?.message || 'فرم شما با موفقیت ثبت گردید.')
                            if (this.hasPayment >= 0) {
                                this.action = res.action
                                if (this?.action?.action) {
                                    this.timeToRedirect = 30
                                    setInterval(() => {
                                        if (this.timeToRedirect <= 1) {
                                            this.redirectToBank()
                                        } else {
                                            this.timeToRedirect--
                                        }
                                    }, 1000)
                                }
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'فرم شما با موفقیت ثبت گردید.', {type: 'error'})
                        }
                    }).catch(err => {
                        this.saving = false
                    }).then(res => {
                        this.saving = false
                    })
                } else {
                    this.$helpers.notify('خطای ورود اطلاعات', 'قبل از ذخیره کردن فرم، باید خطاهای فرم را برطرف کنید.', {type: 'warning'})
                }
            })
        },
        checkErrors() {
            this.errors = {}
            for (let i = 0; i < this.fields.length; i++) {
                const field = this.fields[i]
                this.errors[field.name] = []
                if (field.type === 10) { continue }
                if ((field.is_required && field.type != 3 && (field?.value === null || typeof field?.value === 'undefined' || !field?.value?.length)) ||
                    (field.is_required && field.type === 2 && field?.value === null || typeof field?.value === 'undefined')) {
                    this.errors[field.name].push('فیلد '+field.title+' الزامی است')
                }
                if (field.type === 1 || field.type === 2) {
                    if (field.min && field?.value?.length < parseInt(field.min)) {
                        this.errors[field.name].push('فیلد '+field.title+' حداقل باید '+field.min+' کاراکتر باشد')
                    }
                    if (field.max && field?.value?.length > parseInt(field.max)) {
                        this.errors[field.name].push('فیلد '+field.title+' حداکثر می‌تواند '+field.min+' کاراکتر باشد')
                    }
                }
                if (field.type === 3) {
                    if (field.min && field?.value < parseInt(field.min)) {
                        this.errors[field.name].push('حداقل مقدار فیلد '+field.title+' باید برابر '+field.min+' باشد')
                    }
                    if (field.max && field?.value > parseInt(field.max)) {
                        this.errors[field.name].push('حداکثر مقدار فیلد '+field.title+' نهایتا می‌تواند برابر '+field.min+' باشد')
                    }
                }
                if (field.type === 3 || field.type === 4 || field.type === 5 || field.type === 6) {
                    if (field.min && field?.value < parseInt(field.min)) {
                        this.errors[field.name].push('حداقل مقدار فیلد '+field.title+' باید برابر '+field.min+' باشد')
                    }
                    if (field.max && field?.value > parseInt(field.max)) {
                        this.errors[field.name].push('حداکثر مقدار فیلد '+field.title+' نهایتا می‌تواند برابر '+field.min+' باشد')
                    }
                }
            }
            let hasError = false
            for (const key in this.errors) {
                if (this.errors[key]?.length > 0 ) {
                    hasError = true
                    break
                }
            }
            return !hasError
        },
        redirectToBank() {
            window.location.replace(this.action?.action);
        },
        setErrors(e) {

        },
    }
};
</script>

<style>
.far.fa-circle.has-text-danger {
    font-size: 0.5rem !important;
    vertical-align: super !important;
}
textarea.is-error {
    border-color: #e62965 !important;
}
.transactionDetail {
    display: block;
    border: 3px dashed #a874ec !important;
    border-radius: 15px;
    font-size: 1.75rem !important;
    text-align: center;
    padding: 0.25rem 1.25rem;
    font-family: var(--font-monospace);
    letter-spacing: 3px;
}
</style>
