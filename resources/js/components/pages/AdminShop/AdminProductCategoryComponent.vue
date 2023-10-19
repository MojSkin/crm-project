<template>
    <AdminLayoutComponent>
        <template #title>دسته‌بندی محصولات</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="parent_category=null; action = 'category'" v-if="action === 'sub-category' && !(newRec || editing)" class="button is-transparent font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                    <button @click="newCategory" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>دسته‌بندی جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing && !parent_category?.id" data-hint="دریافت مجدد فهرست فرم‌ها">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <span class="ml-2 is-weight-700" v-if="parent_category">
                        <span class="sahel mr-2">زیر دسته‌های</span>
                        <span class="sahel hide-extra has-text-info" style="width: 150px">{{ parent_category.title }}</span>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing}">
            <b-table
                :headers="headers"
                :data="action==='category' ? categories : parent_category.children"
                :is-busy="loading"
                table-header-class="bg-light"
                :current-page="currentPage"
                :per-page="perPage"
                :search-field="filter"
                striped
                hoverable
                ref="dataTable"
                key="formsDataTable"
                v-if="!newRec && !editing"
            >
                <template #index="data">
                    {{ ((currentPage - 1) * perPage) + data.item.index + 1 }}
                </template>
                <template #children="data">
                    <button class="btn btn-outline text-accent hint--light hint--rounded hint--top"
                            :data-hint="'مشاهده / ویرایش زیر دسته‌ها ('+(data?.item?.children?.length > 0 ? data?.item?.children?.length : '-')+')'"
                            @click="parent_category=data.item; action='sub-category'"
                    >
                        <i class="fal fa-bars"></i>
                    </button>
                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger" @click="deleteItem(data.item)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
            <FormContentComponent
                v-model="form"
                :errors="errors"
                ref="form"
                @error="setErrors"
                v-else
            >
                <template #is_self>
                    <div class="column is-6-desktop is-6-widescreen is-12">
                        <b-input
                            :label="' '"
                            :description="form.is_self.description"
                            :hint="form.is_self.hint"
                            :placeholder="form.is_self.placeholder"
                            :id="form.is_self.custom_class || form.is_self.name"
                            :name="form.is_self.name"
                        >
                            <template #input>
                                <b-switch
                                    v-model="form.is_self.value"
                                    :label="''"
                                    :checked="form.is_self.value==true"
                                >
                                    <div class="is-flex is-align-items-center">
                                        <div class="is-inline-block mr-2">این دسته‌بندی مربوط به سلف سرویس</div>
                                        <div class="is-inline-block is-weight-700 has-text-success" v-if="form.is_self.value == 1">است</div>
                                        <div class="is-inline-block is-weight-700 has-text-danger" v-else>نیست</div>
                                    </div>
                                </b-switch>
                            </template>
                        </b-input>
                    </div>
                </template>
            </FormContentComponent>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveCategory()" :disabled="loading">
                    <i class="fal fa-save" v-if="!loading"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره دسته‌بندی</span>
                </button>
                <button class="button" @click="form={}; newRec=false; editing=false">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
</template>

<script>
import Form from '/resources/js/form'
export default {
    name: "AdminProductCategoryComponent",
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
            category_id: null,
            parent_category: null,
            action: 'category',
            errors: [],
            form: [],
            categories: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 8%'},
                {id: 'title', title: 'عنوان دسته‌بندی', thClass: '', tdClass: '', thStyle: '', tdStyle: '', sortable: true},
                {id: 'description', title: 'توضیحات دسته‌بندی', thClass: '', tdClass: '', thStyle: '', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 100px;', tdStyle: ''},
            ],
            form_template: {
                title:             {title: 'عنوان دسته‌بندی', col_desktop: 6, col_widescreen: 6, validator: ['required', 'min:2', 'max:250']},
                slug:              {title: 'اسلاگ', col_desktop: 6, col_widescreen: 6, validator: ['max:150']},
                description:       {title: 'توضیحات دسته‌بندی', type: 2, col_desktop: 12, col_widescreen: 12, validator: ['max:250']},
            },
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
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getCategoriesList().then(res => {
                    if (res?.status) {
                        this.categories = res?.result ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست دسته‌بندی‌های کالا', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست دسته‌بندی‌های کالا', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        newCategory() {
            this.form    = new Form(this.form_template)
            this.errors  = []
            this.newRec  = true
            this.editing = false
        },
        saveCategory() {
            if (!this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.loading = true
                        const category = {}
                        for (const key in this.form) {
                            category[key] = this.form[key].value
                        }
                        if (this.category_id) {
                            category.id = this.category_id
                        }
                        if (this.parent_category) {
                            category.product_category_id = this.parent_category.id
                        }
                        Requests.saveCategory(category).then(res => {
                            if (res?.status) {
                                if (this.parent_category) {
                                    this.parent_category.children.push(res?.result)
                                    for (let i = 0; i < this.categories.length; i++) {
                                        if (this.categories[i].id === this.parent_category.id) {
                                            this.categories[i].id = this.parent_category.id
                                            break;
                                        }
                                    }
                                } else {
                                    this.categories.push(res?.result)
                                }
                                this.$helpers.notify(res?.message || 'دسته‌بندی کالایی مورد نظر با موفقیت ذخیره شد')
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره دسته‌بندی کالا')
                            }
                        }).catch(err => {
                            console.error(err)
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره دسته‌بندی کالا')
                        }).finally(res => {
                            this.loading = true
                        })
                    }
                })
            }
        },
        setErrors(e) {
            this.errors = e
        },
    }
};
</script>

<style scoped>

</style>
