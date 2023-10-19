<template>
    <div class="field py-0 my-0">
        <slot name="label">
            <label class="w-100 is-flex" :for="$attrs.id || $attrs.name">
                <slot name="label-text">
                    <span v-if="label" v-html="label"></span>
                </slot>
                <span class="ml-2 hint--light hint--rounded hint--top" :data-hint="hint" v-if="hint"><i class="fas fa-question-circle fa-flip-horizontal"></i></span>
            </label>
        </slot>
    </div>
    <div class="field" :class="{ 'has-addons': isExpanded }">
        <div class="control" v-if="isExpanded && $slots['expanded-start']">
            <slot name="expanded-start" is="div" class="control" v-if="isExpanded"></slot>
        </div>
        <div class="control" :class="{ 'is-expanded': isExpanded, 'has-error': isError, 'has-success': isSuccess, 'has-icon': hasIcon, 'has-validation': isSuccess || isError}">
            <slot name="input">
                <input class="input" @change="inputChanged" v-model="vModel" v-bind="$attrs" :key="key">
            </slot>
            <slot name="icon-wrapper">
                <div class="form-icon" v-if="hasIcon">
                    <slot name="icon">
                        <i :class="inputIcon"></i>
                    </slot>
                </div>
            </slot>
            <div class="validation-icon is-error" v-if="isError">
                <i class="fal fa-times has-text-danger"></i>
            </div>
            <div class="validation-icon is-success" v-if="isSuccess">
                <i class="fal fa-check has-text-success"></i>
            </div>
        </div>
        <div class="control" v-if="isExpanded && $slots['expanded-end']">
            <slot name="expanded-end"></slot>
        </div>
    </div>
    <div class="field py-0 my-0">
        <div class="help has-text-danger" v-if="errorMessage?.length" v-html="errorMessage || ''"/>
        <p class="help">
            <slot name="description">
                <span v-html="description" v-if="description"></span>
            </slot>
        </p>
    </div>
</template>

<script>

export default {
    name: "B-Input",
    inheritAttrs: false,
    props: {
        modelValue: {
            default: null
        },
        hasIcon: Boolean,
        inputIcon: [Boolean, String],
        label: [Boolean, String],
        hint: [Boolean, String],
        description: [Boolean, String],
        isSuccess: Boolean,
        isError: Boolean,
        key: [String],
        errorMessage: [String],
        isExpanded: [Boolean]
    },
    data() {
        return {
            vModel: this.modelValue
        }
    },
    watch: {
        modelValue() {
            this.vModel = this.modelValue
        }
    },
    methods: {
        inputChanged() {
            this.$emit('update:modelValue', this.vModel)
        },
    }
};
</script>

<style>
.field.has-addons .control .input:not([disabled]):focus:hover,
.field.has-addons .control .input:not([disabled]):focus,
.field.has-addons .control .input:not([disabled]):hover,
input:hover,
input:focus,
input:focus:hover,
textarea:hover,
textarea:focus,
textarea:focus:hover {
    z-index: 0 !important;
    background-color: rgba(250,250,250,0.7)
}
</style>
