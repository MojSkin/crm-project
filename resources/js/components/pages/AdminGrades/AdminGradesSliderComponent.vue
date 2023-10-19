<template>
    <AdminLayoutComponent>
        <template #title><span class="sahel">اسلایدر</span><span class="ml-2 sahel has-text-info">{{ grade_record?.title }}</span></template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <RouterLink :to="{ name: 'admin.grades' }" class="button border-0 is-outlined text-accent hint--light hint--rounded hint--top" data-hint="بازگشت به فهرست مقاطع تحصیلی" v-if="!newRec && !editing">
                        <i class="far fa-chevron-right mt-1"></i>
                    </RouterLink>
                    <button @click="newSlide" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>اسلاید جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست مقاطع تحصیلی">
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
                :data="slides"
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
                <template #description="data">
                    <span class="show-more" v-html="data.value"></span>
                </template>
                <template #image="data">
                    <div class="is-flex is-align-items-center">
                        <button class="button is-link is-light bg-transparent has-text-grey px-0 hint--light hint--rounded hint--top" data-hint="اولویت بالاتر" @click="reorderSlide(data.item, -1)" :disabled="data.item.tab_index <= 1">
                            <i class="fa fa-arrow-alt-up"></i>
                        </button>
                        <button class="button is-link is-light bg-transparent has-text-grey px-1 mr-2 hint--light hint--rounded hint--top" data-hint="اولویت پایین‌تر" @click="reorderSlide(data.item, 1)" :disabled="data.item.tab_index >= slides.length">
                            <i class="fa fa-arrow-alt-down"></i>
                        </button>
                        <div class="h-avatar is-medium">
                            <img class="avatar" :src="slider_address+data.item?.image?.file_name" v-if="data.item?.image" alt="">
                            <img class="avatar" src="\resources\assets\images\png\no-image.png" v-else>
                        </div>
                    </div>
                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary px-2" @click="editSlide(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger px-2" @click="deleteSlide(data.item.id)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
        </b-card>
        <b-card no-header class="overflow-visible" v-else>
            <div class="columns">
                <div class="column is-12">
                    <div class="slidePreview">
                        <img :src="imageUrl" alt="" v-if="imageUrl">
                        <div class="slider-content">
                            <div class="columns is-flex" :class="{ 'is-justify-content-start': form.title_position.value === 1, 'is-justify-content-end': form.title_position.value === 2, 'is-justify-content-center': form.title_position.value === 3 }" v-if="form?.title?.value">
                                <div class="column" :class="'is-'+form.title_size.value">
                                    <h3 class="mb-4" :class="{ 'has-text-left': form.title_align.value === 1, 'has-text-right': form.title_align.value === 2, 'has-text-centered': form.title_align.value === 3, 'has-text-justified': form.title_align.value === 4 }" :style="{ color: form.title_color.value }">{{ form.title.value }}</h3>
                                </div>
                            </div>
                            <div class="columns is-flex is-flex-direction-column" :class="{ 'is-justify-content-start': form.description_position.value === 1, 'is-justify-content-end': form.description_position.value === 2, 'is-justify-content-center': form.description_position.value === 3 }" v-if="form?.description?.value || form.cta_text?.value">
                                <div class="column" :class="'is-'+form.description_size.value">
                                    <h4 class="mb-4" v-html="form.description.value" :class="{ 'has-text-left': form.description_align.value === 1, 'has-text-right': form.description_align.value === 2, 'has-text-centered': form.description_align.value === 3, 'has-text-justified': form.description_align.value === 4 }" :style="{ color: form.description_color.value }" v-if="form?.description?.value"></h4>
                                    <div class="is-flex" :class="['is-'+form.cta_size.value, { 'is-justify-content-start': form.cta_position.value === 1, 'is-justify-content-end': form.cta_position.value === 2, 'is-justify-content-center': form.cta_position.value === 3 }]" v-if="form.cta_text?.value">
                                        <RouterLink :to="form.cta_link.value || '#'" class="button h-button is-elevated" :class="[form.cta_color.value, {
                                        'is-light': form.cta_light.value==='بلی',
                                        'is-bordered': form.cta_bordered.value==='بلی',
                                        'is-rounded': form.cta_rounded.value==='بلی',
                                        'w-100': form.cta_block.value==='بلی',
                                    }]" v-if="form.cta_link.value && form.cta_link.value.includes(hostName)">
                                            {{ form.cta_text.value }}
                                        </RouterLink>
                                        <a :href="form.cta_link.value" :target="form.cta_target.value==='تب جدید' ? '_blank' : '_self'" class="button h-button is-elevated" :class="[form.cta_color.value, {
                                        'is-light': form.cta_light.value==='بلی',
                                        'is-outlined': form.cta_bordered.value==='بلی',
                                        'is-rounded': form.cta_rounded.value==='بلی',
                                        'w-100': form.cta_block.value==='بلی',
                                    }]" v-else>
                                            {{ form.cta_text.value }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <FormContentComponent
                v-model="form"
                :errors="errors"
                ref="form"
                @error="setErrors"
                class="is-flex"
            >
                <template #title_color>
                    <div class="column" :class="'is-'+form.title_color.col_mobile+'-mobile is-'+form.title_color.col_tablet+'-tablet is-'+form.title_color.col_desktop+'-desktop is-'+form.title_color.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.title_color.title+(form.title_color.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="form.title_color.value" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #description_color>
                    <div class="column" :class="'is-'+form.description_color.col_mobile+'-mobile is-'+form.description_color.col_tablet+'-tablet is-'+form.description_color.col_desktop+'-desktop is-'+form.description_color.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.description_color.title+(form.description_color.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <color-picker class="mt-3" v-model:pure-color="form.description_color.value" shape="circle" use-type="pure" lang="Fa" />
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #title_align>
                    <div class="column" :class="'is-'+form.title_align.col_mobile+'-mobile is-'+form.title_align.col_tablet+'-tablet is-'+form.title_align.col_desktop+'-desktop is-'+form.title_align.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.title_align.title+(form.title_align.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="aligns"
                                    v-model="form.title_align.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #title_position>
                    <div class="column" :class="'is-'+form.title_position.col_mobile+'-mobile is-'+form.title_position.col_tablet+'-tablet is-'+form.title_position.col_desktop+'-desktop is-'+form.title_position.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.title_position.title+(form.title_position.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="positions"
                                    v-model="form.title_position.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #title_size>
                    <div class="column" :class="'is-'+form.title_size.col_mobile+'-mobile is-'+form.title_size.col_tablet+'-tablet is-'+form.title_size.col_desktop+'-desktop is-'+form.title_size.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.title_size.title+(form.title_size.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="sizes"
                                    v-model="form.title_size.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #description_align>
                    <div class="column" :class="'is-'+form.description_align.col_mobile+'-mobile is-'+form.description_align.col_tablet+'-tablet is-'+form.description_align.col_desktop+'-desktop is-'+form.description_align.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.description_align.title+(form.description_align.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="aligns"
                                    v-model="form.description_align.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #description_position>
                    <div class="column" :class="'is-'+form.description_position.col_mobile+'-mobile is-'+form.description_position.col_tablet+'-tablet is-'+form.description_position.col_desktop+'-desktop is-'+form.description_position.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.description_position.title+(form.description_position.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="positions"
                                    v-model="form.description_position.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #description_size>
                    <div class="column" :class="'is-'+form.description_size.col_mobile+'-mobile is-'+form.description_size.col_tablet+'-tablet is-'+form.description_size.col_desktop+'-desktop is-'+form.description_size.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.description_size.title+(form.description_size.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="sizes"
                                    v-model="form.description_size.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #cta_color>
                    <div class="column" :class="'is-'+form.cta_color.col_mobile+'-mobile is-'+form.cta_color.col_tablet+'-tablet is-'+form.cta_color.col_desktop+'-desktop is-'+form.cta_color.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.cta_color.title+(form.cta_color.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="colors"
                                    v-model="form.cta_color.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #cta_position>
                    <div class="column" :class="'is-'+form.cta_position.col_mobile+'-mobile is-'+form.cta_position.col_tablet+'-tablet is-'+form.cta_position.col_desktop+'-desktop is-'+form.cta_position.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.cta_position.title+(form.cta_position.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="positions"
                                    v-model="form.cta_position.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
                <template #cta_size>
                    <div class="column" :class="'is-'+form.cta_size.col_mobile+'-mobile is-'+form.cta_size.col_tablet+'-tablet is-'+form.cta_size.col_desktop+'-desktop is-'+form.cta_size.col_widescreen+'-widescreen'">
                        <b-input
                            :label="form.cta_size.title+(form.cta_size.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                        >
                            <template #input>
                                <b-select
                                    :options="sizes"
                                    v-model="form.cta_size.value"
                                    searchable
                                    autoclose
                                ></b-select>
                            </template>
                        </b-input>
                    </div>
                </template>
            </FormContentComponent>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveSlide()" :disabled="loading || saving">
                    <i class="fal fa-save" v-if="!loading && !saving"></i>
                    <i class="fal fa-spinner-third fa-spin" v-else></i>
                    <span class="ml-2">ذخیره اسلاید</span>
                </button>
                <button class="button" @click="cancelForm">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
</template>

<script>
import Form from '/resources/js/form'
export default {
    name: "AdminGradesSliderComponent",
    inject: ['base_url'],
    props: {
        grade: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            hostName: window.location.hostname,
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            saving: false,
            newRec: false,
            editing: false,
            grade_record: {},
            editingItem: {},
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'image', title: 'تصویر'},
                {id: 'title', title: 'عنوان اسلاید', sortable: true},
                {id: 'description', title: 'توضیحات', tdClass:'show-more', },
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 80px;', tdStyle: ''},
            ],
            slides: [],
            errors: [],
            index: null,
            items: [],
            form: {},
            form_template: {
                'image'                 : { title: 'تصویر پس‌زمینه', type: 15, is_required: false, options: 'image/jpeg, image/png', col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12 },
                'title'                 : { title: 'عنوان', type: 1, is_required: false, col_mobile: 12, col_tablet: 6, col_desktop: 4, col_widescreen: 4, validator: ['min:2', 'max:250'] },
                'title_align'           : { title: 'چیدمان متن عنوان', type: 9, is_required: false, default: 1, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'title_position'        : { title: 'قرارگیری عنوان', type: 9, is_required: false, default: 1, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'title_size'            : { title: 'فضای مورد نیاز عنوان', type: 9, is_required: false, default: 12, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'title_color'           : { title: 'رنگ متن عنوان', type: 1, is_required: false, default: 'rgba(15, 15, 15, 1)', col_mobile: 6, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'description'           : { title: 'توضیحات', type: 2, description: 'کدهای HTML قابل قبول است', is_required: false, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['min:2', 'max:2500'] },
                'description_align'     : { title: 'چیدمان متن توضیحات', type: 9, is_required: false, default: 1, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'description_position'  : { title: 'قرارگیری توضیحات', type: 9, is_required: false, default: 1, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'description_size'      : { title: 'فضای مورد نیاز عنوان', type: 9, is_required: false, default: 8, col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'description_color'     : { title: 'رنگ متن توضیحات', type: 1, is_required: false, default: 'rgba(75, 75, 75, 0.8)', col_mobile: 12, col_tablet: 6, col_desktop: 2, col_widescreen: 2 },
                'cta_text'              : { title: 'متن پیوند', type: 1, is_required: false, col_mobile: 12, col_tablet: 6, col_desktop: 6, col_widescreen: 6, validator: ['min:2', 'max:100'] },
                'cta_link'              : { title: 'مقصد پیوند', type: 1, is_required: false, col_mobile: 12, col_tablet: 6, col_desktop: 6, col_widescreen: 6, validator: ['min:4', 'max:2500'] },
                'cta_target'            : { title: 'نحوه پیمایش', type: 9, is_required: false, options: 'همین صفحه|تب جدید', default: 'همین صفحه', col_mobile: 12, col_tablet: 12, col_desktop: 3, col_widescreen: 3 },
                'cta_position'          : { title: 'قرارگیری دکمه', type: 9, is_required: false, default: 1, col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_size'              : { title: 'فضای مورد نیاز دکمه', type: 9, is_required: false, default: 8, col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_color'             : { title: 'رنگ دکمه', type: 1, is_required: false, default: 'is-info', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_light'             : { title: 'دکمه رنگ روشن', type: 9, is_required: false, options: 'بلی|خیر', default: 'خیر', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_bordered'          : { title: 'دکمه دور خط دار', type: 9, is_required: false, options: 'بلی|خیر', default: 'خیر', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_rounded'           : { title: 'دکمه با گوشه‌های گرد', type: 9, is_required: false, options: 'بلی|خیر', default: 'خیر', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
                'cta_block'             : { title: 'پوشش تمام فضا توسط دکمه', type: 9, is_required: false, options: 'بلی|خیر', default: 'خیر', col_mobile: 6, col_tablet: 6, col_desktop: 3, col_widescreen: 3 },
            },
            imageUrl: null,
            colors: [
                { value: 'is-white', text: 'سفید',               icon: 'mr-3 h-button button is-white' },
                { value: 'is-dark', text: 'سیاه',               icon: 'mr-3 h-button button is-dark' },
                { value: 'is-light', text: 'روشن',               icon: 'mr-3 h-button button is-light' },
                { value: 'is-primary', text: 'رنگ اصلی (بنفش)',    icon: 'mr-3 h-button button is-primary' },
                { value: 'is-info', text: 'توضیحات (آبی)',      icon: 'mr-3 h-button button is-info' },
                { value: 'is-success', text: 'تایید (سبز)',        icon: 'mr-3 h-button button is-success' },
                { value: 'is-warning', text: 'هشدار (نارنجی)',     icon: 'mr-3 h-button button is-warning' },
                { value: 'is-danger', text: 'اخطار (قرمز)',       icon: 'mr-3 h-button button is-danger' },
            ],
            aligns: [
                { value: 1, text: 'راست چین',       icon: 'fal fa-align-right' },
                { value: 2, text: 'چپ چین',         icon: 'fal fa-align-left' },
                { value: 3, text: 'وسط چین',        icon: 'fal fa-align-center' },
                { value: 4, text: 'تراز از طرفین',  icon: 'fal fa-align-justify' },
            ],
            positions: [
                { value: 1, text: 'راست',       icon: 'fal fa-align-right' },
                { value: 2, text: 'چپ',         icon: 'fal fa-align-left' },
                { value: 3, text: 'وسط',        icon: 'fal fa-align-center' },
            ],
            sizes: [
                { value: 12, text: 'تمام صفحه', },
                { value: 9, text: 'سه / چهارم', },
                { value: 8, text: 'دو / سوم', },
                { value: 6, text: 'یک / دوم', },
                { value: 4, text: 'یک / سوم', },
                { value: 3, text: 'یک / چهارم', },
            ],
            slider_address: null,
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
    watch: {
        'form.image': {
            handler() {
                this.imageUrl = null
                if (this.form?.image?.value != null && typeof this.form?.image?.value === 'object' && this.form?.image?.value?.length) {
                    this.imageUrl = this.form?.image?.value[0].imageUrl
                }
            },
            deep: true
        }
    },
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getGradeSlidersList(this.grade).then(res => {
                    if (res?.status) {
                        this.slides = res?.result?.slides ?? [];
                        this.grade_record = res?.result?.grade ?? {};
                        this.slider_address = this.base_url+'/storage/uploads/sliders/'+this?.grade_record?.id+'/'
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت اطلاعات اسلایدر', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت اطلاعات اسلایدر', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        newSlide() {
            this.loading = false
            this.saving = false
            this.newRec = true
            this.editing = false
            this.errors = []
            this.form = new Form(this.form_template)
            console.log(this.form)
        },
        cancelForm() {
            this.newSlide()
            this.editing = false
            this.newRec = false
        },
        saveSlide() {
            if (!this.saving && !this.loading) {
                this.$refs.form.validateAll().then(res => {
                    if (res) {
                        this.saving = true
                        const formData = {
                            grade: this.grade,
                        }
                        for (const key in this.form) {
                            formData[key] = this.form[key].value
                        }
                        for (const key in {'cta_block': true, 'cta_bordered': true, 'cta_light': true, 'cta_rounded': true}) {
                            formData[key] = formData[key] === 'بلی'
                        }
                        formData['slide'] = this.editing ? this.editingItem.id : null
                        formData['cta_target'] = formData['cta_target'] === 'همین صفحه' ? '_self' : '_blank'
                        formData['image'] = this.imageUrl
                        Requests.saveSlide(formData).then(res => {
                            if (res?.status) {
                                if (this.editing) {
                                    this.slides[this.editingItem.index] = res.result
                                } else {
                                    this.slides.push(res.result)
                                }

                                this.cancelForm()

                                this.$helpers.notify(res?.message || 'اسلاید مورد نظر با موفقیت ذخیره شد.')
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره اسلاید مورد نظر', { type: 'error' })
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره اسلاید مورد نظر', { type: 'error' })
                        }).finally(res => {
                            this.saving = false
                        })
                    } else {
                        this.$helpers.notify('خطای ورود اطلاعات', 'قبل از ذخیره کردن فرم، باید خطاهای فرم را برطرف کنید.', {type: 'warning'})
                    }
                })
            }
        },
        editSlide(slide) {
            this.newSlide()
            this.newRec = false
            this.editingItem = slide
            for (let i = 0; i < this.slides.length; i++) {
                if (this.slides === slide.id) {
                    this.editingItem.index = i
                    break
                }
            }
            for (const key in this.form) {
                this.form[key].value = this.editingItem[key]
            }
            this.form.image.value = null
            if (slide?.image?.file_name && slide?.image?.file_name?.length) {
                this.$helpers.toDataURL(this.slider_address+slide?.image?.file_name, res => {
                    this.imageUrl = res
                })
            }
            this.editing = true
        },
        deleteSlide(slide_id) {
            if (!this.saving && !this.loading) {
                this.$swal.fire({
                    title: "حذف اسلاید",
                    text: "اسلاید مورد نظر حذف گردد؟",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "بلی",
                    cancelButtonText: "خیر",
                    showCloseButton: true,
                }).then(result => {
                    if (result.value) {
                        this.loading = true
                        Requests.deleteSlide(slide_id).then(res => {
                            if (res?.status) {
                                for (let i = 0; i < this.slides.length; i++) {
                                    if (this.slides[i].id == slide_id) {
                                        this.slides.splice(i, 1);
                                        this.$helpers.notify('اسلاید مورد نظر با موفقیت حذف گردید.')
                                        break
                                    }
                                }
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام حذف اسلاید مورد نظر', {type: "error"})
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام حذف اسلاید مورد نظر', { type: 'error' })
                            console.log(err);
                        }).finally(res => {
                            this.loading = false
                        })
                    }
                });
            }
        },
        reorderSlide(item, order = 1) {
            if (!this.saving && !this.loading) {
                this.saving = true
                Requests.reorderSlide(this.grade_record.slug, item.id, order).then(res => {
                    console.log(res)
                    if (res?.status) {
                        this.slides = res?.result?.slides ?? [];
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام تغییر چیدمان اسلایدر', { type: 'error' })
                }).finally(() => {
                    this.saving = false
                })
            }
        },
        setErrors(e) {
            this.errors = e
        }
    }
}
</script>

<style scoped>
.slidePreview {
    height: 360px;
    border: 1px solid #b5b5b5;
    border-radius: 3px;
    background-image: url(/resources/assets/images/png/chess-board.png);
    background-size: auto;
    background-repeat: repeat;
    position: relative;
    overflow: hidden;
}
.slidePreview img:first-child {
    position: absolute;
    inset: 0;
    object-position: center center;
    object-fit: cover;
    width: 100%;
    height: 360px;
}
.slider-content {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 360px;
    padding: 30px;
}
.slider-content h3 {
    text-shadow: 2px 2px 1px rgba(35, 35, 35, .65), 0 0 2px rgba(35, 35, 35, .90);
    font-family: Sahel;
    font-size: 3rem;
}
.slider-content h4 {
    text-shadow: 1px 1px 2px rgba(35, 35, 35, .85);
    font-family: Shabnam;
    font-size: 1.2rem;
    min-height: 110px;
    max-height: 190px;
    padding-left: 20px;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>
<style>
input#cta_link {
    direction: ltr;
    text-align: left;
}
.show-more {
    max-width: 200px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis ellipsis;
}
</style>
