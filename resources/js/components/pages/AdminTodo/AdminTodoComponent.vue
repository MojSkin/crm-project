<template>
    <b-modal
        ref="todoMainModal"
        :is-card="false"
        :show="isActive"
        size="small"
        backdrop="shade"
        class="overflow-visible"
    >
        <b-card card-size='s' class="no-margin no-padding" body-class="p-5" no-header>
            <div class="inner-content" style="max-height: 350px">
                <FormContentComponent
                    v-model="form"
                    :errors="errors"
                    ref="form"
                    @error="setErrors"
                />
            </div>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="ذخیره"><i @click="saveTodo" class="fas fa-check has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top mr-2 " data-hint="انصراف"><i @click="modalClose" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
</template>

<script>
import Form from '/resources/js/form'
import FormContentComponent from "@/components/global/Form/FormContentComponent.vue";
export default {
    name: "AdminTodoComponent",
    components: {FormContentComponent},
    props: {
        record: {
            type: [Object, null],
            default: null,
        },
        active: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            form: [],
            errors: [],
            form_template: {
                title:       {title: 'عنوان', class: 'small' , is_required: true, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['required', 'min:3', 'max:100']},
                description: {title: 'توضیحات', rows:2, description: 'حداکثر 500 کاراکتر', type: 2, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['min:10', 'max:500']},
                due_date:    {title: 'زمان سررسید', type: 5, col_mobile: 6, col_tablet: 6, col_desktop: 6, col_widescreen: 6},
                flag:        {title: 'درجه اهمیت', type: 9, default: 'عادی', options: 'کم|متوسط|عادی|زیاد|اضطراری', col_mobile: 6, col_tablet: 6, col_desktop: 6, col_widescreen: 6},
            },
            isActive: this.active,
            flags: [
                'کم',
                'متوسط',
                'عادی',
                'زیاد',
                'اضطراری',
            ]
        }
    },
    created() {
        this.form   = new Form(this.form_template)
        this.errors = []
    },
    mounted() {

    },
    watch: {
        active() {
            this.isActive = this.active
        }
    },
    computed: {},
    methods: {
        modalOpen() {
            this.form   = new Form(this.form_template)
            this.errors = []

            if (this.record != null) {
                this.form.title.value       = this.record?.title ?? this.form.title.value
                this.form.description.value = this.record?.description ?? this.form.description.value
                this.form.due_date.value    = this.record?.due_date ?? this.form.due_date.value
                this.form.flag.value        = this.record?.flag ?? this.form.flag.value
            }

            this.$refs.todoMainModal.modalOpen()
        },
        modalClose() {
            this.form   = new Form(this.form_template)
            this.errors = []
            this.$refs.todoMainModal.modalClose()
        },
        setErrors(e) {
            this.errors = e
        },
        saveTodo() {
            if (!this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.loading = true
                        const record = {}
                        for (const key in this.form) {
                            record[key] = this.form[key].value
                        }
                        record.flag     = this.flags.indexOf(record.flag) ?? 3
                        record.id       = this.record?.id || null
                        if (this.record?.user) {
                            record.user_id = this.record?.user.id
                        }
                        if (this.record?.is_done === true) {
                            record.is_done = this.record?.is_done
                        }
                        Requests.saveTodo(record).then(res => {
                            if (res?.status) {
                                this.$helpers.notify(res?.message)
                                this.EventBus.emit('todoInserted', res.result)
                                this.modalClose()
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره کار')
                            }
                        }).catch(err => {
                            console.error(err)
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره کار', {type: 'error'})
                        }).finally(res => {
                            this.loading = false
                        })
                    }
                })
            }
        },
    },
}
</script>

<style scoped>

</style>
