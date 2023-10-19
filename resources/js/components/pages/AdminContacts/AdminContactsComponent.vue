<template>
    <AdminLayoutComponent>
        <template #title>اشخاص</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newContact" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>مخاطب جدید</span>
                    </button>
                    <button @click="refreshTable(false)" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست اشخاص">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing, 'overflow-visible': true}" v-if="!newRec && !editing" class="overflow-visible">
            <b-accordion
                :headers="[
                    {
                        id: 'searchPanel',
                        title: 'جستجوی پیشرفته',
                    }
                ]"
                is-exclusive
            >
                <template #content-searchPanel>
                    <div class="columns is-multiline">
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='عنوان مخاطب:'
                            >
                                <template #input>
                                    <b-select
                                        autoclose
                                        clearable
                                        label='عنوان مخاطب:'
                                        :options="prefixes"
                                        v-model="filters.prefix"
                                    >
                                        <template #label="item">
                                            {{ item.title }}
                                        </template>
                                    </b-select>
                                </template>
                            </b-input>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='نام مخاطب:'
                                v-model="filters.fName"
                                placeholder="نام مخاطب"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='نام خانوادگی مخاطب:'
                                v-model="filters.lName"
                                placeholder="نام خانوادگی مخاطب"
                            ></b-input>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='نام مستعار:'
                                v-model="filters.nickname"
                                placeholder="نام مستعار"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='عنوان:'
                                v-model="filters.title"
                                placeholder="عنوان"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-12-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='نام تجاری:'
                                v-model="filters.org"
                                placeholder="نام تجاری"
                            ></b-input>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label='تلفن:'
                                v-model="filters.phone"
                                placeholder="تلفن"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label='ایمیل:'
                                v-model="filters.email"
                                placeholder="ایمیل"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label='آدرس:'
                                v-model="filters.address"
                                placeholder="آدرس"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label='سایر مشخصات:'
                                v-model="filters.other"
                                placeholder="سایر مشخصات"
                            ></b-input>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12 is-flex">
                            <button class="button is-success mr-2 ml-auto" @click="refreshTable(filters)" :disabled="loading">
                                <i class="fal fa-filter" v-if="!loading"></i>
                                <i class="fal fa-spinner-third fa-spin" v-else></i>
                                <span class="ml-2">اعمال شرایط</span>
                            </button>
                            <button class="button" @click="cancelFilter">پاک کردن شرایط</button>
                        </div>
                    </div>
                </template>
            </b-accordion>
            <b-table
                :headers="headers"
                :data="contacts"
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
                <template #name="data">
                    <div class="is-flex is-align-items-center w-100">
                        <div class="h-avatar">
                            <img class="avatar" :src="data?.item?.avatar" v-if="data?.item?.avatar" :alt="data?.item?.name || ''">
                            <span class="avatar is-fake has-text-grey-light is-weight-700" v-else><i class="fal fa-address-card"></i></span>
                        </div>
                        <span class="ml-2">{{ data.item.name }}</span>
                        <span class="tag ml-2" v-for="tag in data.item.tags" v-text="tag.title" :style="{backgroundColor: tag.bgColor, color: tag.textColor}"/>
                    </div>

                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary px-2" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger px-2 mr-2" @click="deleteItem(data.item.id)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                    <b-action direction="right" :ref="'tagDropdown-'+data.item.id">
                        <div class="dropdown-item is-media is-clickable d-flex" v-for="tag in tags">
                            <div class="d-flex" @click.stop="setContactTag(data.item.id, tag.id)">
                                <div class="icon mr-2">
                                    <i class="fas fa-tag is-size-5" :style="{ color: tag.bgColor }"></i>
                                </div>
                                <div class="meta mr-4">
                                    <span v-text="tag.title" class="my-2"/>
                                </div>
                            </div>
                            <i class="fal fa-pencil has-text-grey-light ml-auto" @click.self="updateContactTag(tag)"></i>
                            <i class="fal fa-trash-alt has-text-danger ml-2" @click.self="deleteContactTag(tag.id)"></i>
                        </div>
                        <hr class="dropdown-divider">
                        <div class="dropdown-item is-media is-clickable d-flex" @click="updateContactTag(null)">
                            <div class="icon">
                                <i class="fas fa-plus is-size-5"></i>
                            </div>
                            <div class="meta ml-4">
                                <span class="my-2">برچسب جدید</span>
                            </div>
                        </div>
                    </b-action>
                </template>
            </b-table>
        </b-card>
        <b-card no-header class="overflow-visible" v-else>
            <div class="columns is-multiline is-flex">
                <div class="column is-12-mobile is-12-tablet is-10-widescreen is-10-desktop">
                    <div class="columns is-multiline is-flex">
                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                            <b-input
                                label='عنوان مخاطب:'
                                v-model="form.prefix"
                            >
                                <template #input>
                                    <b-select
                                        autoclose
                                        clearable
                                        label='عنوان مخاطب:'
                                        :options="prefixes"
                                        v-model="form.prefix"
                                    >
                                        <template #label="item">
                                            {{ item.title }}
                                        </template>
                                    </b-select>
                                </template>
                            </b-input>
                        </div>
                    </div>
                    <div class="columns is-multiline is-flex">
                            <div class="column is-12-mobile is-6-tablet is-4-widescreen is-4-desktop mb-0">
                                <b-input
                                    label='نام مخاطب:'
                                    v-model="form.fName"
                                    placeholder="نام مخاطب"
                                    :is-error="validator?.form?.fName?.$errors?.length"
                                    :error-message="validator?.form?.fName?.$errors[0]?.$message || ''"
                                ></b-input>
                            </div>
                            <div class="column is-12-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                <b-input
                                    label='نام خانوادگی مخاطب:'
                                    v-model="form.lName"
                                    placeholder="نام خانوادگی مخاطب"
                                    :is-error="validator?.form?.lName?.$errors?.length"
                                    :error-message="validator?.form?.lName?.$errors[0]?.$message || ''"
                                ></b-input>
                            </div>
                        </div>
                    <div class="columns is-multiline is-flex">
                            <div class="column is-12-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                <b-input
                                    label='نام مستعار:'
                                    v-model="form.nickname"
                                    placeholder="نام مستعار"
                                    :is-error="validator?.form?.nickname?.$errors?.length"
                                    :error-message="validator?.form?.nickname?.$errors[0]?.$message || ''"
                                ></b-input>
                            </div>
                            <div class="column is-12-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                <b-input
                                    label='عنوان:'
                                    v-model="form.title"
                                    placeholder="عنوان"
                                    :is-error="validator?.form?.title?.$errors?.length"
                                    :error-message="validator?.form?.title?.$errors[0]?.$message || ''"
                                ></b-input>
                            </div>
                            <div class="column is-12-mobile is-12-tablet is-4-widescreen is-4-desktop">
                                <b-input
                                    label='نام تجاری:'
                                    v-model="form.organization"
                                    placeholder="نام تجاری"
                                    :is-error="validator?.form?.organization?.$errors?.length"
                                    :error-message="validator?.form?.organization?.$errors[0]?.$message || ''"
                                ></b-input>
                            </div>
                        </div>
                </div>
                <div class="column is-12-mobile is-12-tablet is-2-widescreen is-2-desktop is-flex">
                    <div class="media-flex-center mx-auto">
                        <div class="h-avatar is-xl">
                            <input class="file-input" type="file" accept="image/jpeg, image/bmp" @change="changeAvatarFile" v-show="false" ref="avatarFile">
                            <img class="avatar" :src="form?.avatar" v-if="form?.avatar" :alt="form.fName || ''">
                            <span class="avatar is-fake has-text-grey-light is-weight-700" v-else>تصویر کاربر</span>
                            <button class="badge button is-rounded is-light border-1 d-block px-0" @click="$refs.avatarFile.click()">
                                <i class="fal fa-camera"></i>
                            </button>
                            <button class="badge button is-rounded is-warning border-1 d-block px-0" @click="form.avatar=null;" v-if="form.avatar">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="columns is-multiline is-flex is-vcentered" v-for="(info, key) in additional_infos" :key="'additionalInfo-'+index">
                <h3 class="column is-12 is-flex is-align-items-center">
                    <b-dropdown @change="chooseAdditional(key, $event)" key="key" :options="info.options || []" no-caret no-border>
                        <template #placeholder>
                            <button class="btn is-transparent px-2">
                                <i class="fas fa-plus has-text-info"></i>
                            </button>
                        </template>
                    </b-dropdown>
                    <span class="ml-2" v-text="info.title"/>
                </h3>
                <div class="column is-12 m-0 p-0 pl-6" v-for="(additional_info, subKey) in form.additional_infos">
                    <div class="columns is-multiline" v-if="additional_info.section == key">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop is-4-widescreen" v-if="additional_info.is_other">
                            <b-input
                                label='عنوان:'
                                v-model="form.additional_infos[subKey].title"
                                placeholder="عنوان"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-input
                                v-model="form.additional_infos[subKey].value"
                                :placeholder="form.additional_infos[subKey].title.length?(form.additional_infos[subKey].label?form.additional_infos[subKey].label:form.additional_infos[subKey].title):'مقدار'"
                            >
                                <template #label-text>
                                    <label :for="'v_'+additional_info.id">{{form.additional_infos[subKey].title.length?(form.additional_infos[subKey].label?form.additional_infos[subKey].label:form.additional_infos[subKey].title):'مقدار'}}:</label>
                                    <span class="ml-auto text-muted text-hover-danger cursor-pointer" @click="deleteAdditionalInfo(subKey)"><i class="fa fa-times text-muted text-hover-danger"></i> <span>حذف</span></span>
                                </template>
                                <template #input v-if="key==='date'">
                                    <date-picker
                                        :initial-value='Date(Date.now())'
                                        format='x'
                                        display-format='jYYYY/jMM/jDD'
                                        type="date"
                                        v-model="form.additional_infos[subKey].value"
                                        input-class="is-ltr has-text-left input"
                                        :key="additional_info.section+key"
                                    ></date-picker>
                                    {{ form.additional_infos[subKey].value }}
                                </template>
                            </b-input>
                        </div>
                    </div>
                </div>
            </div>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveContact()" :disabled="loading">
                    <i class="fal fa-save" v-if="!loading"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره مخاطب</span>
                </button>
                <button class="button" @click="cancelForm">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
    <b-modal
        ref="avatarCropper"
        :is-card="false"
    >
        <b-card no-header class="no-margin no-padding" body-class="no-padding no-margin">
            <div class="w-100" style="height: 300px; max-height: 300px; min-height: 300px;">
                <Cropper
                    :restrictions="pixelsRestriction"
                    :minHeight="300"
                    :minWidth="300"
                    classname="shadow"
                    :stencil-props="{
                aspectRatio: 1
            }"
                    :src="avatar"
                    ref="cropper"
                />
            </div>
            <template #footer-right>
                <span class="hint--light hint--rounded hint--top my-2 mx-5" data-hint="بزرگنمایی"><i @click="$refs.cropper.zoom(1.2)" class="fal fa-search-plus"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="کوچکنمایی"><i @click="$refs.cropper.zoom(0.8)" class="fal fa-search-minus"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="چرخش 90 درجه به چپ"><i @click="$refs.cropper.rotate(-90)" class="fal fa-undo-alt"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="چرخش 90 درجه به راست"><i @click="$refs.cropper.rotate(90)" class="fal fa-redo-alt"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="آیینه‌ای افقی"><i @click="$refs.cropper.flip(180,0)" class="fal fa-exchange"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="آیینه‌ای عمودی"><i @click="$refs.cropper.flip(0,180)" class="fal fa-exchange fa-rotate-90"></i></span>
            </template>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="اعمال تغییرات"><i @click="changeAvatar" class="fas fa-check has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="بستن پنجره"><i @click="$refs.avatarCropper.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
    <b-modal
        ref="tagModal"
        :is-card="false"
    >
        <b-card no-header class="no-margin no-padding" body-class="no-padding no-margin">
            <div class="w-100 p-5" style="">
                <div class="columns is-multiline is-flex">
                    <div class="column is-12-mobile is-12-tablet is-6-desktop is-6-widescreen">
                        <b-input
                            label='عنوان برچسب:'
                            v-model="contactTag.title"
                            placeholder="نام مخاطب"
                            :is-error="validator?.contactTag?.title?.$errors?.length"
                            :error-message="validator?.contactTag?.title?.$errors[0]?.$message || ''"
                        ></b-input>
                    </div>
                </div>
                <div class="columns is-multiline is-flex">
                    <div class="column is-4">
                        <b-input
                            label='رنگ پس زمینه:'
                            :is-error="validator?.contactTag?.title?.$errors?.length"
                            :error-message="validator?.contactTag?.title?.$errors[0]?.$message || ''"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="contactTag.bgColor" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                    <div class="column is-4">
                        <b-input
                            label='رنگ متن:'
                            :is-error="validator?.contactTag?.title?.$errors?.length"
                            :error-message="validator?.contactTag?.title?.$errors[0]?.$message || ''"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="contactTag.textColor" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                    <div class="column is-4">
                        <span class="tag" :style="{backgroundColor: contactTag.bgColor, color: contactTag.textColor}">متن نمونه برچسب</span>
                    </div>
                </div>
            </div>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" :data-hint="contactTag.id==null ? 'ذخیره برچسب' : 'ویرایش برچسب'"><i @click="saveContactTag" class="fas fa-check has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="انصراف"><i @click="$refs.tagModal.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
</template>

<script>
import { Cropper, CircleStencil } from 'vue-advanced-cropper'
import { useVuelidate } from '@vuelidate/core'
export default {
    name: "AdminContactsComponent",
    props: {},
    inject: ['base_url'],
    components: {
        Cropper,
        CircleStencil
    },
    setup () {
        return { validator: useVuelidate() }
    },
    data() {
        return {
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            usingTag: false,
            saving: false,
            newRec: false,
            editing: false,
            editingItem: {},
            contacts: [],
            form: {
                id: null,
                prefix: 0,
                avatar: null,
                fName: '',
                lName: '',
                nickname: '',
                title: '',
                organization: '',
                additional_infos: []
            },
            prefixes: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'name', title: 'نام مخاطب'},
                {id: 'nickname', title: 'نام مستعار'},
                {id: 'organization', title: 'نام تجاری'},
                {id: 'title', title: 'عنوان'},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 120px;', tdStyle: ''},
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
            additional_infos: {},
            currentAdditional: null,
            filters: {
                fName: null,
                lName: null,
                prefix: null,
                nickname: null,
                title: null,
                org: null,
                phone: null,
                email: null,
                address: null,
                other: null,
            },
            avatar: '',
            contactTag: {
                id: null,
                title: '',
                bgColor: 'rgba(0, 0, 0, 0)',
                textColor: 'rgba(200, 200, 200, 0.85)',
            }
        }
    },
    validations () {
        return {
            form: {
                prefix: 0,
                fName: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ورود نام الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'نام باید حداقل 3 کاراکتر باشد'
                    },
                },
                lName: {
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'نام خانوادگی باید حداقل 3 کاراکتر باشد'
                    },
                },
                nickname: {
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'نام مستعار باید حداقل 3 کاراکتر باشد'
                    },
                },
                organization: {
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'نام تجاری باید حداقل 3 کاراکتر باشد'
                    },
                },
                title: {
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'عنوان باید حداقل 3 کاراکتر باشد'
                    },
                },
            },
            contactTag: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ورود عنوان برچسب الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val==null || val=='' || val?.length >= 3)
                        },
                        $message: 'عنوان برچسب باید حداقل 3 کاراکتر باشد'
                    },
                },
                bgColor: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'رنگ پس زمینه برچسب الزامی است'
                    },
                },
                textColor: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'رنگ متن برچسب الزامی است'
                    },
                },
            }
        }
    },
    created() {
        this.refreshTable(false)
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {
        refreshTable(filters = null) {
            if (!this.loading) {
                let dataFilter = null
                let filtersCount = 0
                if (typeof filters == 'object' && filters != null) {
                    dataFilter = {}
                    for (const [key, value] of Object.entries(filters)) {
                        if (value != null && value != '') {
                            dataFilter[key] = value
                            filtersCount++
                        }
                    }
                    if (filtersCount === 0) {
                        this.$helpers.notify('خطای کاربر', 'وارد کردن حداقل یک گزینه برای جستجو الزامی است.', { type: 'error' })
                        return
                    }
                }
                this.loading = true
                Requests.getContactsList(filters).then(res => {
                    if (res?.status) {
                        this.contacts = res?.result ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست مشخصات مخاطبین', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست مشخصات مخاطبین', { type: 'error' })
                }).finally(() => {
                    if (filtersCount === 0) {
                        Requests.getContactsDetails().then(res => {
                            if (res?.status) {
                                this.additional_infos = res?.result?.additional_infos ?? [];
                                this.prefixes         = res?.result?.prefixes ?? [];
                                this.tags             = res?.result?.tags ?? [];
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست اطلاعات تکمیلی مخاطبین', { type: 'error' })
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست اطلاعات تکمیلی مخاطبین', { type: 'error' })
                        }).finally(() => {
                            this.loading = false
                        })
                    } else {
                        this.loading = false
                    }
                })
            }
        },
        cancelFilter() {
            if (!this.loading) {
                this.filters = {
                    fName: null,
                    lName: null,
                    prefix: null,
                    nickname: null,
                    title: null,
                    org: null,
                    phone: null,
                    email: null,
                    address: null,
                    other: null,
                }
                this.refreshTable(false)
                return
            }
            this.$helpers.notify('خطای کاربر', 'سیستم در حال اجرای درخواست دیگری است. لطفا چند لحظه دیگر مجدد تلاش کنید.', { type: 'error' })
        },
        newContact() {
            this.loading =  false
            this.saving =  false
            this.editing =  false
            this.newRec =  true
            this.errors = []
            this.avatar = ''
            this.form = {
                id: null,
                prefix: 0,
                avatar: null,
                fName: '',
                lName: '',
                nickname: '',
                title: '',
                organization: '',
                additional_infos: []
            }
            this.validator.$reset()
        },
        cancelForm() {
            this.newContact()
            this.editing = false
            this.newRec = false
        },
        setErrors(e) {
            this.errors = e
        },
        saveContact() {
            if (!this.saving && ! this.loading) {
                this.validator.form.$touch();
                if (!this.validator.form.$invalid) {
                    this.saving = true
                    this.setAdditionalValue();
                    Requests.saveContact(this.form).then(res => {
                        if (res?.status) {
                            if (this.editing) {
                                for (let i = 0; i < this.contacts.length; i++) {
                                    if (this.contacts[i].id == res.result.id) {
                                        this.contacts[i] = res.result
                                        break
                                    }
                                }
                                this.$helpers.notify(res?.message || 'مخاطب مورد نظر با موفقیت ذخیره شد.')
                            } else {
                                this.contacts.push(res.result);
                                this.$helpers.notify('مخاطب جدید با موفقیت ثبت شد.');
                            }
                            this.cancelForm()
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره مخاطب مورد نظر', { type: 'error' })
                        }
                    }).catch(err => {
                        console.log(err)
                        this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره مخاطب مورد نظر', { type: 'error' })
                    }).finally(res => {
                        this.saving = false
                        this.sortContacts()
                    })
                }
            }
        },
        editItem(item) {
            if (!this.loading || !this.saving) {
                this.loading = true
                Requests.getSingleContact(item.id).then(res => {
                    if (res && res.status) {
                        this.newContact()
                        this.newRec = false
                        this.editing = true
                        // this.additional_infos = res.additional_infos;
                        this.editingItem = item
                        this.form = {
                            id: res.result.id,
                            title: res.result.title,
                            fName: res.result.fName,
                            lName: res.result.lName,
                            organization: res.result.organization,
                            nickname: res.result.nickname,
                            prefix: res.result.prefix,
                            additional_infos:  res.result.details,
                            avatar:  res.result.avatar
                        }

                        this.editing = true
                    }
                }).catch(err => {
                    console.log(err)
                }).then(res => {
                    this.loading = false
                })
            }
        },
        deleteItem(id) {
            const perm = true
            if (perm) {
                this.$swal.fire({
                    title: "حذف مخاطب",
                    html: "در صورت حذف مخاطب تمامی اطلاعات مرتبط با آن غیرقابل دسترس خواهند بود.<span class='ml-2 has-text-danger has-text-weight-bold'>مخاطب مورد نظر حذف گردد؟</span>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "بلی",
                    cancelButtonText: "خیر",
                    showCloseButton: true,
                }).then(result => {
                    if (result.value) {
                        this.saving = true
                        Requests.deleteContact(id).then(res => {
                            if (res?.status) {
                                for (let i = 0; i < this.contacts.length; i++) {
                                    if (this.contacts[i].id == id) {
                                        this.contacts.splice(i, 1);
                                        this.$helpers.notify('مخاطب مورد نظر با موفقیت حذف گردید.')
                                        break
                                    }
                                }
                            } else {
                                this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف مخاطب مورد نظر با مشکل مواجه شد...', {
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
            }
        },
        changeAvatar() {
            const c = this.$refs.cropper.getResult().canvas
            this.form.avatar = c.toDataURL()
            this.$refs.avatarCropper.modalClose()
        },
        changeAvatarFile(e) {
            let reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = () => {
                this.avatar = reader.result
                this.$refs.avatarCropper.modalOpen()
            }
        },
        pixelsRestriction({minWidth, minHeight, maxWidth, maxHeight, imageWidth, imageHeight}) {
            return {
                minWidth: minWidth,
                minHeight: minHeight,
                maxWidth: maxWidth,
                maxHeight: maxHeight,
            }
        },
        prepareContactAvatar(image) {
            if (typeof image === 'object') {
                image = image[0]
            }
            if (image?.imageUrl) {
                return image?.imageUrl
            }
            return image?.file_name ? this.base_url + '/storage/uploads/images/' + image?.file_name : null;
        },
        chooseAdditional(key, e) {
            this.setAdditionalValue();
            this.form.additional_infos.push({
                section: key,
                title: this.additional_infos[key]?.options[e.type].title,
                label: (this.additional_infos[key]?.options[e.type]?.is_other) ? '' : this.additional_infos[key]?.options[e.type].label,
                value: '',
                is_other: this.additional_infos[key]?.options[e.type]?.is_other || false,
                id: key+e.key,
                validations : this.additional_infos[key]?.options[e.type].validations
            })
        },

        setAdditionalValue() {
            if (this.currentAdditional !== null && this.currentAdditional.title.trim().length > 2 && this.currentAdditional.value.trim().length > 2) {
                for (let i = 0; i < this.form.additional_infos.length; i++) {
                    if(this.form.additional_infos[i].section === this.currentAdditional.section && this.form.additional_infos[i].id === this.currentAdditional.id){
                        this.form.additional_infos[i] = {
                            section: this.currentAdditional.section,
                            title: this.currentAdditional.title,
                            label: this.currentAdditional.label,
                            value: this.currentAdditional.value,
                            id: this.currentAdditional.id,
                            is_other: this.currentAdditional.is_other
                        }
                        for (let j = 0; j < this.additional_infos[this.currentAdditional.section].options.length; j++) {
                            if(this.additional_infos[this.currentAdditional.section].options[j].id && this.additional_infos[this.currentAdditional.section].options[j].id === this.currentAdditional.id){
                                this.additional_infos[this.currentAdditional.section].options[j] = this.currentAdditional;
                            }
                        }
                        return;
                    }
                }
                if (this.currentAdditional.is_other) {
                    this.additional_infos[this.currentAdditional.section].options.push({
                        key: this.currentAdditional.title,
                        title: this.currentAdditional.title,
                        is_other: true,
                        id: this.currentAdditional.id
                    });
                }
                this.form.additional_infos.push(this.currentAdditional);
            }
        },
        deleteAdditionalInfo(index) {
            this.form.additional_infos.splice(index, 1)
        },
        sortContacts() {
            this.contacts = this.contacts.sort((a, b) => {
                return a.name.localeCompare(b.name)
            })
        },
        setContactTag(contact_id, tag_id) {
            if (!this.usingTag) {
                this.usingTag = true
                Requests.setContactTag(contact_id, tag_id).then(res => {
                    if (res?.status) {
                        for (let i = 0; i < this.contacts.length; i++) {
                            if (this.contacts[i]?.id === contact_id) {
                                this.contacts[i].tags = res?.result || []
                                break;
                            }
                        }
                        this.$helpers.notify(res.message);
                    } else {
                        this.$helpers.notify('خطا', res.message, {type: "error"})
                    }
                }).catch(err => {
                    console.log(err)
                }).finally(res => {
                    this.usingTag = false
                })
            } else {
                this.$helpers.notify('خطا', 'سیستم در حال اجرای درخواست یک برچسب دیگر است. چند لحظه دیگر تلاش کنید...', {
                    type: "error",
                })
            }
        },
        updateContactTag(tag = null) {
            this.contactTag = {
                id: null,
                title: '',
                bgColor: 'rgba(0, 0, 0, 0)',
                textColor: 'rgba(200, 200, 200, 0.85)',
            }
            if (tag != null) {
                this.contactTag = tag
            }
            this.$refs.tagModal.modalOpen()
        },
        saveContactTag() {
            if (!this.saving && ! this.loading) {
                this.validator.contactTag.$touch();
                if (!this.validator.contactTag.$invalid) {
                    this.saving = true
                    this.setAdditionalValue();
                    Requests.saveContactTags(this.contactTag).then(res => {
                        if (res?.status) {
                            this.$refs.tagModal.modalClose()
                            this.saving = false
                            this.$helpers.notify(res?.message || 'برچسب مورد نظر با موفقیت ذخیره شد.')
                            this.refreshTable(false)
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره برچسب مورد نظر', { type: 'error' })
                        }
                    }).catch(err => {
                        console.log(err)
                        this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره برچسب مورد نظر', { type: 'error' })
                    }).finally(res => {
                        this.saving = false
                    })
                }
            }
        },
        deleteContactTag(tag_id) {
            this.$swal.fire({
                title: "حذف برچسب",
                html: "حذف برچسب یک عملیات غیرقابل بازگشت بوده و علاوه بر حذف آن، از تمامی مخاطبین نیز برداشته خواهد شد.<span class='ml-2 has-text-danger has-text-weight-bold'>برچسب مورد نظر حذف گردد؟</span>",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "بلی",
                cancelButtonText: "خیر",
                showCloseButton: true,
            }).then(result => {
                if (result.value) {
                    this.saving = true
                    console.log(tag_id);
                    Requests.deleteContactTags(tag_id).then(res => {
                        if (res?.status) {
                            this.$helpers.notify('برچسب مورد نظر با موفقیت حذف گردید.')
                            this.refreshTable(false)
                        } else {
                            this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف برچسب مورد نظر با مشکل مواجه شد...', {
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
}
.bg-transparent {
    background-color: transparent !important;
}
.table-container {
    overflow: visible;
}
</style>
