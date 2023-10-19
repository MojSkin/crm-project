<template>
    <AdminLayoutComponent>
        <template #title>مقاطع تحصیلی</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newGrade" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>مقطع جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست مقاطع">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing}" v-if="!newRec && !editing">
            <b-table
                :headers="headers"
                :data="grades"
                :is-busy="loading"
                table-header-class="bg-light"
                :current-page="currentPage"
                :per-page="perPage"
                :search-field="filter"
                striped
                hoverable
                ref="dataTable"
                key="formsDataTable"
            >
                <template #index="data">
                    {{ ((currentPage - 1) * perPage) + data.item.index + 1 }}
                </template>
                <template #sections="data">
                    <button class="btn btn-outline hint--light hint--rounded hint--top" data-hint="پایه‌های تحصیلی" @click="levels(data.item)">
                        <i class="fas fa-users-class has-text-primary"></i>
                    </button>
                    <RouterLink :to="{ name: 'admin.grades-gallery', params: { grade: data.item.slug } }" class="btn btn-outline hint--light hint--rounded hint--top" data-hint="گالری تصاویر">
                        <i class="fas fa-images has-text-info"></i>
                    </RouterLink>
                    <RouterLink :to="{ name: 'admin.grades-slider', params: { grade: data.item.slug } }" class="btn btn-outline hint--light hint--rounded hint--top" data-hint="اسلایدر">
                        <i class="fas fa-image has-text-warning"></i>
                    </RouterLink>
                    <button class="btn btn-outline hint--light hint--rounded hint--top" data-hint="مقالات و اخبار" @click="blog(data.item)">
                        <i class="fas fa-book-reader has-text-grey"></i>
                    </button>
                </template>
                <template #active="data">
                    <i class="fal" :class="data.value?'fa-check text-success':'fa-times text-danger'"></i>
                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary px-2" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger px-2" @click="deleteItem(data.item.id)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
        </b-card>
        <b-card no-header class="overflow-visible" v-else>
            <FormContentComponent
                v-model="form"
                :errors="errors"
                ref="form"
                @error="setErrors"
                class="is-flex"
            >
                <template #bg_color>
                    <div class="column is-6-mobile is-6-tablet is-2-widescreen is-2-desktop">
                        <b-input
                            :label="form.bg_color.title+(form.bg_color.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="form.bg_color.value" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #text_color>
                    <div class="column is-6-mobile is-6-tablet is-2-widescreen is-2-desktop">
                        <b-input
                            :label="form.text_color.title+(form.text_color.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="form.text_color.value" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #sample>
                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                        <b-input
                            label="پیش‌نمایش"
                        >
                            <template #input>
                                <span class="input" :style="{ 'background-color': form.bg_color.value, 'color': form.text_color.value, }" v-text="'این یک متن پیش‌نمایش است.'"/>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #image v-if="editing && form.image.value && form.image.value?.length">
                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                        <b-input
                            label="پیش‌نمایش"
                        >
                            <template #input>
                                <div class="h-avatar is-xl">
                                    <img class="avatar" :src="prepareGradeImage(form.image.value)" alt="">
                                    <button class="badge button is-rounded is-warning border-1 d-block px-0" @click="form.image.value = null">
                                        <i class="fal fa-times"></i>
                                    </button>
                                </div>
                            </template>
                        </b-input>
                    </div>
                </template>
            </FormContentComponent>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveGrade()" :disabled="loading">
                    <i class="fal fa-save" v-if="!loading"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره مقطع</span>
                </button>
                <button class="button" @click="cancelForm">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
</template>

<script>
import Form from '/resources/js/form'

export default {
    name: "AdminGradesComponent",
    props: {},
    inject: ['base_url'],
    data() {
        return {
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            saving: false,
            newRec: false,
            editing: false,
            editingItem: {},
            errors: [],
            grades: [],
            form: {},
            color: null,
            form_template: {
                title:          {title: 'عنوان مقطع', col_mobile: 6, col_tablet: 6, col_desktop: 6, col_widescreen: 6, validator: ['required', 'min:2', 'max:250']},
                slogan:         {title: 'شعار / توضیحات', col_mobile: 6, col_tablet: 6, col_desktop: 6, col_widescreen: 6, validator: ['min:5', 'max:150']},
                description:    {title: 'معرفی مقطع', description: 'کدهای HTML قابل قبول است', type: 2, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['min:10', 'max:2500']},
                bg_color:       {title: 'رنگ پس‌زمینه', type: 1, default: 'rgba(0, 0, 0, 0)', is_required: true, validator: ['required'], col_mobile: 6, col_tablet: 6, col_desktop: 2, col_widescreen: 2},
                text_color:     {title: 'رنگ قلم', type: 1, default: 'rgba(70, 70, 70, 1)', is_required: true, validator: ['required'], col_mobile: 6, col_tablet: 6, col_desktop: 2, col_widescreen: 2},
                sample:         {title: 'پیش‌نمایش', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3},
                image:          {title: 'تصویر پس‌زمینه', type: 15, options: '.jpg, .png', col_mobile: 6, col_tablet: 6, col_desktop: 4, col_widescreen: 4},
            },
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'title', title: 'عنوان مقطع', sortable: true},
                {id: 'sections', title: 'زیرمجموعه‌ها'},
                {id: 'active', title: 'وضعیت', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 50px;', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 120px;', tdStyle: ''},
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
        }
    },
    created() {
        this.refreshTable()
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getGradesList().then(res => {
                    if (res?.status) {
                        this.grades = res?.result ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست مقاطع تحصیلی', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست مقاطع تحصیلی', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        newGrade() {
            this.loading =  false
            this.saving =  false
            this.newRec =  true
            this.editing =  false
            this.errors = []
            this.form = new Form(this.form_template)
        },
        cancelForm() {
            this.newGrade()
            this.editing = false
            this.newRec = false
        },
        setErrors(e) {
            this.errors = e
        },
        saveGrade() {
            if (!this.saving && ! this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.saving = true
                        const formData = {
                            id: this.editing ? this.editingItem.id : null,
                            title: this.form.title.value,
                            slogan: this.form.slogan.value,
                            description: this.form.description.value,
                            image: this.form?.image?.value ? (this.form?.image?.value[0]?.imageUrl || null) : -1,
                            bg_color: this.form.bg_color.value,
                            text_color: this.form.text_color.value,
                        }
                        Requests.saveGrade(formData).then(res => {
                            if (res?.status) {
                                if (this.editing) {
                                    for (let i = 0; i < this.grades.length; i++) {
                                        if (this.grades[i].id == res.result.id) {
                                            this.grades[i] = res.result
                                            break
                                        }
                                    }
                                } else {
                                    this.grades.push(res.result)
                                }

                                this.cancelForm()

                                this.$helpers.notify(res?.message || 'مقطع مورد نظر با موفقیت ذخیره شد.')
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره مقطع مورد نظر', { type: 'error' })
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره مقطع مورد نظر', { type: 'error' })
                        }).finally(res => {
                            this.saving = false
                        })
                    } else {
                        this.$helpers.notify('خطای ورود اطلاعات', 'قبل از ذخیره کردن فرم، باید خطاهای فرم را برطرف کنید.', {type: 'warning'})
                    }
                })
            }
        },
        editItem(item) {
            this.newGrade()
            this.newRec = false
            this.editingItem = item
            this.editing = true
            this.form.title.value = this.editingItem.title
            this.form.slogan.value = this.editingItem.slogan
            this.form.description.value = this.editingItem.description
            this.form.image.value = (this.editingItem.images && this.editingItem.images.length) ? this.editingItem.images : null
            this.form.bg_color.value = this.editingItem.bg_color
            this.form.text_color.value = this.editingItem.text_color
        },
        prepareGradeImage(image) {
            console.log(image)
            if (typeof image === 'object') {
                image = image[0]
            }
            if (image?.imageUrl) {
                return image?.imageUrl
            }
            return image?.file_name ? this.base_url + '/storage/uploads/images/' + image?.file_name : null;
        }
    }
}
</script>

<style>
.vc-color-wrap.round {
    margin-top: 6px;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50%;
    border: 1px solid #d8d8d8;
}</style>
