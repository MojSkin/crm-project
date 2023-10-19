<template>
    <div class="tabs-wrapper">
        <div class="tabs-inner">
            <div class="tabs">
                <ul>
                    <li :class="{ 'is-active': active===header.id }" v-for="header in headers">
                        <slot name="header" :item="header">
                            <a @click="active = header.id"><span>{{ header.text }}</span></a>
                        </slot>
                    </li>
                    <li class="tab-naver"></li>
                </ul>
            </div>
        </div>

        <div class="panel-body is-relative">
            <div v-for="tab in headers" class="tab-content" :class="{ 'is-active': active===tab.id }">
                <slot :name="tab.id"></slot>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "BTabs",
    props: {
        modelValue: {
            default: null,
        },
        headers: Array
    },
    data() {
        return {
            active: this.modelValue
        }
    },
    watch: {
        modelValue() {
            this.active = this.modelValue
        },
        active() {
            this.$emit('update:modelValue', this.active)
        }
    }
};
</script>

<style scoped>

</style>
