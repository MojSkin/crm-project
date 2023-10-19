<template>
    <AdminLayoutComponent>
        <template #title><span class="sahel">گالری تصاویر</span><span class="ml-2 sahel has-text-info">{{ grade_record?.title }}</span></template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <RouterLink :to="{ name: 'admin.grades' }" class="button border-0 is-outlined text-accent hint--light hint--rounded hint--top" data-hint="بازگشت به فهرست مقاطع تحصیلی" v-if="!newRec && !editing && !showGallery">
                        <i class="far fa-chevron-right mt-1"></i>
                    </RouterLink>
                    <button @click="cancelForm" class="button border-0 is-outlined text-accent hint--light hint--rounded hint--top" data-hint="بازگشت به فهرست گالری‌ها" v-if="showGallery">
                        <i class="far fa-chevron-right mt-1"></i>
                    </button>
                    <button @click="newGallery" v-if="permission.add && !showGallery" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>گالری جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing && !showGallery" data-hint="دریافت مجدد فهرست مقاطع تحصیلی">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <span class="sahel is-weight-700 has-text-info ml-3" v-if="gallery_record?.title" v-text="'گالری '+gallery_record?.title"/>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!showGallery && !newRec && !editing" :body-class="{'p-0': (!newRec && !editing && !showGallery), 'p-5': newRec || editing}" v-if="!newRec && !editing && !showGallery">
            <b-table
                :headers="headers"
                :data="galleries"
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
                <template #active="data">
                    <button class="btn btn-outline has-text-primary px-2" @click="changeStatus(data.item.slug)">
                        <i class="fal" :class="data.value?'fa-check text-success':'fa-times text-danger'"></i>
                    </button>
                </template>
                <template #description="data">
                    <span class="show-more" v-html="data.value"></span>
                </template>
                <template #images="data">
                    <bdi>{{ data?.value?.length || 0 }}</bdi>
                </template>
                <template #action="data">
                    <button class="btn btn-outline has-text-primary px-2" @click="gradeGallery(data.item)">
                        <i class="fas fa-images"></i>
                    </button>
                    <button class="btn btn-outline text-primary px-2" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger px-2" @click="deleteItem(data.item.id)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
        </b-card>
        <b-card no-header class="overflow-visible" v-else-if="editing || newRec">
            <FormContentComponent
                v-model="form"
                :errors="errors"
                ref="form"
                @error="setErrors"
                class="is-flex"
            >
            </FormContentComponent>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveGallery()" :disabled="loading">
                    <i class="fal fa-save" v-if="!loading"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره گالری</span>
                </button>
                <button class="button" @click="cancelForm">انصراف</button>
            </template>
        </b-card>
        <b-card no-header class="overflow-visible" v-else>
            <div class="columns is-multiline">
                <div class="column is-12 pb-2">
                    <dropzone v-model="files" @dropped="fileDropped" :is-busy="saving" accept="image/jpeg, image/png" />
                    <span class="small pl-3">
                        <span>فقط تصاویر با پسوند</span>
                        <span class="mx-2 has-text-primary">jpg / jpeg / png</span>
                        <span>مورد تایید است.</span>
                    </span>
                </div>
                <div class="column is-12 pt-0">
                    <progress class="progress is-success is-tiny" :value="uploadProgress" max="100" v-if="saving"/>
                </div>
                <div class="column is-12 is-flex is-justify-content-end pt-0">
                    <button class="button is-success is-light is-outlined" :disabled="saving" @click="uploadFiles">
                        <i class="fal fa-cloud-upload-alt" v-if="!saving"></i>
                        <i class="fas fa-spinner-third fa-spin" v-else></i>
                        <span class="ml-2">افزودن تصویر به گالری</span>
                    </button>
                </div>
            </div>
            <div class="columns is-multiline">
                <div class="column is-6-mobile is-4-tablet is-3-desktop is-2-widescreen is-flex align-items-center flex-wrap image-thumb-wrapper" v-if="!gallery_record?.images || !gallery_record?.images?.length">
                    <div class="image-thumb">
                        <div class="image is-flex is-align-items-center is-justify-content-center is-flex-direction-column">
                            <span class="fa-stack fa-3x">
                                <i class="fal fa-camera fa-stack-1x"></i>
                                <i class="fas fa-ban fa-stack-2x has-text-grey-dark opacity-50"></i>
                            </span>
                            <span class="small has-text-grey mt-2">تصویری موجود نیست</span>
                        </div>
                        <div class="field has-addons is-disabled">
                            <div class="control is-expanded">
                                <input class="input is-small" type="text" placeholder="عنوان تصویر" disabled="">
                            </div>
                            <div class="control">
                                <a class="button is-static is-small">
                                    <i class="fal fa-check has-text-success mr-3"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6-mobile is-4-tablet is-3-desktop is-2-widescreen is-flex align-items-center flex-wrap image-thumb-wrapper" v-for="(image, imageIndex) in gallery_record?.images">
                    <div class="image-thumb">
                        <button @click.stop="deleteImage(image)">
                            <i class="fal fa-trash-alt"></i>
                        </button>
                        <div class="image" :style="{ 'background-image': 'url('+gallery_address+gallery_record.id+'/'+image.file_name+')' }" @click="index = imageIndex"></div>
                        <div class="field has-addons" :class="{ 'is-disabled': updatingAlts.includes(image.id) }">
                            <div class="control is-expanded">
                                <input v-model="image.alt" class="input is-small" type="text" placeholder="عنوان تصویر" :disabled="updatingAlts.includes(image.id)" @keydown.enter="changeAlt(image)">
                            </div>
                            <div class="control">
                                <a class="button is-light is-small" v-if="!updatingAlts.includes(image.id)" @click="changeAlt(image)">
                                    <i class="fal fa-check has-text-success mx-1 is-clickable"></i>
                                </a>
                                <a class="button is-static is-small" v-else>
                                    <i class="fal fa-spinner-third fa-spin mx-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <template #footer-left>
                <button class="button" @click="cancelForm">بازگشت به فهرست گالری‌ها</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
    <vue-easy-lightbox
        :visible="index != null && index >= 0"
        :imgs="items"
        :index="index"
        min-zoom="0.3"
        max-zoom="2"
        @hide="index = null"
        loop
        rtl
    >
        <template #toolbar="{ toolbarMethods, image }">
            <div class="vel-toolbar">
                <div role="button" aria-label="zoom in button" class="toolbar-btn toolbar-btn__zoomin" @click="toolbarMethods.zoomIn">
                    <i class="fal fa-search-plus"></i>
                </div>
                <div role="button" aria-label="zoom out button" class="toolbar-btn toolbar-btn__zoomout" @click="toolbarMethods.zoomOut">
                    <i class="fal fa-search-minus"></i>
                </div>
                <div role="button" aria-label="zoom out button" class="toolbar-btn toolbar-btn__resize" @click="toolbarMethods.resize">
                    <i class="fal fa-expand-wide"></i>
                </div>
                <div role="button" aria-label="zoom out button" class="toolbar-btn toolbar-btn__rorate" @click="downloadImage(index)">
                    <i class="fal fa-cloud-download-alt"></i>
                </div>
            </div>
        </template>
    </vue-easy-lightbox>
</template>

<script>
import Form from '/resources/js/form'
export default {
    name: "AdminGradesGalleryComponent",
    inject: ['base_url'],
    props: {
        grade: {
            type: String,
            required: true
        }
    },
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
            showGallery: false,
            grade_record: {},
            gallery_record: {},
            gallery: {},
            editingItem: {},
            errors: [],
            galleries: [],
            files: [],
            updatingAlts: [],
            form: {},
            index: null,
            items: [],
            form_template: {
                title:          {title: 'عنوان گالری', col_desktop: 6, col_widescreen: 6, validator: ['required', 'min:5', 'max:250']},
                description:    {title: 'توضیحات', type:2, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['min:10', 'max:2500']},
            },
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'title', title: 'عنوان گالری', sortable: true},
                {id: 'description', title: 'توضیحات'},
                {id: 'images', title: 'تعداد تصاویر', thStyle: 'width: 100px;', tdClass: 'text-center'},
                {id: 'active', title: 'وضعیت', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 50px;', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 135px;', tdStyle: ''},
            ],
            uploadProgress: 0,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
            gallery_address: this.base_url+'/storage/uploads/galleries/'
        }
    },
    created() {
        this.refreshTable()
    },
    mounted() {
    },
    computed: {},
    watch: {
        gallery_record: {
            handler() {
                this.items = []
                const images = this.gallery_record?.images || []
                for (let i = 0; i < images.length; i++) {
                    this.items.push({
                        title:images[i].alt,
                        src: this.gallery_address + this.gallery_record.id + '/' + images[i].file_name
                    })
                }
            },
            deep: true
        }
    },
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getGradeGalleriesList(this.grade).then(res => {
                    if (res?.status) {
                        this.galleries = res?.result?.galleries ?? [];
                        this.grade_record = res?.result?.grade ?? {};
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست گالری‌ها', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست گالری‌ها', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        newGallery() {
            this.loading = false
            this.saving = false
            this.newRec = true
            this.editing = false
            this.showGallery = false
            this.gallery_record = {}
            this.errors = []
            this.form = new Form(this.form_template)
        },
        cancelForm() {
            this.newGallery()
            this.editing = false
            this.newRec = false
        },
        setErrors(e) {
            this.errors = e
        },
        changeStatus(gallery) {
            if (!this.saving && ! this.loading) {
                this.saving = true
                Requests.changeGalleryStatus(gallery).then(res => {
                    if (res?.status) {
                        let gState = false
                        for (let i = 0; i < this.galleries.length; i++) {
                            if (this.galleries[i].slug == gallery) {
                                this.galleries[i].active = !this.galleries[i].active
                                gState = this.galleries[i].active
                                break
                            }
                        }
                        this.$helpers.notify(res?.message || 'گالری مورد نظر با موفقیت '+(gState ? 'فعال' : 'غیرفعال')+' شد.')
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام تغییر وضعیت گالری مورد نظر', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام تغییر وضعیت گالری مورد نظر', { type: 'error' })
                }).finally(res => {
                    this.saving = false
                })
            }
        },
        saveGallery() {
            if (!this.saving && ! this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.saving = true
                        const formData = {
                            gallery: this.editing ? this.editingItem.slug : null,
                            grade: this.grade,
                            title: this.form.title.value,
                            description: this.form.description.value,
                        }
                        Requests.saveGallery(formData).then(res => {
                            if (res?.status) {
                                if (this.editing) {
                                    this.galleries[this.editingItem.index] = res.result
                                } else {
                                    this.galleries.push(res.result)
                                }

                                this.cancelForm()

                                this.$helpers.notify(res?.message || 'گالری مورد نظر با موفقیت ذخیره شد.')
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره گالری مورد نظر', { type: 'error' })
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره گالری مورد نظر', { type: 'error' })
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
            this.newGallery()
            this.newRec = false
            this.editingItem = item
            this.editingItem.index = -1

            for (let i = 0; i < this.galleries.length; i++) {
                if (this.galleries[i].slug == item.slug) {
                    this.editingItem.index = i
                    break
                }
            }

            this.editing = true
            this.form.title.value = this.editingItem.title
            this.form.description.value = this.editingItem.description
        },
        gradeGallery(item) {
            this.newGallery()
            this.newRec = false
            this.showGallery = true
            this.gallery_record = item
            this.updatingAlts = []
        },
        fileDropped(e) {
            this.files = e
        },
        uploadFiles() {
            if (this.files.length) {
                this.saving = true
                let formData = new FormData()
                formData.append("gallery", this.gallery_record.slug);
                this.files.forEach((file, index) => {
                    formData.append("file-"+index+1, file);
                });
                Requests.addFileToGallery(formData, (progressEvent) => {
                    this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                }).then(res => {
                    this.gallery_record.images = res?.result?.images || []
                    for (let i = 0; i < this.galleries.length; i++) {
                        if (this.galleries[i].slug === this.gallery_record.slug) {
                            this.galleries[i].images = this.gallery_record.images
                            break;
                        }
                    }
                }).catch(err => {
                    console.log('error')
                }).finally(res => {
                    this.uploadProgress = 0
                    this.files = []
                    this.saving = false
                })
            } else {
                this.$helpers.notify('خطای کاربر', 'حداقل یک فایل تصویر برای افزودن به گالری انتخاب کنید...', {type: 'error'})
            }
        },
        downloadImage(imageIndex) {
            let img = document.createElement('a')
            img.setAttribute("href", this.gallery_address + this.gallery_record.id + '/' + this.gallery_record.images[imageIndex].file_name);
            const filenameArray = this.gallery_record.images[imageIndex].file_name.split('.')
            const ext           = filenameArray.length > 1 ? '.'+filenameArray.slice(-1).pop() : ''
            img.setAttribute("download", this.gallery_record.images[imageIndex].alt+ext);
            document.body.appendChild(img);
            img.click();
            img.remove();
        },
        changeAlt(image) {
            if (!this.updatingAlts.includes(image.id)) {
                if (image.alt?.trim()?.length > 1) {
                    this.updatingAlts.push(image.id)
                    const formData = {
                        gallery: this.gallery_record.slug,
                        image: image.id,
                        alt: image.alt
                    }
                    Requests.renameAlt(formData).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.gallery_record.images.length; i++) {
                                if (this.gallery_record.images[i].id === image.id) {
                                    this.gallery_record.images[i].alt = image.alt
                                    for (let j = 0; j < this.updatingAlts.length; j++) {
                                        if (this.updatingAlts[j] === image.id) {
                                            this.updatingAlts.splice(j, 1)
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                            for (let i = 0; i < this.galleries.length; i++) {
                                if (this.galleries[i].slug === this.gallery_record.slug) {
                                    this.galleries[i].images = this.gallery_record.images
                                    break;
                                }
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ویرایش عنوان تصویر', { type: 'error' })
                        }
                    }).catch(err => {
                        console.error(err)
                        this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام ویرایش عنوان تصویر', { type: 'error' })
                    }).finally(() => {
                        this.loading = false
                    })
                } else {
                    this.$helpers.notify('خطا', 'عنوان تصویر نمی‌تواند خالی باشد', { type: 'error' })
                }
            }
        },
        deleteImage(image) {
            if (!this.updatingAlts.includes(image.id)) {
                this.$swal.fire({
                    title: "حذف تصویر",
                    text: "تصویر مورد نظر حذف گردد؟",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "بلی",
                    cancelButtonText: "خیر",
                    showCloseButton: true,
                }).then(result => {
                    if (result.value) {
                        this.updatingAlts.push(image.id)
                        const formData = {
                            gallery: this.gallery_record.slug,
                            image: image.id,
                        }

                        Requests.deleteImage(formData).then(res => {
                            if (res?.status) {
                                for (let i = 0; i < this.gallery_record?.images?.length; i++) {
                                    if (this.gallery_record.images[i].id == image.id) {
                                        this.gallery_record.images.splice(i, 1);
                                        this.$helpers.notify('تصویر مورد نظر با موفقیت حذف گردید.')
                                        break
                                    }
                                }
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام حذف تصویر مورد نظر', {type: "error"})
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام حذف تصویر مورد نظر', { type: 'error' })
                            console.log(err);
                        }).finally(res => {
                            for (let i = 0; i < this.updatingAlts.length; i++) {
                                if (this.updatingAlts[i] === image.id) {
                                    this.updatingAlts.splice(i, 1)
                                }
                            }
                        })
                    }
                });
            }
        },
    }
}
</script>

<style scoped>
.image-thumb {
    position: relative;
    aspect-ratio: 1/1;
    background-image: linear-gradient(to top, #faae42, #ededed);
    padding: 6px 6px 0 6px;
    border-radius: 7%;
    transition: 200ms all ease-in-out;
}
.image-thumb .image {
    width: 100%;
    height: calc(100% - 40px);
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    overflow: hidden;
    border-radius: 7%;
    background-color: rgba(221, 228, 253, 0.35);
    margin-bottom: 5px;
    cursor: zoom-in;
    pointer-events: auto;
    transition: 200ms all ease-in-out;
}
.image-thumb:hover {
    box-shadow: 0 0 5px #646464;
    scale: 1.05;
}
.image:hover {
    box-shadow: 0 0 2px #161c76;
}
.image-thumb .input,
.image-thumb .control .button {
    padding: 2px 5px !important;
    height: 30px;
}
.is-small.textarea, .is-small.input {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}
.field.has-addons .control:last-child:not(:only-child) .button {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.image-thumb button {
    position: absolute;
    background-color: rgba(240, 240, 240, 0.7);
    border: none;
    color: #e62965;
    width: 32px;
    height: 32px;
    padding: 4px 2px 0 2px;
    border-radius: 50%;
    left: -12px;
    top: -12px;
    transition: all 200ms ease-in-out;
    box-shadow: 0 0 5px #cfcfcf;
    z-index: 3;
}
.image-thumb button:hover {
    background-color: #e62965;
    color: rgba(250, 250, 250, 1);
}

</style>

<style>
.vel-img-title {
    padding: 7px 15px;
    background-color: #FFF !important;
    border-radius: 5px !important;
    color: #0A0A0A !important;
    font-size: 1rem !important;
}

</style>
