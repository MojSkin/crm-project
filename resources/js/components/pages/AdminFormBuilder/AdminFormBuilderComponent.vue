<template>
    <AdminLayoutComponent>
        <template #title>فرم‌ساز</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newItem" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>فرم جدید</span>
                    </button>
                    <button @click="refreshTable()" class="button is-solid is-flex align-items-center text-accent hint--light hint--rounded hint--top" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست فرم‌ها">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading" class="is-flex align-items-center">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>

        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing}" class="overflow-visible">
            <b-table
                :headers="headers"
                :data="itemsList"
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
                <template #count="data">
                    {{ data?.item?.count?.length > 0 ? data?.item?.count?.length : '-' }}
                </template>
                <template #response="data">
                    {{ options?.responses[data.value] || '' }}
                </template>
                <template #active="data">
                    <button class="btn btn-outline text-accent hint--light hint--rounded hint--top" data-hint="فعال / غیرفعال کردن فرم" @click="changeFormStatus(data?.item?.id)">
                        <i class="fal" :class="data.value?'fa-check text-success':'fa-times text-danger'"></i>
                    </button>
                </template>
                <template #action="data">
                    <a class="btn btn-outline is-primary px-2" :href="base_url+'/preview/'+data.item.id" :target="'preview-form-'+data.item.id">
                        <i class="fa fa-eye"></i>
                    </a>
                    <button class="btn btn-outline text-primary px-2" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger px-2" @click="deleteItem(data.item)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
            <div class="" v-if="newRec || editing">
                <div class="columns is-multiline">
                    <div class="column is-12 is-6-desktop">
                        <b-input
                            label='عنوان فرم:'
                            :is-error="validator?.form?.title?.$errors?.length"
                            :error-message="validator?.form?.title?.$errors[0]?.$message || ''"
                            v-model="form.title"
                            placeholder="عنوان فرم"
                        ></b-input>
                    </div>
                    <div class="column is-12 is-6-desktop">
                        <b-input
                            label='توضیحات فرم:'
                            :is-error="validator?.form?.description?.$errors?.length"
                            :error-message="validator?.form?.description?.$errors[0]?.$message || ''"
                            v-model="form.description"
                            placeholder="عنوان فرم"
                        ></b-input>
                    </div>
                    <div class="column is-12">
                        <div class="is-divider py-0 my-0"></div>
                    </div>
                    <div class="column is-12" v-if="editingItem?.id > 0">
                        <div class="message is-danger">
                            <div class="message-body has-text-justified">
                                <h6 class="vazirmatn is-weight-700 text-danger">هرگونه تغییر در فیلدهای فرم، می‌تواند دسترسی به صندوق ورودی این فرم را غیرممکن سازد.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-12 is-7-desktop is-7-widescreen">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <b-dropdown @change="addNewField" :options="options.field_types || []" key="selectField" v-if="!(field_data?.id >= 0)">
                                            <template #placeholder>
                                                <span>{{ field_data?.showTitle || 'افزودن فیلد' }}</span>
                                            </template>
                                        </b-dropdown>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="field_data?.type">
                                    <div class="column is-12 pb-0">
                                        <h3>
                                            <span v-if="field_data.id && field_data.id>0">ویرایش</span>
                                            <span v-else>افزودن</span>
                                            <span class="text-info ml-2">{{ options?.field_types[field_data.type]?.title }}</span>
                                        </h3>
                                    </div>
                                    <div class="column is-12 pt-0">
                                        <div class="is-divider my-0"></div>
                                    </div>

                                    <div class="column is-12-mobile is-6 mb-3">
                                        <b-input
                                            label='عنوان فیلد:'
                                            description='عنوانی که کاربر هنگام ثبت فرم مشاهده می‌کند'
                                            :is-error="validator?.field_data?.title?.$errors?.length"
                                            :error-message="validator?.field_data?.title?.$errors[0]?.$message || ''"
                                            id="fieldTitle"
                                            v-model="field_data.title"
                                            placeholder="عنوان فیلد"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12-mobile is-6 mb-3">
                                        <b-input
                                            label='شناسه فیلد:'
                                            description='یک متن به عنوان راهنما که به صورت Tooltip و یک علامت سوال به کاربر نمایش داده خواهد شد'
                                            :is-error="validator?.field_data?.name?.$errors?.length"
                                            :error-message="validator?.field_data?.name?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.name"
                                            placeholder="عنوان فیلد"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-input
                                            label='راهنمای فیلد:'
                                            description='یک متن به عنوان راهنما که به صورت Tooltip و یک علامت سوال به کاربر نمایش داده خواهد شد'
                                            :is-error="validator?.field_data?.hint?.$errors?.length"
                                            :error-message="validator?.field_data?.hint?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.hint"
                                            placeholder="متن راهنما"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-input
                                            label='توضیحات فیلد:'
                                            description='یک متن راهنما که در زیر فیلد به کاربر نمایش داده خواهد شد (شبیه همین متن)'
                                            :is-error="validator?.field_data?.description?.$errors?.length"
                                            :error-message="validator?.field_data?.description?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.description"
                                            placeholder="عنوان فیلد"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12-mobile mb-3" :class="field_data.type_id !== 10 && field_data.type_id !== 31 && field_data.type_id !== 15 ? 'is-6' : 'is-12'" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-input
                                            label='عنوان درون فیلد:'
                                            description='متن Placeholder'
                                            :is-error="validator?.field_data?.placeholder?.$errors?.length"
                                            :error-message="validator?.field_data?.placeholder?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.placeholder"
                                            placeholder="عنوان درون فیلد"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12-mobile is-6 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31 && field_data.type_id !== 15">
                                        <b-input
                                            label='محتوای پیش‌فرض:'
                                            description='محتوایی که به صورت پیش‌فرض از ابتدا درون این فیلد قرار خواهد گرفت'
                                            :is-error="validator?.field_data?.default?.$errors?.length"
                                            :error-message="validator?.field_data?.default?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.default"
                                            placeholder="محتوای پیش‌فرض"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12" v-if="field_data.type_id === 15">
                                        <b-card no-footer no-header class="has-background-warning has-text-centered is-weight-700 has-text-grey-dark">
                                            <span>حداکثر حجم مجاز تعیین شده برای آپلود هر فایل برابر با</span>
                                            <span class="mx-2 has-text-primary">{{ $helpers.humanReadableFileSize(max_allowed_file_size / 1024) }}</span>
                                            <span>می‌باشد</span>
                                        </b-card>
                                    </div>
                                    <div class="column mb-3" :class="['checkbox', 'radio', 'select', 'html', 'payment'].includes(field_data.type) || (field_data.type_id==15 && key=='options') ? 'is-12' : 'is-6'" v-for="(prop, key) in options.field_types[field_data.type].props" :key="key" v-if="field_data.type_id">
                                        <b-input
                                            :label="(options?.field_types[field_data.type]?.label[key] || '') + ':'"
                                            :description="options?.field_types[field_data?.type]?.hint[key] || ''"
                                            :is-error="validator?.field_data[key]?.name?.$errors?.length"
                                            :error-message="validator?.field_data[key]?.name?.$errors[0]?.$message || ''"
                                            type="number"
                                            class="hide-spin"
                                            v-model="field_data[key]"
                                            :placeholder="options?.field_types[field_data.type]?.label[key] || ''"
                                        >
                                            <template #input v-if="['date', 'datetime', 'time'].includes(field_data.type)">
                                                <date-picker
                                                    :format="field_data.type_id === 4 ? 'YYYY-MM-DD' : (field_data.type_id === 5 ? 'YYYY-MM-DD HH:mm:ss' : 'HH:mm')"
                                                    :display-format="field_data.type_id === 4 ? 'jYYYY/jMM/jDD' : (field_data.type_id === 5 ? 'jYYYY/jMM/jDD HH:mm' : 'HH:mm')"
                                                    :type="field_data.type"
                                                    clearable
                                                    v-model="field_data[key]"
                                                    input-class="is-ltr has-text-left input"
                                                    :key="field_data.type+key"
                                                ></date-picker>
                                            </template>
                                            <template #input v-if="['checkbox', 'radio', 'select', 'html'].includes(field_data.type)">
                                                <textarea
                                                    v-model="field_data[key]"
                                                    rows="3"
                                                    class="textarea no-resize"
                                                />
                                            </template>
                                            <template #input v-if="field_data.type_id==15 && key=='options'">
                                                <textarea
                                                    v-model="field_data[key]"
                                                    rows="3"
                                                    class="textarea no-resize is-ltr"
                                                />
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column mb-3 is-12" v-if="field_data.type_id===3">
                                        <b-switch
                                            id="use-separator"
                                            v-model="field_data.options"
                                        >
                                            <span class="is-inline-block mr-1">استفاده از جداکننده ارقام: </span>
                                            <span class="is-inline-block has-text-success" v-if="field_data.options">بلی</span>
                                            <span class="is-inline-block has-text-danger" v-else>خیر</span>
                                        </b-switch>
                                    </div>
                                    <div class="column is-12 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-switch
                                            id="my-switch"
                                            v-model="field_data.is_required"
                                            :checked="field_data.is_required==1"
                                        >
                                            <span class="is-inline-block mr-1">ورود این فیلد اجباری</span>
                                            <span class="is-inline-block has-text-success" v-if="field_data.is_required">است</span>
                                            <span class="is-inline-block has-text-danger" v-else>نیست</span>
                                        </b-switch>
                                    </div>
                                    <div class="column is-12 mb-3">
                                        <b-switch
                                            id="my-switch"
                                            v-model="field_data.is_printable"
                                            :checked="field_data.is_printable==1"
                                        >
                                            <span class="is-inline-block mr-1">این فیلد در چاپ نمایش</span>
                                            <span class="is-inline-block has-text-success" v-if="field_data.is_printable">داده شود</span>
                                            <span class="is-inline-block has-text-danger" v-else>داده نشود</span>
                                        </b-switch>
                                    </div>
                                    <div class="column is-6 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-input
                                            label='کلاس(های) دلخواه:'
                                            description='کلاس‌های CSS دلخواه خود را با فاصله از یکدیگر وارد کنید'
                                            :is-error="validator?.field_data?.custom_class?.$errors?.length"
                                            :error-message="validator?.field_data?.custom_class?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.custom_class"
                                            placeholder="کلاس(های) دلخواه"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6 mb-3" v-if="field_data.type_id !== 10 && field_data.type_id !== 31">
                                        <b-input
                                            label='آیدی (ID) دلخواه:'
                                            description='ID دلخواه خود را وارد کنید'
                                            :is-error="validator?.field_data?.custom_id?.$errors?.length"
                                            :error-message="validator?.field_data?.custom_id?.$errors[0]?.$message || ''"
                                            id="fieldName"
                                            v-model="field_data.custom_id"
                                            placeholder="آیدی (ID) دلخواه"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6-mobile is-3 mb-3" v-if="field_data.type_id !== 31">
                                        <b-select
                                            label='چیدمان مانیتور عریض'
                                            description='سایز نمایش فیلد در مانیتورهای عریض را انتخاب کنید'
                                            :is-error="validator?.field_data?.col_widescreen?.$errors?.length"
                                            :error-message="validator?.field_data?.col_widescreen?.$errors[0]?.$message || false"
                                            v-model="field_data.col_widescreen"
                                            :options="field_size"
                                        ></b-select>
                                    </div>
                                    <div class="column is-6-mobile is-3 mb-3" v-if="field_data.type_id !== 31">
                                        <b-select
                                            label='چیدمان لپتاپ / دسکتاپ'
                                            description='سایز نمایش فیلد در لپتاپ / دسکتاپ را انتخاب کنید'
                                            :is-error="validator?.field_data?.col_desktop?.$errors?.length"
                                            :error-message="validator?.field_data?.col_desktop?.$errors[0]?.$message || false"
                                            v-model="field_data.col_desktop"
                                            :options="field_size"
                                        ></b-select>
                                    </div>
                                    <div class="column is-6-mobile is-3 mb-3" v-if="field_data.type_id !== 31">
                                        <b-select
                                            label='چیدمان تبلت'
                                            description='سایز نمایش فیلد در تبلت را انتخاب کنید'
                                            :is-error="validator?.field_data?.col_tablet?.$errors?.length"
                                            :error-message="validator?.field_data?.col_tablet?.$errors[0]?.$message || false"
                                            v-model="field_data.col_tablet"
                                            :options="field_size"
                                        ></b-select>
                                    </div>
                                    <div class="column is-6-mobile is-3 mb-3" v-if="field_data.type_id !== 31">
                                        <b-select
                                            label='چیدمان موبایل'
                                            description='سایز نمایش فیلد در موبایل را انتخاب کنید'
                                            :is-error="validator?.field_data?.col_mobile?.$errors?.length"
                                            :error-message="validator?.field_data?.col_mobile?.$errors[0]?.$message || false"
                                            v-model="field_data.col_mobile"
                                            :options="field_size"
                                        ></b-select>
                                    </div>
                                    <div class="column is-12 is-flex" v-if="field_data?.id >= 0 || field_data?.id?.includes('temp')">
                                        <button @click="cancelField" class="button mr-2 ml-auto">
                                            <span class="px-3">انصراف</span>
                                        </button>
                                        <button class="button is-success" @click="addField">
                                            <span v-if="field_data.id <= 0">افزودن </span>
                                            <span v-else>ویرایش </span>
                                            <span class="ml-1">فیلد</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="message">
                                    <div class="message-body has-text-justified">
                                        <p >با قابلیت Drag & Drop می‌توانید اولویت فیلدها را مشخص کنید.</p>
                                        <p class="has-text-primary is-weight-700">حتما پس از این کار، فرم را ذخیره کنید.</p>
                                    </div>
                                </div>
                                <div class="w-100 has-slimscroll" style="max-height: 485px">
                                    <b-draggable-table
                                        :headers="fields_headers"
                                        :data="fields"
                                        hoverable
                                        striped
                                        overflow
                                        draggable
                                        ref="fieldsTable"
                                    >
                                        <template #type="data">
                                            {{ options.field_types[data.value].title }}
                                        </template>
                                        <template #action="data">
                                            <div class="btn-group btn-group-sm font-size-10">
                                                <b-action direction="right">
                                                    <button @click="moveUp(data.item)" :disabled="data.item.tab_index<=1" class="dropdown-item font-size-base">
                                                        <i class="fal fa-arrow-up text-dark mr-3"></i>
                                                        <span>اولویت بالاتر</span>
                                                    </button>
                                                    <button @click="moveDown(data.item)" :disabled="data.item.tab_index>=fields.length" class="dropdown-item font-size-base">
                                                        <i class="fal fa-arrow-down text-dark mr-3"></i>
                                                        <span>اولویت بالاتر</span>
                                                    </button>
                                                    <hr class="dropdown-divider">
                                                    <button @click="duplicate(data.item)" class="dropdown-item font-size-base">
                                                        <i class="fal fa-copy mr-3"></i>
                                                        <span>کپی فیلد</span>
                                                    </button>
                                                    <hr class="dropdown-divider">
                                                    <button @click="editField(data.item)" class="dropdown-item font-size-base">
                                                        <i class="fal fa-pencil mr-3"></i>
                                                        <span>ویرایش فیلد</span>
                                                    </button>
                                                    <button @click="deleteField(data.item)" class="dropdown-item font-size-base">
                                                        <i class="fal fa-trash-alt text-danger mr-3"></i>
                                                        <span>حذف فیلد</span>
                                                    </button>
                                                </b-action>
                                            </div>
                                        </template>
                                    </b-draggable-table>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 mb-5" v-if="editingItem?.id > 0">
                            <div class="message is-danger">
                                <div class="message-body has-text-justified">
                                    <h6 class="vazirmatn is-weight-700 text-danger">هرگونه تغییر در فیلدهای فرم، می‌تواند دسترسی به صندوق ورودی این فرم را غیرممکن سازد.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <template #footer-left>
                <button @click="returnToList" class="button mr-2 ml-auto">
                    <i class="fal fa-arrow-alt-right mr-2"></i>
                    <span>بازگشت به فهرست</span>
                </button>
                <a class="button mr-2 ml-auto is-light is-primary is-outlined" :href="base_url+'/preview/'+editingItem?.id" :target="'preview-form-'+editingItem.id" v-if="editingItem?.id">
                    <i class="fa fa-eye mr-2"></i>
                    <span>پیش‌نمایش فرم</span>
                </a>
                <button class="button is-success px-5" :disabled="saving || loading" @click="saveForm">
                    <i class="fal fa-save mr-2" v-if="!saving"></i>
                    <span class="mr-1">ذخیره</span>
                    <span v-if="editing">تغییرات</span>
                    <span v-else>فرم</span>
                </button>
            </template>
        </b-card>
    </AdminLayoutComponent>

</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import BSelect from "@/components/global/Input/B-Select.vue";
import BAction from "@/components/global/Action/B-Action.vue";
import BCard from "@/components/global/Card/BCard.vue";
export default {
    name: "AdminFormBuilderComponent",
    components: {BCard, BAction, BSelect },
    inject: ['base_url'],
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
            options: {},
            tableLoading: false,
            saving: false,
            loading: false,
            editing: false,
            newRec: false,
            routing: false,
            route_id: null,
            direct_manager: false,
            position_id: null,
            department_id: null,
            editingItem: null,
            form: {
                title: null,
                description: null,
            },
            max_allowed_file_size: Settings.max_allowed_file_size,
            active: true,
            fields: [],
            field_data: {},
            fields_defaults: {
                type: '',
                name: '',
                title: '',
                hint: '',
                description: '',
                placeholder: '',
                default: '',
                is_required: false,
                is_printable: true,
                validator: [],
                tab_index: 1,
                col_mobile: 12,
                col_tablet: 6,
                col_desktop: 4,
                col_widescreen: 3,
                custom_id: '',
                custom_class: '',
            },
            itemsList: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 8%'},
                {id: 'title', title: 'عنوان فرم', thClass: '', tdClass: '', thStyle: '', tdStyle: '', sortable: true},
                {id: 'description', title: 'توضیحات فرم', thClass: '', tdClass: '', thStyle: '', tdStyle: '', sortable: true},
                {id: 'count', title: 'استفاده', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 50px;', tdStyle: ''},
                {id: 'active', title: 'فعال', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 50px;', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 110px;', tdStyle: ''},
            ],
            fields_headers: [
                {id: 'title', title: 'عنوان فیلد', sortable: false},
                {id: 'type', title: 'نوع فیلد', sortable: false},
                {id: 'action', title: '', thStyle: {width: '50px'}},
            ],
            field_size: [
                {value: 1, text: '1 از 12'},
                {value: 2, text: '2 از 12'},
                {value: 3, text: '3 از 12'},
                {value: 4, text: '4 از 12'},
                {value: 5, text: '5 از 12'},
                {value: 6, text: '6 از 12'},
                {value: 7, text: '7 از 12'},
                {value: 8, text: '8 از 12'},
                {value: 9, text: '9 از 12'},
                {value: 10, text: '10 از 12'},
                {value: 11, text: '11 از 12'},
                {value: 12, text: '12 از 12'},
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            sortBy: 'title',
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            filterOn: [],
            formFieldsCount: 0,
            types: {
                '1': 'text',
                '11': 'nCode',
                '12': 'pCode',
                '13': 'mobile',
                '14': 'email',
                '15': 'file',
                '2': 'textarea',
                '3': 'number',
                '31': 'payment',
                '4': 'date',
                '5': 'datetime',
                '6': 'time',
                '7': 'checkbox',
                '8': 'radio',
                '9': 'select',
                '10': 'html',
            },
            normalizer(node) {
                return node.children_recursive && node.children_recursive.length ? {
                    id: node.id,
                    label: node.title,
                    children: node.children_recursive,
                } : {
                    id: node.id,
                    label: node.title,
                }
            },
        }
    },
    validations () {
        return {
            form: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ارائه عنوان فرم الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'عنوان فرم باید حداقل 5 کاراکتر باشد'
                    },
                    hasField: {
                        $validator: () => {
                            return (this.fields?.length > 0)
                        },
                        $message: 'برای هر فرم باید حداقل یک فیلد ثبت شده باشد'
                    },
                },
                description: {
                    min: {
                        $validator: (val) => {
                            return (!val?.length || val?.length === 0 || val?.length >= 10)
                        },
                        $message: 'توضیحات فرم باید حداقل 10 کاراکتر باشد'
                    },
                },
            },

            field_data: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ارائه عنوان فیلد الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 2)
                        },
                        $message: 'عنوان فیلد باید حداقل 2 کاراکتر باشد'
                    },
                },
                name: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ارائه شناسه فیلد الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 2)
                        },
                        $message: 'شناسه فیلد باید حداقل 2 کاراکتر باشد'
                    },
                    unique: {
                        $validator: (val) => {
                            const skip = this.field_data?.id
                            for (let i = 0; i < this.fields?.length; i++) {
                                if (this.fields[i]?.name == val) {
                                    if (!this.fields[i].id) {
                                        return false
                                    } else {
                                        if (this?.fields[i]?.id != skip) {
                                            return false
                                        }
                                    }
                                }
                            }
                            return true
                        },
                        $message: 'شناسه فیلد نمی‌تواند تکراری باشد'
                    },
                },

            }
        }
    },
    created() {
        this.getFormOptions()
        this.refreshTable()
    },
    methods: {
        getFormOptions() {
            Requests.getFormOptions().then(res => {
                this.options = res?.result || {}
            })
        },
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getFormsList().then(res => {
                    this.itemsList = res?.result ?? []
                }).then(() => {
                    this.loading = false
                    this.$nextTick(e => {
                        this.$refs.dataTable.update()
                    })
                })
            }
        },
        changeFormStatus(form_id) {
            if (!this.saving) {
                this.saving = true
                Requests.changeFormStatus(form_id).then(res => {
                    for (let i = 0; i < this.itemsList.length; i++) {
                        if (this.itemsList[i].id === form_id) {
                            this.itemsList[i].active = !this.itemsList[i].active
                        }
                    }
                }).then(res => {
                    this.saving = false
                })
            }
        },
        newItem() {
            if (this.permission.add) {
                this.returnToList()
                this.newRec = true
            } else {
                this.$helpers.notify('خطای دسترسی', 'شما مجوز افزودن در این قسمت را ندارید!', {
                    type: 'error',
                });
            }
        },
        returnToList(returnBack = true) {
            this.tableLoading   = false
            this.saving         = false
            this.loading        = false
            this.editing        = false
            this.newRec         = false
            this.editingItem    = null
            this.fields         = []
            this.form           = {
                title: null,
                description: null,
            }
            this.validator.$reset()
        },
        cancelField() {
            this.field_data = {}
            this.validator.field_data.$reset()
        },
        addNewField(e) {
            console.log(e);
            if (e.type === 'payment' || e.id === 31) {
                for (let i = 0; i < this.fields.length; i++) {
                    console.log(this.fields[i].type_id);
                    if (this.fields[i].type_id === 31 || this.fields[i].type === 'payment') {
                        this.$helpers.notify('خطا', 'هر فرم فقط می‌تواند یک فیلد با نوع <span class="is-weight-700 has-text-danger">«مبلـغ»</span> داشته باشد.', { type: "error" })
                        return false
                    }
                }
            }
            this.field_data = {}
            for (const key in this.fields_defaults) {
                this.field_data[key] = this.fields_defaults[key]
            }
            for (const key in e.props) {
                this.field_data[key] = e.props[key]
            }
            this.field_data['id']        = 0
            this.field_data['showTitle'] = e?.title
            this.field_data['title']     = e?.title
            this.field_data['type_id']   = e.id
            this.field_data['type']      = e?.type
            this.field_data['tab_index'] = this.fields.length+1
            this.validator.field_data.$reset()
        },
        addField() {
            this.validator.field_data.$touch();
            if (!this.validator.field_data.$invalid) {
                if (this.field_data.id) {
                    for (let i = 0; i < this.fields.length; i++) {
                        if (this.fields[i].id==this.field_data.id) {
                            this.fields[i] = this.field_data
                            break;
                        }
                    }
                } else {
                    let field_data = {}
                    for (const key in this.field_data) {
                        field_data[key] = this.field_data[key]
                    }
                    this.fields.push(field_data)
                }
                for (let i = 0; i < this.fields.length; i++) {
                    this.fields[i].tab_index = i+1
                }
                // this.$refs.fieldsTable.update()
                this.field_data = {}
            }
        },
        editField(field) {
            // this.addNewField(field.type, type)
            this.field_data = {}
            for (const fieldKey in field) {
                this.field_data[fieldKey] = field[fieldKey]
            }
            // this.field_data = field
        },
        deleteField(field) {
            let oldField = (!isNaN(parseInt(field.id)) && parseInt(field.id)>0)

            if (oldField && field.used_count>0) {
                this.$swal.fire({
                    title: "حذف فیلد",
                    text: "فیلدهای قدیمی فرم‌هایی که توسط کاربران استفاده شده‌اند، قابل حذف نیستند.",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: "متوجه شدم",
                    showCloseButton: true,
                })
            } else {
                this.$swal.fire({
                    title: "حذف فیلد",
                    text: "فیلد مورد نظر حذف گردد؟",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "بلی",
                    cancelButtonText: "خیر",
                    showCloseButton: true,
                }).then(result => {
                    if (result.value) {
                        if (oldField) {
                            this.saving = true
                            Requests.deleteField(field.id, this.editingItem.id).then(res => {
                                if (res?.status) {
                                    for (let i = 0; i < this.fields.length; i++) {
                                        if (this.fields[i].id == field.id) {
                                            this.fields.splice(i, 1);
                                            this.$helpers.notify('فیلد مورد نظر با موفقیت حذف گردید.')
                                            break
                                        }
                                    }
                                } else {
                                    this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف فیلد مورد نظر با مشکل مواجه شد...', {
                                        type: "error",
                                    })
                                }
                            }).catch(err => {
                                console.log(err);
                            }).then(res => {
                                this.saving = false
                            })
                        } else {
                            for (let i = 0; i < this.itemsList.length; i++) {
                                if (this.fields[i].id == field.id) {
                                    this.fields.splice(i, 1);
                                    this.$helpers.notify('فیلد مورد نظر با موفقیت حذف گردید.')
                                    break
                                }
                            }
                        }
                    }
                });
            }
        },
        moveUp(field) {
            for (let i = 0; i < this.fields.length; i++) {
                if (this.fields[i].name===field.name) {
                    let temp = this.fields[i].tab_index
                    this.fields[i].tab_index = this.fields[i-1].tab_index
                    this.fields[i-1].tab_index = temp
                    break;
                }
            }
            this.fields.sort(function(a, b) {
                if (a.tab_index < b.tab_index) return -1;
                if (a.tab_index > b.tab_index) return 1;
                return 0;
            });
        },
        moveDown(field) {
            for (let i = 0; i < this.fields.length; i++) {
                if (this.fields[i].name===field.name) {
                    let temp = this.fields[i].tab_index
                    this.fields[i].tab_index = this.fields[i+1].tab_index
                    this.fields[i+1].tab_index = temp
                    break;
                }
            }
            this.fields.sort(function(a, b) {
                if (a.tab_index < b.tab_index) return -1;
                if (a.tab_index > b.tab_index) return 1;
                return 0;
            });
        },
        editItem(item) {
            if (this.permission.edit) {
                // this.returnToList()
                this.editing = true
                this.editingItem = item
                this.form = {
                    title: item.title,
                    description: item.description,
                }
                for (let i = 0; i < item.fields.length; i++) {
                    this.fields.push({
                        ...item.fields[i],
                        type_id: item.fields[i].type,
                        type: this.types[item.fields[i].type]
                    })
                }
            } else {
                this.$helpers.notify('شما مجوز ویرایش در این قسمت را ندارید!', {
                    type: 'error',
                    icon: 'fa fa-exclamation-triangle'
                });
            }
        },
        saveForm() {
            if (!this.saving) {
                let perm = false
                let message = ''
                if (this.editing) {
                    if (this.permission.edit) {
                        perm = true
                    } else {
                        message = 'شما مجوز ویرایش در این قسمت را ندارید!'
                    }
                } else {
                    if (this.permission.add) {
                        perm = true
                    } else {
                        message = 'شما مجوز افزودن در این قسمت را ندارید!'
                    }
                }
                if (perm) {
                    this.validator.form.$touch();
                    if (!this.validator.form.$invalid) {
                        this.saving = true
                        let formData = {
                            id: (this.editing?this.editingItem.id:null),
                            title: this.form.title,
                            description: this.form.description,
                            fields: this.fields
                        }
                        Requests.saveForm(formData).then(res => {
                            if (res?.status) {
                                if (res?.status) {
                                    if (this.editing) {
                                        for (let i = 0; i < this.itemsList.length; i++) {
                                            if (this.itemsList[i].id == res?.result.id) {
                                                this.itemsList[i] = res?.result
                                                break
                                            }
                                        }
                                        this.$helpers.notify(res?.message || 'فرم مورد نظر با موفقیت ویرایش شد.')
                                    } else {
                                        this.itemsList.push(res?.result);
                                        this.$helpers.notify('فرم جدید با موفقیت ثبت شد.');
                                    }
                                    this.returnToList(false)
                                    // this.sortItems(field)
                                } else {
                                    this.$helpers.notify('خطا', res?.message || 'بروز خطای ناشناخته!', {
                                        type: 'error',
                                    })
                                }
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطای ناشناخته!', {
                                    type: 'error',
                                })
                            }
                        }).catch(err => {
                        }).then(res => {
                            this.saving = false
                        })
                    }
                } else {
                    this.$helpers.notify('خطای دسترسی', message, {type: 'error'})
                }
            }
        },
        deleteItem(item) {
            if (this.permission.delete) {
                if (!this.saving) {
                    this.saving = true
                    Requests.usedFormCount(item.id).then(res => {
                        if (res?.status) {
                            if (res.count <= 0) {
                                this.$swal.fire({
                                    title: "حذف فرم درخواست",
                                    text: "حذف فرم یک عملیات بدون بازگشت می‌باشد. از حذف فرم جاری اطمینان کامل دارید؟",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "بلی",
                                    cancelButtonText: "خیر",
                                    showCloseButton: true,
                                }).then(result => {
                                    if (result.value) {
                                        this.saving = true
                                        Requests.deleteForm(item.id).then(res => {
                                            if (res?.status) {
                                                for (let i = 0; i < this.itemsList.length; i++) {
                                                    if (this.itemsList[i].id == item.id) {
                                                        this.itemsList.splice(i, 1);
                                                        this.totalRows = this.itemsList.length
                                                        this.$helpers.notify('فرم مورد نظر با موفقیت حذف گردید.')
                                                        break
                                                    }
                                                }
                                            } else {
                                                this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف فرم مورد نظر با مشکل مواجه شد...', {
                                                    type: "error",
                                                })
                                            }
                                        }).then(res => {
                                            this.saving = false
                                        })
                                    }
                                });
                            } else {
                                this.$helpers.notify('خطای کاربر', 'شما امکان حذف فرم استفاده شده را ندارید! در صورت عدم نیاز به فرم جاری، می‌توانید آن را غیرفعال کنید.', {
                                    type: 'error',
                                });
                            }
                        } else {
                            this.$helpers.notify('خطا', 'بروز خطا هنگام دریافت اطلاعات فرم‌های استفاده شده.', {
                                type: "error",
                            })
                        }
                    }).then(res => {
                        this.saving = false
                    })
                }
            } else {
                this.$helpers.notify('خطای دسترسی', 'شما مجوز حذف در این قسمت را ندارید!', {
                    type: 'error',
                });
            }
        },
        duplicate(item) {
            let duplicate = {
                ...item,
                id: 'temp-'+Math.random(1011111100, 9999999999),
                name: 'copy_' + item.name,
                title: 'کپی ' + item.title,
                tab_index: this.fields.length+1
            }
            this.fields.push(duplicate)
        },
    }
};
</script>

<style scoped>
.no-resize {
    resize: none;
}
</style>


