<template>
    <div class="dropdown is-dots dropdown-trigger" :class="['is-'+direction, { 'is-active': isActive }]" @click="handleActive()">
        <div class="is-trigger" aria-haspopup="true">
            <slot name="icon">
                <i class="fal fa-ellipsis-v"></i>
            </slot>
        </div>
        <div class="dropdown-menu" role="menu">
            <div class="dropdown-content">
                <slot>
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "B-Action",
    props: {
        direction: {
            type: String,
            validator(value) {
                return ['up', 'down', 'left', 'right'].includes(value)
            },
            default: 'up',
            key: [String],
        }
    },
    data() {
        return {
            isActive: false,
        }
    },
    created() {
        this.EventBus.on('action-is-activated', (id) => {
            const t = this._
            if (t.uid !== id) {
                this.isActive = false
            }
        })
    },
    methods: {
        handleActive() {
            this.isActive = !this.isActive
            const t = this._
            this.EventBus.emit('action-is-activated', t.uid)
        },

        close() {
            this.isActive = false
        }
    }
};
</script>

<style>
.dropdown.is-dots .dropdown-menu {
    position: absolute;
    z-index: 100;
    top: 0;
}
.dropdown.is-right .dropdown-menu {
    left: 32px;
}
</style>
