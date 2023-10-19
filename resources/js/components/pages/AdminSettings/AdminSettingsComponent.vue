<template>
    <AdminLayoutComponent>
        <template #title>تنظیمات سیستم</template>
        <b-card no-header body-class="px-0" class="is-relative">
            <b-overlay :show="showOverlay">
                <b-tabs v-model="active_tab" :headers="tabs">
                    <template v-slot:[active_tab]>
                        <div class="mx-5">
                            <FormContentComponent
                                v-model="form"
                                :errors="[]"
                            >
                                <template #update_page_title>
                                    <div class="column is-6-desktop is-6-widescreen is-12">
                                        <b-input
                                            :label="' '"
                                            :description="form.update_page_title.description"
                                            :hint="form.update_page_title.hint"
                                            :placeholder="form.update_page_title.placeholder"
                                            :id="form.update_page_title.custom_class || form.update_page_title.name"
                                            :name="form.update_page_title.name"
                                        >
                                            <template #input>
                                                <b-switch
                                                    v-model="form.update_page_title.value"
                                                    :label="''"
                                                    :checked="form.update_page_title.value"
                                                    key="updatePageTitle"
                                                >
                                                    <div class="is-flex is-align-items-center">
                                                        <div class="is-inline-block mr-2">عنوان هر بخش به عنوان سایت</div>
                                                        <div class="is-inline-block is-weight-700 has-text-success" v-if="form.update_page_title.value == 1">اضافه شود</div>
                                                        <div class="is-inline-block is-weight-700 has-text-danger" v-else>اضافه نشود</div>
                                                    </div>
                                                </b-switch>
                                            </template>
                                        </b-input>
                                    </div>
                                </template>
                                <template #theme>
                                    <div class="column is-6-desktop is-6-widescreen is-12">
                                        <b-select
                                            :label="form.theme.title+(form.theme.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                                            :description="form.theme.description"
                                            :hint="form.theme.hint"
                                            :options="[
                                                {value: 'light', text: 'روشن'},
                                                {value: 'dark', text: 'تاریک'},
                                                {value: 'system', text: 'تشخیص خودکار بر اساس تنظیمات سیستم'},
                                            ]"
                                            :id="form.theme.custom_id || form.theme.name"
                                            :name="form.theme.name"
                                            v-model="form.theme.value"
                                        />
                                    </div>
                                </template>
                                <template #default_role>
                                    <div class="column is-6-desktop is-6-widescreen is-12">
                                        <b-input
                                            :label="form.default_role.title+(form.default_role.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                                            :description="form.default_role.description"
                                            :hint="form.default_role.hint"
                                            :placeholder="form.default_role.placeholder"
                                            :id="form.default_role.custom_class || form.default_role.name"
                                            :name="form.default_role.name"
                                        >
                                            <template #input>
                                                <b-select
                                                    :options="['گزینه یک', 'گزینه دو', 'گزینه سه']"
                                                ></b-select>
                                            </template>
                                        </b-input>
                                    </div>
                                </template>
                            </FormContentComponent>
                        </div>
                    </template>
                </b-tabs>
            </b-overlay>
            <template #footer-left>
                <div class="column is-12 is-flex is-flex-direction-row is-justify-content-flex-end">
                    <button class="button is-success mr-2" @click="saveSettings()" :disabled="loading">
                        <i class="fal fa-save" v-if="!loading"></i>
                        <i class="fal fa-spinner-third fa-spin" v-else></i>
                        <span class="ml-2">ذخیره تغییرات</span>
                    </button>
                    <button class="button" @click="refreshTable()">انصراف</button>
                </div>
            </template>
        </b-card>
    </AdminLayoutComponent>
</template>

<script>

import Form from '/resources/js/form'

export default {
    name: "AdminSettingsComponent",
    props: {},
    data() {
        return {
            showOverlay: true,
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            form_data: [],
            form: [],
            active_tab: '',
            tabs: [
                {
                    id: 'general',
                    text: 'تنظیمات عمومی',
                    template: {
                        'site_name' : { title: 'عنوان سایت', col_desktop: 6,  col_widescreen: 6,  min: 5, max: 250, is_required: true, validator: ['required', 'min:5', 'max:250'] },
                        'site_description' : { title: 'توضیحات سایت', col_desktop: 6,  col_widescreen: 6, min: 15, max: 250, validator: ['max:250'] },
                        'site_keywords' : { title: 'کلمات کلیدی سایت', col_desktop: 12,  col_widescreen: 12, description: 'کلمات کلیدی و موثر در جستجو را وارد کرده و برای جدا کننده از پایپ ( | ) استفاده کنید. حداکثر 500 کاراکتر', max: 500, validator: ['max:500']},
                        'logo_light' : { title: 'لوگو با طرح روشن', col_desktop: 6,  col_widescreen: 6, min: 15, max: 250, validator: ['min:15', 'max:250'], custom_class: 'is-ltr' },
                        'logo_dark' : { title: 'لوگو با طرح تیره', col_desktop: 6,  col_widescreen: 6, min: 15, max: 250, validator: ['min:15', 'max:250'], custom_class: 'is-ltr' },
                        'theme' : { title: 'طرح رنگ‌بندی', col_desktop: 6,  col_widescreen: 6, is_required: true, type: 9, validator: ['required'] },
                        'default_role' : { title: 'نقش کاربری پیش‌فرض', col_desktop: 6,  col_widescreen: 6, is_required: true, type: 9, validator: ['required'] },
                        'update_page_title' : { title: 'افزودن عنوان هر بخش به عنوان سایت', col_desktop: 6,  col_widescreen: 6, type: 7 },
                    },
                },
                {
                    id: 'footer',
                    text: 'تنظیمات پابرگ',
                    template: {
                        'widget_right'  : { title: 'ویجت سمت راست', description: 'کدهای HTML قابل قبول است', type: 2, col_desktop: 6,  col_widescreen: 6, custom_class: "is-ltr"},
                        'widget_center' : { title: 'ویجت میانی', description: 'کدهای HTML قابل قبول است', type: 2, col_desktop: 6,  col_widescreen: 6, custom_class: "is-ltr"},
                        'widget_left'   : { title: 'ویجت سمت چپ', description: 'کدهای HTML قابل قبول است', type: 2, col_desktop: 6,  col_widescreen: 6, custom_class: "is-ltr"},
                    },
                }
            ]
        }
    },
    created() {
        this.showOverlay = true
        for (let i = 0; i < this.tabs.length; i++) {
            for (const key in this.tabs[i].template) {
                this.form_data[key] = this.tabs[i].template[key]
            }
        }
        this.form_data = new Form(this.form_data)
    },
    mounted() {
        this.refreshTable()
    },
    computed: {},
    watch: {
        active_tab() {
            for (const key in this.form) {
                this.form_data[key].value = this.form[key].value
            }

            for (let i = 0; i < this.tabs.length; i++) {
                if (this.active_tab === this.tabs[i].id) {
                    this.form = new Form(this.tabs[i].template)
                    break;
                }
            }

            for (const key in this.form) {
                this.form[key].value = this.form_data[key].value
            }
        }
    },
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.showOverlay = true
                this.loading = true
                Requests.getSettingsData().then(res => {
                    if (res?.status) {
                        for (const key in res?.result) {
                            this.form_data[key].value = res?.result[key]
                        }
                        this.active_tab = 'general'
                    } else {
                        this.$helpers.notify('خطا', 'بروز خطا هنگام دریافت تنظیمات سیستم', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', 'بروز خطای ناشناخته هنگام دریافت تنظیمات سیستم', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
            }
        },
        saveSettings() {
            if (!this.loading) {
                this.loading = true
                let form = {}
                for (const key in this.form) {
                    this.form_data[key].value = this.form[key].value
                }
                for (const key in this.form_data) {
                    form[key] = this.form_data[key].value
                }
                Requests.setSettingsData(form).then(res => {
                    if (res?.status) {
                        this.$helpers.notify('تنظیمات سیستم بروزرسانی شد.')
                        window.location.reload()
                    } else {
                        this.$helpers.notify('خطا', 'بروز خطا هنگام ذخیره تنظیمات سیستم', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطا', 'بروز خطا هنگام ذخیره تنظیمات سیستم', { type: 'error' })
                }).finally(() => {
                    this.showOverlay = false
                    this.loading = false
                })
            }
        },
    }
}
</script>

<style scoped>

</style>
