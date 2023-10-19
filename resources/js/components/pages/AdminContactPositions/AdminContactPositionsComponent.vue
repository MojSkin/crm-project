<template>
    <AdminLayoutComponent>
        <template #title>جایگاه اشخاص</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newItem" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>جایگاه جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست فرم‌ها">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing}">
            <b-table
                :headers="headers"
                :data="positions"
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
                <template #action="data">
                    <button class="btn btn-outline text-primary" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger" @click="deleteItem(data.item.id)">
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
            />
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveItem()" :disabled="loading">
                    <i class="fal fa-save" v-if="!loading"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره جایگاه</span>
                </button>
                <button class="button" @click="form={}; newRec=false; editing=false">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
</template>

<script>
import Form from '/resources/js/form'
export default {
    name: "AdminContactPositionsComponent",
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
            errors: [],
            form: [],
            positions: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 8%'},
                {id: 'title', title: 'عنوان جایگاه', thClass: '', tdClass: '', thStyle: '', tdStyle: '', sortable: true},
                {id: 'description', title: 'توضیحات جایگاه', thClass: '', tdClass: '', thStyle: '', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 100px;', tdStyle: ''},
            ],
            form_template: {
                title:       {title: 'عنوان جایگاه', is_required: true, col_desktop: 6, col_widescreen: 6, validator: ['required', 'min:2', 'max:30']},
                description: {title: 'توضیحات جایگاه', type: 2, col_desktop: 12, col_widescreen: 12, validator: ['max:150'], description: 'حداکثر 150 حرف'},
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
                Requests. getPositionsList().then(res => {
                    if (res?.status) {
                        this.positions = res?.result ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست جایگاه اشخاص', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست جایگاه اشخاص', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        newItem() {
            this.form    = new Form(this.form_template)
            this.errors  = []
            this.newRec  = true
            this.editing = false
            this.editingItem = []
        },
        saveItem() {
            if (!this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.loading = true
                        const record = {}
                        for (const key in this.form) {
                            record[key] = this.form[key].value
                        }
                        if (this.editing) {
                            record.id = this.editingItem.id
                        }
                        Requests.savePosition(record).then(res => {
                            if (res?.status) {
                                if (this.editing) {
                                    for (let i = 0; i < this.positions.length; i++) {
                                        if (this.positions[i].id === this.editingItem.id) {
                                            this.positions[i] = res.result
                                            break;
                                        }
                                    }
                                } else {
                                    this.positions.push(res.result)
                                }
                                this.$helpers.notify(res?.message || 'جایگاه مورد نظر با موفقیت ذخیره شد')
                                this.newItem()
                                this.newRec = false
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره جایگاه')
                            }
                        }).catch(err => {
                            console.error(err)
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره جایگاه')
                        }).finally(res => {
                            this.loading = false
                        })
                    }
                })
            }
        },
        editItem(item) {
            this.newItem()
            this.newRec = false
            this.editingItem = item
            this.editing = true
            this.form.title.value = this.editingItem.title
            this.form.description.value = this.editingItem.description
        },
        deleteItem(item_id) {
            this.$swal.fire({
                title: "حذف جایگاه",
                html: "حذف جایگاه یک عملیات غیرقابل بازگشت بوده و علاوه بر حذف آن، از تمامی پروژه‌ها (املاک) نیز برداشته خواهد شد.<span class='ml-2 has-text-danger has-text-weight-bold'>جایگاه مورد نظر حذف گردد؟</span>",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "بلی",
                cancelButtonText: "خیر",
                showCloseButton: true,
            }).then(result => {
                if (result.value) {
                    this.saving = true
                    Requests.deletePosition(item_id).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.positions.length; i++) {
                                if (this.positions[i].id == item_id) {
                                    this.positions.splice(i, 1)
                                    break;
                                }
                            }
                            this.$helpers.notify('جایگاه مورد نظر با موفقیت حذف گردید.')
                        } else {
                            this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف جایگاه مورد نظر با مشکل مواجه شد...', {
                                type: "error",
                            })
                        }
                    }).catch(err => {
                        console.log(err);
                    }).then(res => {
                        this.saving = false
                    })
                }
            });
        },
        setErrors(e) {
            this.errors = e
        },
    }
};
</script>

<style scoped>

</style>
