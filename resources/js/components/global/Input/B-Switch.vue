<template>
    <div class="field">
        <div class="control">
            <div class="switch-block" v-if="!isThin">
                <label class="form-switch pr-0">
                    <input type="checkbox" class="is-switch" :class="'is-'+variant" v-bind="$attrs" v-model="vModel" @change="inputChanged">
                    <i></i>
                </label>
                <div class="text w-100">
                    <slot>
                        <div class="" v-html="label" v-if="label != false"/>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "B-Switch",
    inheritAttrs: false,
    props: {
        modelValue: {
            default: null
        },
        isThin: Boolean,
        label: [Boolean, String],
        key: [String],
        variant: {
            Type: 'String',
            default: 'default'
        }
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
        }
    }
};
</script>

<style scoped>
</style>
