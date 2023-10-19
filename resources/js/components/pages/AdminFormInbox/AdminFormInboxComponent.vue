<template>
    <AdminLayoutComponent>
        <template #title>صندوق ورودی فرم‌ها</template>
        <b-card v-if="action==='select-form'" no-header no-footer class="overflow-visible">
            <div class="columns is-multiline is-flex is-align-items-end">
                <div class="column is-9">
                    <b-select
                        :options="forms"
                        v-model="form_id"
                    >
                        <template #label>
                            <span>انتخاب فرم</span>
                            <button @click="getForms()" class="btn btn-outline text-accent hint--light hint--rounded hint--top" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست فرم‌ها">
                                <i class="fal fa-undo"></i>
                            </button>
                            <button class="btn btn-outline text-accent" v-if="loading">
                                <i class="fas fa-spinner-third fa-spin"></i>
                            </button>
                        </template>
                    </b-select>
                </div>
                <div class="column is-3 is-flex">
                    <button class="button is-primary ml-auto hint--light hint--rounded hint--top" data-hint="دریافت اطلاعات صندوق ورودی" @click="getFormInbox" :disabled="form_id <= 0 || getInbox">
                        <i class="fas fa-spinner-third fa-spin" v-if="getInbox"></i>
                        <i class="fal fa-inbox-in" v-else></i>
                        <span class="ml-2 is-hidden-mobile">دریافت اطلاعات</span>
                    </button>
                </div>
            </div>
        </b-card>
        <b-card v-if="action==='show-inbox'" body-class="p-0" no-footer>
            <template #header-right>
                <h3 class="mb-0">
                    <button @click="action='select-form'" class="btn btn-outline text-accent hint--light hint--rounded hint--top" data-hint="بازگشت به انتخاب فرم">
                        <i class="far fa-chevron-right"></i>
                    </button>
                    <span class="sahel is-weight-700">{{ form.text }}</span>
                    <button @click="getFormInbox" class="btn btn-outline text-accent hint--light hint--rounded hint--top" v-if="!getInbox" data-hint="دریافت مجدد صندوق ورودی">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="getInbox">
                        <i class="fas fa-spinner-third fa-spin"></i>
                    </span>
                    <span class="btn-group btn-group-sm font-size-10">
                        <b-action direction="left">
                            <template #icon>
                                <i class="fal fa-download"></i>
                            </template>
                            <button @click="exportTable('xls')" class="dropdown-item font-size-base">
                                <i class="fal fa-file-excel mr-3"></i>
                                <span>خروجی اکسل XLS</span>
                            </button>
                            <button @click="exportTable('csv')" class="dropdown-item font-size-base">
                                <i class="fal fa-file-csv mr-3"></i>
                                <span>خروجی CSV</span>
                            </button>
                        </b-action>
                    </span>
                </h3>
            </template>
            <template #header-left>
                <input class="input" v-model="filters.text" type="search" placeholder="جستجو کنید...">
            </template>
            <b-table
                :headers="inboxHeaders"
                :data="computedData"
                :is-busy="getInbox"
                table-header-class="bg-light"
                :per-page="perPage"
                overflow
                striped
                hoverable
                class="pb-6 mb-0"
            >
                <template #status="data">
                    <span class='is-clickable hint--light hint--rounded hint--top' :class="data.value.class" @click="showRejectsModal(data.item)" data-hint="برای مشاهده فهرست تغییرات کلیک کنید">
                        <span>{{ data.value.text }}</span>
                        <span v-if="data.value.status != 0 || data.item?.rejects?.length"></span>
                    </span>
                </template>
                <template #action="data">
                    <div class="btn-group btn-group-sm font-size-10">
                        <b-action direction="up">
                            <button @click="downloadForm(data.item)" class="dropdown-item font-size-base">
                                <i class="fal fa-download mr-3"></i>
                                <span>دریافت فرم</span>
                            </button>
                            <button @click="downloadForm(data.item, false)" class="dropdown-item font-size-base">
                                <i class="fal fa-eye mr-3"></i>
                                <span>مشاهده فرم</span>
                            </button>
                            <hr class="dropdown-divider">
                            <button @click="editForm(data.item)" class="dropdown-item font-size-base">
                                <i class="fal fa-pencil mr-3"></i>
                                <span>ویرایش وضعیت</span>
                            </button>
                        </b-action>
                    </div>
                </template>
                <template #invoice="data">
                    <span v-if="data?.value?.id" class="is-flex">
                        <span class="tag is-success is-rounded hint--light hint--rounded hint--top" :data-hint="'شماره پیگیری: '+data?.value?.callbackId" v-if="data?.value?.status===1">موفقیت‌آمیز</span>
                        <span class="tag is-info is-outlined is-rounded" v-else-if="data?.value?.status===0">منتظر پرداخت</span>
                        <span class="tag is-danger is-rounded" v-else>خطای پرداخت</span>
                    </span>
                    <span v-else>
                        <span class="tag is-danger is-outlined is-rounded">فاقد اطلاعات پرداخت</span>
                    </span>
                </template>
                <template v-for="file in file_ids" v-slot:[file]="data">
                    <div class="avatar-stack" v-if="data?.value?.length || false">
                        <div class="h-avatar is-large" v-for="f in data.value">
                            <a class="avatar hint--light hint--rounded hint--top" :class="{ 'is-fake': !f[2].includes('image'), 'is-success': !f[2].includes('image') }" :href="base_url+'/storage/uploads/form_attachments/'+data.item.inbox_id+'/'+f[5]" :data-hint="f[4]" :download="f[4]">
                                <img class="avatar" :src="base_url+'/storage/uploads/form_attachments/'+data.item.inbox_id+'/'+f[5]" :alt="f[4]" v-if="f[2].includes('image')">
                                <span style="word-wrap: anywhere; font-size: 10px; text-align: center;" v-else>{{ f[4] }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="h-avatar is-large" v-else>
                        <span class="avatar is-fake">
                            <span>-</span>
                        </span>
                    </div>
                </template>
            </b-table>
            <div class="column is-12 has-text-centered" v-if="computedData.length">
                <button class="button px-6 is-light py-5" :disabled="getInbox || (formInbox.length >= totalRecords)" @click="loadMoreData()">
                    <span class="px-6 mx-6">
                        <i class="fas fa-spinner-third fa-spin" v-if="getInbox"></i>
                        <i class="fal fa-cloud-download-alt" v-else></i>
                        <span class="ml-4 is-weight-700" v-if="formInbox.length < totalRecords">دریافت بیشتر</span>
                        <span class="ml-4" v-else>پایان صندوق ورودی</span>
                    </span>
                </button>
            </div>
        </b-card>
    </AdminLayoutComponent>
    <vue3-html2pdf v-if="this.original_form"
                   :show-layout="false"
                   :float-layout="true"
                   :enable-download="false"
                   :preview-modal="true"
                   :paginate-elements-by-height="1600"
                   filename="document"
                   :pdf-quality="2"
                   :manual-pagination="false"
                   pdf-format="a4"
                   pdf-orientation="portrait"
                   pdf-content-width="100%"
                   :html-to-pdf-options="{margin: 3.5, image: {type: 'png', quality: 1}, html2canvas: { logging: false, scale: 3.75}, jsPDF: {precision: 20, logging: false}}"
                   ref="html2Pdf"
                   @hasDownloaded="hasGenerated()"
    >
        <template v-slot:pdf-content>
            <b-card no-footer no-header class="position-relative mb-0" body-class="pt-0 mb-0">
                <div style="position: absolute; inset: 0; z-index: 10; display: flex; align-items: center; filter: grayscale(0.8)">
                    <img :src="site_logo" style="opacity: 0.08">
                </div>
                <div class="row mb-0 pb-0">
                    <div class="col-3 text-center" style="font-size: 10px; padding-top: 90px;">
                        <span class="mr-2" v-html="'تاریخ ثبت فرم:'" />
                        <span v-html="currentRow.created_at" />
                    </div>
                    <div class="col-6 text-center" style="padding-top: 40px;">
                        <span style="font-size: 18px">{{ site_name }}</span>
                        <br>
                        <span style="font-size: 14px">{{ form.text }}</span>
                    </div>
                    <div class="col-3 position-relative" style="height: 120px">
                        <div style="position: absolute; inset:0; text-align: left">
                            <img :src="site_logo" style="height: 120px">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div v-for='field in this.original_form' class="mb-2 py-0" :class="[field.custom_class, 'col-'+field.col_desktop]">
                        <div v-if="field.type === 15">
                            <span class="mr-2" style="font-size: 10px">{{ field.title }}: </span>
                            <div class="w-100 is-flex justify-content-center">
                                <div class="avatar-stack" v-if="field?.value?.length || false">
                                    <div class="h-avatar is-large" v-for="f in field.value">
                                        <img class="avatar" :src="base_url+'/storage/uploads/form_attachments/'+currentRow.inbox_id+'/'+f[5]" :alt="f[4]" v-if="f[2].includes('image')">
                                        <span style="word-wrap: anywhere; font-size: 10px; text-align: center;" v-else>{{ f[4] }}</span>
                                    </div>
                                </div>
                                <div class="h-avatar is-large" v-else>
                            <span class="avatar is-fake">
                                <span>-</span>
                            </span>
                                </div>
                            </div>
                        </div>
                        <div v-if="field.type === 10 && field.is_printable" v-html="field.options"></div>
                        <div v-if="field.type !== 10 && field.type !== 15" class="input" style="font-size: 10px !important; height: auto;">
                            <span class="mr-2">{{ field.title }}: </span><span v-html="field.value"/>
                        </div>
                    </div>
                    <div class="mb-2 py-0 col-12">
                        <div class="input has-background-light" style="font-size: 10px !important; height: auto;">
                            <span class="mr-2">وضعیت فرم:</span>
                            <span :class="currentRow?.status?.class">{{ currentRow?.status?.text }}</span>
                            <span class="ml-2" v-if="currentRow?.rejects?.length">
                            <span class="is-weight-700">{{ currentRow?.rejects[currentRow?.rejects?.length-1]?.comment }}</span>
                            <span class="mx-2">- در تاریخ</span>
                            <bdi class="is-weight-700">{{ $helpers.jDate(currentRow?.rejects[currentRow?.rejects?.length-1]?.created_at, 'jYYYY/jMM/jDD HH:MM:ss') }}</bdi>
                        </span>
                        </div>
                    </div>
                    <div class="mb-2 py-0 col-12" v-if="formInvoice?.id">
                        <div class="input" style="font-size: 10px !important; height: auto;">
                            <span class="mr-2">وضعیت پرداخت:</span>
                            <span class="tag is-success is-rounded" v-if="formInvoice?.status===1">موفقیت‌آمیز</span>
                            <span class="tag is-info is-outlined is-rounded" v-else-if="formInvoice?.status===0">منتظر پرداخت</span>
                            <span class="tag is-danger is-rounded" v-else>خطای پرداخت</span>
                            <span class="ml-2">
                                <span>شماره تراکنش:</span>
                                <span>{{ formInvoice?.callbackId }}</span>
                                <span class="mx-2">- تاریخ پرداخت</span>
                                <bdi>{{ $helpers.jDate(formInvoice?.created_at, 'jYYYY/jMM/jDD HH:MM') }}</bdi>
                            </span>
                        </div>
                    </div>
                </div>
            </b-card>
        </template>
    </vue3-html2pdf>

    <b-modal
        :is-card='false'
        ref="viewForm"
        >
        <b-card no-footer class="overflow-scroll no-margin">
            <template #header-right>
                <div class="row mb-0 pb-0">
                    <div class="col-12 text-center my-2 pb-0 is-flex pl-5">
                        <span class="sahel is-weight-700">{{ form.text }}</span>
                    </div>
                </div>
            </template>
            <template #header-left>
                <div class="row mb-0 pb-0">
                    <div class="col-12 text-center my-2 pb-0 is-flex pl-5">
                        <button class="delete ml-auto" @click="$refs.viewForm.modalClose()"></button>
                    </div>
                </div>
            </template>
            <div class="row">
                <div v-for='field in original_form' class="mb-2 py-0" :class="[field.custom_class, 'col-'+field.col_desktop]">
                    <div v-if="field.type === 10 && field.is_required" v-html="field.options"></div>
                    <div v-if="field.type === 15">
                        <span class="mr-2" style="font-size: 10px">{{ field.title }}: </span>
                        <div class="w-100 is-flex justify-content-center">
                            <div class="avatar-stack" v-if="field?.value?.length || false">
                                <div class="h-avatar is-large" v-for="f in field.value">
                                    <img class="avatar" :src="base_url+'/storage/uploads/form_attachments/'+currentRow.inbox_id+'/'+f[5]" :alt="f[4]" v-if="f[2].includes('image')">
                                    <span style="word-wrap: anywhere; font-size: 10px; text-align: center;" v-else>{{ f[4] }}</span>
                                </div>
                            </div>
                            <div class="h-avatar is-large" v-else>
                            <span class="avatar is-fake">
                                <span>-</span>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div v-if="field.type !== 10 && field.type !== 15" class="input" style="font-size: 10px !important; height: auto;">
                        <span class="mr-2">{{ field.title }}: </span><span v-html="field.value"/>
                    </div>
                </div>
                <div class="mb-2 py-0 col-12">
                    <div class="input has-background-light" style="font-size: 10px !important; height: auto;">
                        <span class="mr-2">وضعیت فرم:</span>
                        <span :class="currentRow?.status?.class">{{ currentRow?.status?.text }}</span>
                        <span class="ml-2" v-if="currentRow?.rejects?.length">
                            <span class="is-weight-700">{{ currentRow?.rejects[currentRow?.rejects?.length-1]?.comment }}</span>
                            <span class="mx-2">- در تاریخ</span>
                            <bdi class="is-weight-700">{{ $helpers.jDate(currentRow?.rejects[currentRow?.rejects?.length-1]?.created_at, 'jYYYY/jMM/jDD HH:MM:ss') }}</bdi>
                        </span>
                    </div>
                </div>
                <div class="mb-2 py-0 col-12" v-if="formInvoice?.id">
                    <div class="input has-background-light" style="font-size: 10px !important; height: auto;">
                        <span class="mr-2">وضعیت پرداخت:</span>
                        <span class="tag is-success is-rounded" v-if="formInvoice?.status===1">موفقیت‌آمیز</span>
                        <span class="tag is-info is-outlined is-rounded" v-else-if="formInvoice?.status===0">منتظر پرداخت</span>
                        <span class="tag is-danger is-rounded" v-else>خطای پرداخت</span>
                        <span class="ml-2">
                        <span>شماره تراکنش:</span>
                        <span class="is-weight-700">{{ formInvoice?.callbackId }}</span>
                        <span class="mx-2">- تاریخ پرداخت</span>
                        <bdi class="is-weight-700">{{ $helpers.jDate(formInvoice?.created_at, 'jYYYY/jMM/jDD HH:MM:ss') }}</bdi>
                    </span>
                    </div>
                </div>
            </div>
        </b-card>
    </b-modal>
    <b-modal
        :close-button='false'
        :closable="false"
        header-content="انتخاب فیلدهای خروجی"
        ref="fieldsModal"
        >
        <button class="button is-light is-dark is-outlined mr-2" @click="selectAllFields">انتخاب همه</button>
        <button class="button is-light is-dark is-outlined mr-2" @click="selectedFieldsToExport=[]">انتخاب هیچکدام</button>
        <button class="button is-light is-dark is-outlined"      @click="selectInverse">انتخاب برعکس</button>
        <div class="is-divider mt-5 mb-3"></div>
        <b-checkbox
            v-model="selectedFieldsToExport"
            :options="fieldsToExport"
            :stack="true"
        >
        </b-checkbox>
        <template #footer>
            <div class="container">
                <div class="is-flex is-flex-direction-row is-justify-content-flex-end">
                    <button class="button is-success" @click="completeExportation(fileType)" :disabled="!selectedFieldsToExport?.length">ایجاد خروجی</button>
                    <button class="button" @click="$refs.fieldsModal.modalClose()">انصراف</button>
                </div>
            </div>
        </template>
    </b-modal>
    <b-modal
        :close-button='false'
        :closable="false"
        header-content="ویرایش وضعیت فرم"
        ref="editStatus"
        >
        <div class="columns is-multiline" style="height: 275px">
            <div class="column is-6">
                <b-input
                    label="وضعیت کنونی"
                >
                    <template #input>
                        <div class="input has-background-light">{{ currentRow.status.text }}</div>
                    </template>
                </b-input>
            </div>
            <div class="column is-6">
                <b-select
                    label="وضعیت جدید"
                    v-model="newStatus"
                    :options="statuses"
                >
                </b-select>
            </div>
            <div class="column is-12">
                <b-input
                    label="توضیحات"
                    :description="newComment?.length < 10 ? ('حداقل '+(10 - newComment?.length)+' کاراکتر توضیحات الزامی است') : (newComment?.length + ' / 250')"
                >
                    <template #input>
                        <textarea class="textarea" v-model="newComment"></textarea>
                    </template>
                </b-input>
            </div>
        </div>
        <template #footer>
            <div class="container">
                <div class="is-flex is-flex-direction-row is-justify-content-flex-end">
                    <button class="button is-success" @click="completeEditFormStatus" :disabled="newStatus == currentRow.status.status || newComment?.length < 10 || newComment?.length > 250">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                        <i class="fal fa-save" v-else></i>
                        <span class="ml-2">ویرایش وضعیت</span>
                    </button>
                    <button class="button" @click="$refs.editStatus.modalClose()">انصراف</button>
                </div>
            </div>
        </template>
    </b-modal>
    <b-modal
        :close-button='false'
        :closable="false"
        header-content="مشاهده تغییرات وضعیت"
        ref="showRejects"
        >
        <div class="columns is-multiline">
            <div class="column is-12" v-if="currentRow?.rejects?.length">
                <table class="table" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                            <th>توضیحات</th>
                            <th>کاربر</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="r in currentRow.rejects">
                        <td><bdi>{{ $helpers.jDate(r.created_at, 'jYYYY/jMM/jDD HH:MM') }}</bdi></td>
                        <td><span :class="options.statuses[r.status].class">{{ options.statuses[r.status].text }}</span></td>
                        <td>{{ r.comment }}</td>
                        <td>{{ r.user?.username || '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-12" v-else>
                <div class="w-100 my-6 py-6 has-text-centered" >
                    <i class="fal fa-exclamation-triangle has-text-danger"></i>
                    <span class="ml-2">موردی یافت نشد.</span>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="container">
                <div class="is-flex is-flex-direction-row is-justify-content-flex-end">
                    <button class="button" @click="$refs.showRejects.modalClose()">بستن</button>
                </div>
            </div>
        </template>
    </b-modal>
    <b-modal
        :closable="false"
        :append-to="body"
        :close-button="false"
        :escape-control="false"
        :is-card="false"
        ref="pdfLoader"
    >
        <b-card no-footer no-header class="no-margin" body-class="my-2">
            <div class="has-text-centered mt-3 sahel is-weight-900" style="font-size:1.75rem;">در حال ایجاد خروجی PDF</div>
            <div class="has-text-centered my-5 has-text-grey-lighter"><i class="fa fa-spinner-third fa-spin fa-2x"></i></div>
            <progress class="progress is-info"/>
        </b-card>
    </b-modal>
</template>

<script>
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';
import Vue3Html2pdf from 'vue3-html2pdf'
import { jsontoexcel } from "vue-table-to-excel";

export default {
    name: "AdminFormInboxComponent",
    data() {
        return {
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            site_name: Settings?.site_name || '',
            site_logo: Settings?.site_logo || import.meta.env.VITE_LOGO,
            base_url: window.location.origin,
            forms: [],
            form: {},
            formInvoice: null,
            form_id: null,
            original_form: null,
            formInbox: [],
            loading: false,
            getInbox: false,
            action: 'select-form',
            inboxHeaders: [],
            currentPage: 1,
            perPage: 10,
            startPdfDownload: false,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            options: [],
            statuses: [],
            selectedFieldsToExport: [],
            fieldsToExport: [],
            fileType: '',
            hasPayment: false,
            currentRow: [],
            newStatus: 0,
            newComment: '',
            file_ids: [],
            filters: {
                text: '',
                payment: [],
                status: [],
            },
            totalRecords: 0,
        }
    },
    components: {
        Vue3Html2pdf
    },
    created() {
        this.getFormOptions()
        this.getForms()
    },
    computed: {
        computedData() {
            let table = this.formInbox

            if (this.filters.text !== false && this?.filters.text?.length > 0) {
                table = table.filter(item => {
                    let found = false
                    for (const itemKey in item) {
                        if (typeof item[itemKey] === 'object') {
                            const temp = item[itemKey]
                            for (const tempKey in temp) {
                                const t = (typeof temp[tempKey] === 'string' ? temp[tempKey] : (temp[tempKey] ? temp[tempKey].toString() : '')).toUpperCase()
                                if (t.includes(this.filters.text.toUpperCase())) {
                                    found = true
                                    break;
                                }
                            }
                        } else if (typeof item[itemKey] === 'string') {
                            const temp = (typeof item[itemKey] === 'string' ? item[itemKey] : (item[itemKey] ? item[itemKey].toString() : '')).toUpperCase()
                            if (temp.includes(this.filters.text.toUpperCase())) {
                                found = true
                            }
                        }
                        if (found) {break}
                    }
                    return found
                })
            }

            return table
        }
    },
    methods: {
        getFormOptions() {
            Requests.getFormOptions().then(res => {
                this.options = res?.result || {}
                this.statuses = []
                for (const key in this.options.statuses) {
                    this.statuses.push({
                        value: key,
                        text: this.options.statuses[key].text,
                    })
                }
            })
        },
        getForms() {
            if (!this.loading) {
                this.loading = true
                this.forms = []
                Requests.getFormsList().then(res => {
                    if (res?.status) {
                        for (let i = 0; i < res.result.length; i++) {
                            this.forms.push({
                                value: res.result[i].id,
                                text: res.result[i].title,
                                description: res.result[i].description,
                                user_ip: res.result[i].user_ip,
                                date: res.result[i].date,
                                fields: res.result[i].fields
                            })
                        }
                    } else {
                        this.$helpers.notify('خطا هنگام دریافت فهرست فرم‌ها', {type: 'error'})
                    }
                }).then(res => {
                    this.loading = false
                }).finally(() =>{
                    this.loading = false
                })
            }
        },
        loadMoreData() {
            if (!this.getInbox && (this.formInbox.length < this.totalRecords)) {
                this.getInbox = true
                Requests.getFormInboxMore(this.form_id, this.formInbox[this.formInbox.length - 1].inbox_id).then(res => {
                    if (res?.status) {
                        this.$helpers.notify(res.message)
                        this.totalRecords = res.result.length
                        for (let f = 0; f < res.result.length; f++) {
                            let tempRow = []
                            const fields = res.result[f].fields
                            tempRow = {
                                inbox_id: res.result[f]?.id,
                                index: this.formInbox.length+1,
                                status: {
                                    ...this.options?.statuses[res.result[f]?.status],
                                    status: res.result[f]?.status
                                },
                                rejects: res.result[f]?.rejects,
                                user_ip: res.result[f]?.user_ip,
                                transaction: res.result[f]?.transaction,
                                created_at: '<bdi>'+this.$helpers.jDate(res.result[f]?.date, 'jYYYY/jMM/jDD HH:MM')+'</bdi>',
                            }
                            for (let j = 0; j < this.form?.fields?.length; j++) {
                                if (this.form?.fields[j].type < 100 && this.form?.fields[j].type != 10) {
                                    const fieldName = this.form?.fields[j].name
                                    if (this.form?.fields[j].type === 15) {
                                        tempRow[fieldName] = fields[j]?.value?.split('|') || []
                                        for (let n = 0; n < tempRow[fieldName].length; n++) {
                                            const tempName = tempRow[fieldName][n]
                                            tempRow[fieldName][n] = tempRow[fieldName][n].split('__')
                                            tempRow[fieldName][n].push(tempName)
                                        }
                                    } else if (this.form?.fields[j].type === 3 && this.form?.fields[j].options=='true') {
                                        let value = parseInt(fields[j].value)
                                        value = isNaN(value) ? fields[j].value : value.toLocaleString()
                                        tempRow[fieldName] = value
                                    } else if (this.form?.fields[j].type === 4) {
                                        tempRow[fieldName] = '<bdi>'+this.$helpers.jDate(fields[j]?.value, 'jYYYY/jMM/jDD')+'</bdi>'
                                    } else if (this.form?.fields[j].type === 5) {
                                        tempRow[fieldName] = '<bdi>'+this.$helpers.jDate(fields[j]?.value, 'jYYYY/jMM/jDD HH:MM')+'</bdi>'
                                    } else if (this.form?.fields[j].type === 31) {
                                        let amount = parseInt(fields[j]?.options || 0)
                                        amount = isNaN(amount) ? fields[j].options : amount.toLocaleString()
                                        tempRow[fieldName] = amount
                                    } else {
                                        tempRow[fieldName] = fields[j]?.value
                                    }
                                }
                            }
                            tempRow['actions'] = ''
                            if (this.hasPayment) {
                                tempRow['invoice'] = res.result[f]?.invoice || null
                            }
                            this.formInbox.push(tempRow)
                        }
                    } else {
                        this.$helpers.notify('خطا هنگام دریافت ادامه صندوق ورودی', {type: 'error'})
                    }
                }).then(res => {
                    this.getInbox = false
                }).finally(() =>{
                    this.getInbox = false
                })
            }
        },
        getFormInbox() {
            if (!this.getInbox) {
                this.form = []
                this.getInbox = true
                for (let i = 0; i < this.forms.length; i++) {
                    if (this.forms[i].value === this.form_id) {
                        this.form = this.forms[i]
                        break
                    }
                }
                this.inboxHeaders = [
                    {id: 'action',      title: '', thStyle: 'width: 50px'},
                    {id: 'index',       title: 'ردیف', thStyle: 'width: 50px'},
                    {id: 'status',      title: 'وضعیت فرم', thStyle: 'width: 100px'},
                    {id: 'user_ip',     title: 'IP کاربر', thStyle: 'width: 100px'},
                    {id: 'transaction', title: 'کد پیگیری', thStyle: 'width: 100px'},
                    {id: 'created_at',  title: 'زمان ارسال', thStyle: 'min-width: 120px'},
                ]
                this.original_form = this.form?.fields
                this.file_ids = []
                for (let i = 0; i < this.form?.fields?.length; i++) {
                    if (this.form?.fields[i].type<100 && this.form?.fields[i].type != 10) {
                        if (this.form?.fields[i].type === 15) {
                            this.file_ids.push(this.form?.fields[i].name)
                        }
                        if (this.form?.fields[i].type === 31) {
                            this.hasPayment = true
                        }
                        this.inboxHeaders.push({
                            id: this.form?.fields[i].name,
                            title: this.form?.fields[i].title,
                            thStyle: 'min-width: 100px'
                        })
                    }
                }
                if (this.hasPayment) {
                    this.inboxHeaders.push({id: 'invoice', title: 'وضعیت پرداخت'})
                }
                Requests.getFormInbox(this.form_id).then(res => {
                    if (res?.status) {
                        this.totalRecords = res.total_records
                        this.formInbox = []
                        for (let f = 0; f < res.result.length; f++) {
                            let tempRow = []
                            const fields = res.result[f].fields
                            tempRow = {
                                inbox_id: res.result[f]?.id,
                                index: f+1,
                                status: {
                                    ...this.options?.statuses[res.result[f]?.status],
                                    status: res.result[f]?.status
                                },
                                rejects: res.result[f]?.rejects,
                                user_ip: res.result[f]?.user_ip,
                                transaction: res.result[f]?.transaction,
                                created_at: '<bdi>'+this.$helpers.jDate(res.result[f]?.date, 'jYYYY/jMM/jDD HH:MM')+'</bdi>',
                            }
                            for (let j = 0; j < this.form?.fields?.length; j++) {
                                if (this.form?.fields[j].type < 100 && this.form?.fields[j].type != 10) {
                                    const fieldName = this.form?.fields[j].name
                                    if (this.form?.fields[j].type === 15) {
                                        tempRow[fieldName] = fields[j]?.value?.split('|') || []
                                        for (let n = 0; n < tempRow[fieldName].length; n++) {
                                            const tempName = tempRow[fieldName][n]
                                            tempRow[fieldName][n] = tempRow[fieldName][n].split('__')
                                            tempRow[fieldName][n].push(tempName)
                                        }
                                    } else if (this.form?.fields[j]?.type === 3 && this.form?.fields[j]?.options=='true') {
                                        let value = parseInt(fields[j]?.value)
                                        value = isNaN(value) ? fields[j]?.value : value.toLocaleString()
                                        tempRow[fieldName] = value
                                    } else if (this.form?.fields[j]?.type === 4) {
                                        tempRow[fieldName] = '<bdi>'+this.$helpers.jDate(fields[j]?.value, 'jYYYY/jMM/jDD')+'</bdi>'
                                    } else if (this.form?.fields[j]?.type === 5) {
                                        tempRow[fieldName] = '<bdi>'+this.$helpers.jDate(fields[j]?.value, 'jYYYY/jMM/jDD HH:MM')+'</bdi>'
                                    } else if (this.form?.fields[j]?.type === 31) {
                                        let amount = parseInt(fields[j]?.options || 0)
                                        amount = isNaN(amount) ? fields[j]?.options : amount.toLocaleString()
                                        tempRow[fieldName] = amount
                                    } else {
                                        tempRow[fieldName] = fields[j]?.value
                                    }
                                }
                            }
                            tempRow['actions'] = ''
                            if (this.hasPayment) {
                                tempRow['invoice'] = res.result[f]?.invoice || null
                            }
                            this.formInbox.push(tempRow)
                        }
                        this.action='show-inbox'
                    } else {
                        this.$helpers.notify('خطا هنگام دریافت صندوق ورودی فرم مورد نظر', {type: 'error'})
                    }
                }).then(res => {
                    this.getInbox = false
                }).finally(() =>{
                    this.getInbox = false
                })
            }
        },
        hasGenerated() {
            this.$refs.pdfLoader.modalClose()
        },
        downloadForm(form, download = true) {
            for (let i = 0; i < this.original_form.length; i++) {
                this.original_form[i] = {
                    ...this.original_form[i],
                    value: form[this.original_form[i].name],
                }
            }
            this.currentRow  = form
            this.formInvoice = form.invoice
            if (download) {
                this.$refs.pdfLoader.modalOpen()
                this.$nextTick(() => {
                    this.$refs.html2Pdf.generatePdf()
                })
            } else {
                this.$refs.viewForm.modalOpen()
                this.$swal.close();
            }
        },
        exportTable(fileType) {
            this.fileType = fileType
            this.fieldsToExport = [];
            this.selectedFieldsToExport = [];
            for (let i = 1; i < this.inboxHeaders.length; i++) {
                if (this.inboxHeaders[i].id !== 'actions') {
                    this.fieldsToExport.push({
                        value: this.inboxHeaders[i].id,
                        text: this.inboxHeaders[i].title,
                    })
                }
            }

            this.$refs.fieldsModal.modalOpen()
        },
        completeExportation(fileType) {
            if (this.selectedFieldsToExport?.length) {
                let head = []
                let data = []

                for (let i = 1; i < this.inboxHeaders.length; i++) {
                    if (this.selectedFieldsToExport.includes(this.inboxHeaders[i].id)) {
                        head.push(this.inboxHeaders[i].title)
                    }
                }

                for (let i = 0; i < this.formInbox.length; i++) {
                    let singleData = {}
                    for (let j = 0; j < this.selectedFieldsToExport.length; j++) {
                        let tmp = ''
                        if (this.selectedFieldsToExport[j] == 'status') {
                            tmp = this.formInbox[i].status.text
                        } else if (this.selectedFieldsToExport[j] == 'invoice') {
                            tmp = this.formInbox[i].invoice.status === 1 ? 'موفقیت آمیز' : (this.formInbox[i].invoice.status === 0 ? 'منتظر پرداخت' : 'خطای پرداخت')
                            if (this.formInbox[i]?.invoice?.callbackId) {
                                tmp += ' شماره تراکنش: ' +this.formInbox[i]?.invoice?.callbackId+ ' - ' +this.$helpers.jDate(this.formInbox[i]?.invoice?.created_at, 'jYYYY/jMM/jDD HH:MM')
                            }
                        } else {
                            tmp = this.$helpers.stripHtml(this.formInbox[i][this.selectedFieldsToExport[j]])
                        }

                        singleData[head[j]] = tmp
                    }
                    data.push(singleData)
                }
                const filename = this.form.text+'.'+fileType;
                let tab_text
                const ex_type = fileType.toLowerCase().includes('xls') ? 'application/vnd.ms-excel;charset=utf-8' : 'text/csv;charset=utf-8'

                if (fileType.toLowerCase().includes('xls')) {
                    let dataTable = document.createElement('table')
                    dataTable.style.fontFamily = "Vazir, 'B Nazanin', Tahoma"
                    dataTable.style.fontSize = '12px'
                    dataTable.style.direction = 'rtl'
                    dataTable.classList.add('table', 'is-hoverable', 'is-striped')
                    let dataTableHeader = document.createElement('thead')
                    let dataTableBody   = document.createElement('tbody')
                    let headerRow = document.createElement('tr')
                    for (let x = 0; x < head.length; x++) {
                        const headerCell = document.createElement('th')
                        headerCell.innerText = head[x]
                        headerRow.appendChild(headerCell)
                    }
                    for (let y = 0; y < data.length; y++) {
                        let bodyRow = document.createElement('tr')
                        for (let x = 0; x < head.length; x++) {
                            const bodyCell = document.createElement('td')
                            bodyCell.innerText = data[y][head[x]]
                            bodyRow.appendChild(bodyCell)
                        }
                        dataTableBody.appendChild(bodyRow)
                    }

                    dataTableHeader.appendChild(headerRow)
                    dataTable.appendChild(dataTableHeader)
                    dataTable.appendChild(dataTableBody)

                    tab_text = dataTable.outerHTML
                } else {
                    tab_text = head.toString() + '\n'
                    for (let i = 0; i < data.length; i++) {
                        for (let item in data[i]) {
                            const t = data[i][item]?.replace(',', '') || ''
                            tab_text += t + ','
                        }
                        tab_text += '\n'
                    }
                }

                let downloadLink
                downloadLink = document.createElement("a");
                document.body.appendChild(downloadLink);
                if(navigator.msSaveOrOpenBlob){
                    let blob = new Blob(['\ufeff', tab_text], {
                        type: ex_type
                    });
                    navigator.msSaveOrOpenBlob( blob, filename);
                } else {
                    downloadLink.href = 'data:' + ex_type + ',\ufeff' + encodeURIComponent(tab_text);
                    downloadLink.download = filename;
                    downloadLink.click();
                }


                // jsontoexcel.getXlsx(data, head, this.form.text+'.'+fileType);

                this.$nextTick(() => {
                    this.$refs.fieldsModal.modalClose()
                })
            } else {
                this.$helpers.notify('خطای ورودی', 'انتخاب حداقل یک فیلد برای ایجاد خروجی الزامی است', {type: 'error'})
            }
        },
        selectAllFields() {
            let selected = []
            for (let i = 0; i < this.fieldsToExport.length; i++) {
                selected.push(this.fieldsToExport[i].value)
            }
            this.selectedFieldsToExport = selected
        },
        selectInverse() {
            let selected = []
            for (let i = 0; i < this.fieldsToExport.length; i++) {
                if (!this.selectedFieldsToExport.includes(this.fieldsToExport[i].value)) {
                    selected.push(this.fieldsToExport[i].value)
                }
            }
            this.selectedFieldsToExport = selected
        },
        editForm(item) {
            this.currentRow = item
            this.newStatus = this.currentRow.status.status
            this.newComment = ''
            this.$refs.editStatus.modalOpen()
        },
        completeEditFormStatus() {
            if (!this.loading) {
                this.loading = true
                Requests.editFormStatus(this.currentRow.inbox_id, this.newStatus, this.newComment).then(res => {
                    if (res?.status) {
                        for (let i = 0; i < this.formInbox.length; i++) {
                            if (this.formInbox[i].inbox_id === this.currentRow.inbox_id) {
                                this.formInbox[i].rejects = res?.result
                                this.formInbox[i].status = {
                                    ...this.options?.statuses[this.newStatus],
                                    status: this.newStatus
                                }
                                break;
                            }
                        }
                    }
                }).finally(res => {
                    this.loading = false
                    this.$refs.editStatus.modalClose()
                })
            }
        },
        showRejectsModal(form) {
            this.currentRow = form
            this.$refs.showRejects.modalOpen()
        },
    }
}
</script>

<style scoped>

</style>
