<template>
    <div class="columns is-multiline is-flex">
        <slot v-for="[key, field] of Object.entries(fields)" :name="field.name" v-if="renderComponent">
            <div
                 class="column"
                 :class="defineClass(field)"
            >
                <span v-if="field.type === 10 && (!printable || field.is_printable)" v-html="field.options"></span>
                <b-input
                    :label="field.title+(field.is_required ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                    :description="field.description"
                    :hint="field.hint"
                    :placeholder="field.placeholder"
                    :id="field.custom_id || field.name"
                    :name="field.name"
                    :is-error="hasError(field.name) && (field.type < 7 || field.type > 10)"
                    :error-message="getErrorMessage(field.name)"
                    :class="[field.custom_class, 'hide-spin', {'is-grouped': field.type==15}]"
                    v-model="field.value"
                    :steps="field.steps"
                    :type="field.type===1 ? 'text' : 'number'"
                    :min="field?.min || null"
                    :max="field?.max || null"
                    v-if="editable && !(field.type === 10 && (!printable || field.is_printable))"
                    @input="emitChanges"
                    @blur="checkValidation(field)"
                >
                    <template #input v-if="field.type === 15">
                        <div class="file is-boxed is-flex-direction-column" v-if="!field.value || field.value.length < 1">
                            <label class="file-label is-block">
                                <input class="file-input" type="file" :name="field.name" :accept="field.options" @change="handleFileSelect($event, key, field)" :multiple="field.max_rows>1">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fal fa-upload"></i>
                                    </span>
                                    <span class="file-label">انتخاب کنید...</span>
                                </span>
                            </label>
                            <span class="small is-block">
                                <span class="mr-2">فرمت‌های مورد قبول:</span><bdi>{{ field.options }}</bdi>
                            </span>
                        </div>
                        <div class="mt-2" v-else>
                            <span v-for="(file, index) in field.value">
                                <div class="h-avatar is-xl" v-if="file.type.includes('image/')">
                                    <img class="avatar" :src="file.imageUrl">
                                    <a class="badge button is-rounded is-warning d-block px-0" @click="deleteFile(key, index)"><i class="fal fa-times"></i></a>
                                </div>
                                <div  class="tags has-addons my-2" v-else>
                                    <a class="tag is-light">
                                        {{ file.name }}
                                    </a>
                                    <a class="tag is-delete is-warning" @click="deleteFile(key, index)"></a>
                                </div>
                            </span>
                        </div>
                    </template>
                    <template #input v-if="field.type === 31">
                        <div
                            class="input w-100 is-weight-700 has-background-light"
                            v-html="parseInt(field?.value || field?.options)?.toLocaleString() + ' ريال'"
                        />
                    </template>
                    <template #input v-if="field.type === 2">
                        <textarea
                            v-model="field.value"
                            :rows="field?.max_rows || 3"
                            class="textarea no-resize"
                            :class="{ 'is-error': hasError(field.name) }"
                            @input="emitChanges"
                        />
                    </template>
                    <template #input v-if="field.type >= 4 && field.type <= 6">
                        <date-picker
                            :format="field.type === 4 ? 'YYYY-MM-DD' : (field.type === 5 ? 'YYYY-MM-DD HH:mm:ss' : 'HH:mm')"
                            :display-format="field.type === 4 ? 'jYYYY/jMM/jDD' : (field.type === 5 ? 'jYYYY/jMM/jDD HH:mm' : 'HH:mm')"
                            :type="field.type === 4 ? 'date' : (field.type === 5 ? 'datetime' : 'time')"
                            :min="field?.min || null"
                            :max="field?.max || null"
                            :placeholder="field.placeholder"
                            clearable
                            v-model="field.value"
                            input-class="is-ltr has-text-left input"
                            @input="emitChanges"
                        ></date-picker>
                    </template>
                    <template #input v-if="field.type === 7">
                        <b-checkbox
                            :options="field.options.split('|')"
                            variant="success"
                            :is-error="hasError(field.name)"
                            :name="field.name"
                            v-model="field.value"
                            @change="emitChanges"
                        ></b-checkbox>
                    </template>
                    <template #input v-if="field.type === 8">
                        <b-radio
                            :options="field.options.split('|')"
                            variant="success"
                            :is-error="hasError(field.name)"
                            :name="field.name"
                            v-model="field.value"
                            @change="emitChanges"
                        ></b-radio>
                    </template>
                    <template #input v-if="field.type === 9">
                        <b-select
                            :options="field.options.split('|')"
                            :is-error="hasError(field.name)"
                            v-model="field.value"
                            searchable
                            autoclose
                            @select="emitChanges"
                        ></b-select>
                    </template>
                </b-input>
                <div v-if="field.type !== 10 && !editable" class="w-100 has-text-justified input">
                    <span>{{ field.title }}:</span> <span class="ml-2" v-html="field.value"></span>
                </div>
            </div>
        </slot>
    </div>
</template>

<script>
// import {isNull} from "url/util";

export default {
    name: "FormContentComponent",
    props: {
        modelValue: {
            default: [Array, Object],
        },
        errors: Array,
        printable: Boolean,
        editable: {
            type: Boolean,
            default: true
        },
    },
    data() {
        return {
            fields: this.modelValue,
            hasPayment: false,
            errorArray: this.errors,
            renderComponent: true,
        }
    },
    created() {
    },
    mounted() {
    },
    methods: {
        defineClass(field) {
            let classes = []
            classes.push(typeof field.col_mobile === 'number' ? 'is-'+field.col_mobile+'-mobile' : field.col_mobile)
            classes.push(typeof field.col_tablet === 'number' ? 'is-'+field.col_tablet+'-tablet' : field.col_tablet)
            classes.push(typeof field.col_desktop === 'number' ? 'is-'+field.col_desktop+'-desktop' : field.col_desktop)
            classes.push(typeof field.col_widescreen === 'number' ? 'is-'+field.col_widescreen+'-widescreen' : field.col_widescreen)
            return classes.join(' ')
        },
        hasError(field) {
            return this.errorArray[field]?.length ? this.errorArray[field]?.length > 0 : false
        },
        getErrorMessage(field) {
            return this.errorArray[field]?.length ? this.errorArray[field][0] : ''
        },
        checkValidation(field) {
            if (field?.validator && field?.validator?.length) {
                if (typeof field?.validator === 'string') {
                    field.validator = field?.validator?.split('|') || []
                }
                if (this.errorArray.hasOwnProperty(field.name)) {
                    delete this.errorArray[field.name]
                }
                let errors = []
                for (let i = 0; i < field.validator.length; i++) {
                    const v = this.validate(field, field.validator[i])
                    if (v !== true) {
                        errors.push(v)
                    }
                }
                if (errors.length) {
                    this.errorArray[field.name] = errors
                    this.$emit('error', this.errorArray)
                }
            }
        },
        validate(field, validation) {
            let is_validated = true
            validation = validation.split(':')
            const rule = validation[0].toUpperCase()
            const params = validation[1]?.split(',') || []
            if ((rule !== 'REQUIRED' && field.type === 15) || field.type === 10) { return is_validated }
            switch (rule) {
                case 'REQUIRED':
                    if (typeof field.value === 'undefined' || (typeof field.value === 'object' && field.value === null) || field.value?.length < 1) {
                        is_validated = 'فیلد '+field.title+' الزامی است'
                    }
                    break;
                case 'MIN':
                    if (typeof field.value === 'string' && field.value?.length > 0 && field.value?.length < params[0]) {
                        is_validated = 'فیلد '+field.title+' باید حداقل '+params[0]+' کاراکتر باشد'
                    }
                    break;
                case 'MAX':
                    if (typeof field.value === 'string' && field.value?.length > 0 && field.value?.length > params[0]) {
                        is_validated = 'فیلد '+field.title+' باید حداکثر '+params[0]+' کاراکتر باشد'
                    }
                    break;
                case 'BETWEEN':
                    if (typeof field.value === 'undefined' || (typeof field.value === 'object' && field.value === null) || (field.value?.length < params[0] || field.value?.length > params[1])) {
                        is_validated = 'فیلد '+field.title+' باید بین از '+params[0]+' و '+params[1]+'باشد'
                    }
                    break;
                case 'MIN_VALUE':
                    if (field.value === null || field.value < params[0]) {
                        is_validated = 'فیلد '+field.title+' باید حداقل برابر با '+params[0]+' باشد'
                    }
                    break;
                case 'MAX_VALUE':
                    if (field.value > params[0]) {
                        is_validated = 'فیلد '+field.title+' باید حداکثر برابر با '+params[0]+' باشد'
                    }
                    break;
                case 'DIGITS':
                    if (field.value === null || isNaN(field.value) || field.value.replace('-', '').length != params[0]) {
                        is_validated = 'فیلد '+field.title+' باید دقیقا '+params[0]+' عدد باشد'
                    }
                    break;
                case 'IN':
                    if (field.value === null || !params.includes(field.validator)) {
                        const values = params.join('، ')
                        is_validated = 'فیلد '+field.title+' باید یکی از گزینه‌های '+values+' باشد'
                    }
                    break;
                case 'IS_NUMBER':
                    const test = field.value+''
                    if (field.value === null || parseInt(test) != field.value) {
                        is_validated = 'فیلد '+field.title+' باید یک مقدار عددی (صحیح یا اعشاری) داشته باشد'
                    }
                    break;
                case 'IS_EMAIL':
                    if (field.value && !this.$helpers.isEmail(field.value)) {
                        is_validated = 'فیلد '+field.title+' باید یک ایمیل معتبر باشد'
                    }
                    break;
                case 'IS_MOBILE':
                    if (!this.$helpers.isMobile(field?.value)) {
                        is_validated = 'فیلد '+field.title+' باید یک تلفن همراه معتبر باشد'
                    }
                    break;
                case 'IS_MELLI':
                    console.log(this.$helpers.isValidNationalId(field.value))
                    if (field.value && !this.$helpers.isValidNationalId(field.value)) {
                        is_validated = 'فیلد '+field.title+' باید یک کد ملی معتبر باشد'
                    }
                    break;
            }
            return is_validated
        },
        async validateAll() {
            for (const [key, field] of Object.entries(this.fields)) {
                await this.checkValidation(field)
            }
            return !(this.errorArray != null && Object.keys(this.errorArray).length)
        },
        handleFileSelect(event, key) {
            const max_files = this.fields[key].max_rows ? (this.fields[key].max_rows > 99 ? 99 : this.fields[key].max_rows) : 1
            const min_size  = this.fields[key].min  && this.fields[key].min > 1 ? this.fields[key].min : 0
            const max_size  = this.fields[key].max ? (this.fields[key].max > Settings?.max_allowed_file_size ? Settings.max_allowed_file_size : this.fields[key].max) : -1
            this.fields[key].value = []
            const files = event.target.files
            let that = this
            let max_selected = 0
            for (let i = 0; i < files.length; i++) {
                if (max_selected < max_files) {
                    if (min_size > 0 && (files[i].size / 1024) < min_size) {
                        this.$helpers.notify('اخطار', 'حجم فایل انتخاب شده می‌بایست بزرگتر از '+this.$helpers.humanReadableFileSize(min_size)+' باشد', { type: 'error' })
                        continue
                    }
                    if (max_size > 0 && (files[i].size / 1024) > max_size) {
                        this.$helpers.notify('اخطار', 'حجم فایل انتخاب شده نباید بزرگتر از '+this.$helpers.humanReadableFileSize(max_size)+' باشد', { type: 'error' })
                        continue
                    }
                    let reader = new FileReader();
                    reader.readAsDataURL(files[i]);
                    const f = files[i]
                    reader.onload = () => {
                        f['imageUrl'] = reader.result
                        that.fields[key].value.push(f)
                    }
                    max_selected++
                } else {
                    this.$helpers.notify('اخطار', 'تعداد فایل‌های انتخاب شده بیش از حد مجاز است.', { type: 'error' })
                    break
                }
            }
            this.$emit('update:modelValue', this.fields)
        },
        deleteFile(key, index) {
            this.fields[key].value.splice(index, 1)
            if (!this.fields[key].value.length) {
                this.fields[key].value = null
            }
        },
        emitChanges() {
            this.$emit('update:modelValue', this.fields)
        },
        async forceUpdate() {
            this.renderComponent = false;
            await this.$nextTick();
            this.renderComponent = true;
        }    }
};
</script>

<style>
.radio input + span::after {
    font-size: 10px;

}
</style>
