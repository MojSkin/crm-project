<template>
    <div class="field">
        <div class="control" :class="{ 'is-error': isError}">
            <label
                class="checkbox"
                :class="['is-'+variant, { 'is-outlined': !solid, 'is-circle': rounded, 'is-solid': solid, 'w-100': stack }]"
                v-for="opt in opts"
            >
                <input :name="$attrs.name" type="checkbox" :checked="selects?.includes(opt.value) || false" @click="selectThis(opt)">
                <span></span>
                {{ opt.text }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    name: "B-Checkbox",
    inheritAttrs: false,
    props: {
        modelValue: [String, Number, Array],
        label: [Boolean, String],
        hint: [Boolean, String],
        description: [Boolean, String],
        isError: [Boolean],
        errorMessage: [Boolean, String],
        options: Array,
        rounded: [Boolean],
        solid: [Boolean],
        variant: String,
        key: [String],
        stack: [Boolean]
    },
    data() {
        return {
            opts: [],
            selects: typeof this.modelValue === 'object' ? this.modelValue : (this.modelValue ? [this.modelValue] : [])
        }
    },
    created() {
        this.opts = []
        for (let i = 0; i < this.options.length; i++) {
            let op = []
            if (typeof this.options[i] === 'object') {
                op = this.options[i]
            } else {
                op = {
                    value: this.options[i],
                    text: this.options[i],
                    checked: this.options[i],
                    unchecked: null,
                }
            }
            this.opts.push(op)
        }
    },
    watch: {
        modelValue() {
            this.selects = typeof this.modelValue === 'object' ? this.modelValue : (this.modelValue ? [this.modelValue] : [])
        }
    },
    methods: {
        selectThis(opt) {
            if (this.selects === null) {
                this.selects = []
            }
            if (this.selects.includes(opt.value)) {
                for (let i = 0; i < this.selects.length; i++) {
                    if (this.selects[i] === opt.value) {
                        this.selects.splice(i, 1)
                        break
                    }
                }
            } else {
                this.selects.push(opt.value)
            }
            if (!this.selects.length) {
                this.$emit('update:modelValue', null)
            } else if (this.selects.length === 1) {
                this.$emit('update:modelValue', this.selects[0])
            } else {
                this.$emit('update:modelValue', this.selects)
            }
        },
    }
};
</script>

<style scoped>
.control.is-error {
    border: 1px dashed #e62965 !important;
    border-radius: 5px;
}
</style>
<style>
.checkbox input + span::after {
    content: "\f00c";
    font-family: "Font Awesome 5 Pro";
    font-weight: 400;
}
</style>
