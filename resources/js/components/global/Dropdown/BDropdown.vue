<template>
    <div class="dropdown is-modern is-spaced dropdown-trigger" :class="{ 'is-right': isRight }">
        <div class="is-trigger" aria-haspopup="true" aria-controls="dropdown-menu" @click="showDropdown=!showDropdown">
            <button class="button bg-transparent" aria-haspopup="true" aria-controls="dropdown-menu" :class="{'border-0': noBorder}" ref="dropdown-btn">
                <slot name="placeholder">{{ options[0]?.title || '' }}</slot>
                <span class="caret" v-if="!noCaret">
                    <i class="fa fa-chevron-down"></i>
                </span>
            </button>
        </div>
        <div class="dropdown-menu" :class="{'is-active': showDropdown}" role="menu">
            <div class="dropdown-content scroll-menu overflow-scroll" style="max-height: 255px">
                <slot name="options" :items="options">
                    <div v-for="(opt, key) in options" @click="submit(opt, key)">
                        <slot name="option" :item="opt">
                            <hr class="dropdown-divider" v-if="opt?.divider === true">
                            <div class="dropdown-item is-media is-clickable" v-else>
                                <div class="icon" v-if="opt.icon">
                                    <slot name="row.icon">
                                        <i :class="opt?.icon"></i>
                                    </slot>
                                </div>
                                <div class="meta">
                                    <slot name="row.label">
                                        <span v-html="typeof opt === 'string' ? opt : opt?.title"/>
                                        <span v-html="opt?.description"/>
                                    </slot>
                                </div>
                            </div>
                        </slot>
                    </div>
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "BDropdown",
    props: {
        options: {
            type: Array,
            default: []
        },
        key: [String],
        noCaret: {
            type: Boolean,
            default: false
        },
        noBorder: {
            type: Boolean,
            default: false
        },
        isRight: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            showDropdown: false
        }
    },
    created() {
    },
    mounted() {
    },
    computed: {},
    watch: {
        showDropdown() {
            if (!this.showDropdown) {
                this.$refs['dropdown-btn'].blur()
            }
        }
    },
    methods: {
        submit(option, key) {
            this.showDropdown = false
            let result = {
                type: key,
                ...option
            }
            this.$emit('change', result)
            this.$emit('update:modelValue', result)
        },
        close() {
            this.showDropdown = false
        }
    }
}
</script>

<style scoped>
.dropdown-menu {
    padding-bottom: 0;
}
.dropdown-menu.is-active {
    display: block;
}
.dropdown.is-spaced .dropdown-menu .dropdown-content {
    border: unset;
    padding: 0;
}
.dropdown-item {
    min-height: 42px !important;
}
.dropdown.is-spaced .dropdown-menu {
    min-width: 240px !important;
}
</style>
