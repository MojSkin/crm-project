<template>
    <div class="field">
        <div class="control" :class="{ 'is-error': isError}">
            <label
                class="radio"
                :class="['is-'+variant, { 'is-outlined': !solid, 'is-circle': rounded, 'is-solid': solid, 'w-100': stack }]"
                v-for="opt in opts"
            >
                <input :name="$attrs.name" type="radio" :checked="select == opt.value || false" @click="selectThis(opt)">
                <span></span>
                {{ opt.text }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    name: "B-Radio",
    inheritAttrs: false,
    props: {
        modelValue: {
            default: null
        },
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
            select: this.modelValue,
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
    methods: {
        selectThis(opt) {
            this.select = opt.checked
            this.$emit('update:modelValue', this.select)
        },
    }
};
</script>

<style scoped>
.radio input + span::after {
    content: "\f111";
    font-family: "Font Awesome 5 Pro";
    font-weight: 900;
}
.control.is-error {
    border: 1px dashed #e62965 !important;
    border-radius: 5px;
}
</style>
