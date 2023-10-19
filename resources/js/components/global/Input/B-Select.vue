<template>
    <div class="field">
        <div class="control is-combo">
            <div class="combo-label">
                <slot name="label">
                    <span v-if="label" v-html="label"></span>
                </slot>
            </div>
            <div class="combo-box" :class="{ 'is-active': isActive, 'is-rounded': rounded, 'is-error': isError }">
                <div class="box-inner" @click.self.stop="isActive = !isActive">
                    <div class="combo-item" @click.self.stop="isActive = !isActive">
                        <slot name="selected">
                            <i :class="selectedOption?.icon" v-if="selectedOption?.icon"></i>
                            <span class="selected-item">{{ selectedOption || 'انتخاب کنید' }}</span>
                        </slot>
                    </div>
                </div>
                <div class="box-chevron mr-5" v-if="clearable && vModel" @click.prevent="clearSelect">
                    <i class="fal fa-times"></i>
                </div>
                <div class="box-chevron" @click.prevent="isActive = !isActive">
                    <i class="fal fa-chevron-down" :class="{ 'fa-rotate-180': isActive }"></i>
                </div>
                <div class="box-dropdown">
                    <div class="dropdown-inner has-slimscroll">
                        <div class="position-fixed w-100" style="padding: 0 0 0 1px;" v-if="searchable">
                            <input type="text" v-model="searchField" class="input px-6" @click.self.prevent>
                            <div class="position-absolute" style="top:10px; right: 15px">
                                <i class="fal fa-search"></i>
                            </div>
                            <div class="position-absolute" style="top:10px; left: 15px" v-if="searchField?.length">
                                <i class="fal fa-times is-clickable" @click="searchField=''"></i>
                            </div>
                        </div>
                        <ul :class="{ 'mt-6': searchable && computedOptions?.length }">
                            <li v-for="opt in computedOptions" :class="{ 'is-active': checkSelected(opt) }" @click.prevent="setSelected(opt)">
                                <span class="item-icon px-2" v-if="opt.icon">
                                    <i :class="opt.icon"></i>
                                </span>
                                <span class="item-name">{{ opt?.text || opt }}</span>
                                <span class="checkmark">
                                    <i class="fal fa-check"></i>
                                </span>
                            </li>
                        </ul>
                        <div class="has-text-centered mt-5 py-2" v-if="!computedOptions?.length">
                            <i class="fal fa-exclamation-triangle has-text-danger mr-2"></i>
                            <span>موردی یافت نشد</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "B-Select",
    inheritAttrs: false,
    props: {
        modelValue: {
            default: ''
        },
        label: [Boolean, String],
        hint: [Boolean, String],
        description: [Boolean, String],
        isError: [Boolean],
        errorMessage: [Boolean, String],
        options: Array,
        rounded: [Boolean],
        clearable: [Boolean],
        searchable: [Boolean],
        autoclose: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            vModel: this.modelValue || null,
            isActive: false,
            searchField: '',
        }
    },
    computed: {
        selectedOption() {
            for (let i = 0; i < this.options.length; i++) {
                let opt = typeof this.options[i]?.value !== 'undefined' ? this.options[i]?.value : this.options[i]
                if (this.vModel === opt) {
                    return this.options[i]?.text || opt
                }
            }
            return null
        },
        computedOptions() {
            let table = this.options

            if (this.searchField !== false && this?.searchField?.length > 0) {
                table = table.filter(item => {
                    let found = false
                    if (typeof item === 'object') {
                        for (const itemKey in item) {
                            const temp = typeof item[itemKey] === 'string' ? item[itemKey] : (item[itemKey] ? item[itemKey].toString() : '')
                            if (temp?.includes(this.searchField)) {
                                found = true
                            }
                        }
                    } else {
                        found = item?.includes(this.searchField) || false
                    }
                    return found
                })
            }

            return table

        }
    },
    watch: {
        modelValue() {
            this.vModel = this.modelValue || null
        },
        isActive(newValue) {
            if (newValue === true) {
                this.$emit('close')
            } else {
                this.$emit('open')
            }
        }
    },
    methods: {
        inputChanged() {
            this.$emit('update:modelValue', this.vModel)
        },
        checkSelected(opt) {
            return (typeof opt?.value !== 'undefined' ? opt?.value : opt) === this.vModel;
        },
        setSelected(opt) {
            this.vModel = typeof opt?.value !== 'undefined' ? opt?.value : opt
            this.$emit('update:modelValue', this.vModel)
            this.$emit('selected', this.vModel)
            if (this.autoclose) {
                this.isActive = false
            }
        },
        clearSelect() {
            this.vModel = null
            this.$emit('update:modelValue', this.vModel)
            this.$emit('selected', this.vModel)
            this.$emit('cleared')
        },
    }
};
</script>

<style scoped>
i.fal.fa-chevron-down {
    transition: all 300ms ease-in-out;
}
.combo-box.is-error {
    border-color: #e62965 !important;
}
</style>
