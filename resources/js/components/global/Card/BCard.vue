<template>
    <div v-bind="$attrs" :class="['mb-5', {'overflow-hidden': noOverflow}, cSize]">
        <div class="card-head" :class="headerClass" v-if="!noHeader">
            <slot name="header">
                <div class="right">
                    <slot name="header-right"/>
                </div>
                <div class="left">
                    <slot name="header-left"/>
                </div>
            </slot>
        </div>
        <div class="card-body" :class="bodyClass">
            <slot/>
        </div>
        <div class="card-foot" :class="footerClass" v-if="!noFooter">
            <slot name="footer">
                <div class="right">
                    <slot name="footer-right"/>
                </div>
                <div class="left">
                    <slot name="footer-left"/>
                </div>
            </slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "BCard",
    props: {
        noHeader: [Boolean],
        noFooter: [Boolean],
        cardSize: {
            type: String,
            default: 'r',
            validator(value) {
                return ['s', 'r', 'l'].includes(value)
            }
        },
        headerClass: [String, Object],
        footerClass: [String, Object],
        bodyClass: [String, Object],
        key: [String],
        noOverflow: [Boolean]
    },
    data() {
        return {
            cSize: this.cardSize.toLowerCase()+'-card-advanced'
        }
    }
};
</script>

<style scoped>

</style>
