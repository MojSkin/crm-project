<template>
    <Teleport :to="appendTo">
        <Transition name="fade">
            <div class="modal is-active" v-if="showModal">
                <div class="modal-background" :class="'modal-background-'+modalBackdrop" @click="modalClose" v-if="showModal"></div>
                <div class="modal-content overflow-visible" v-if="showModal">
                    <div class="modal-card" v-if="isCard">
                        <header class="modal-card-head" v-if="!noCardHeader">
                            <slot name="header">
                                <h3 class="modal-card-title" v-html="headerContent"></h3>
                            </slot>
                            <button class="delete" @click="modalClose" v-if="closeButton"></button>
                        </header>
                        <section class="modal-card-body">
                            <slot name="default">
                            </slot>
                        </section>
                        <footer class="modal-card-foot" v-if="!noCardFooter">
                            <slot name="footer">
                                <div class="container">
                                    <div class="is-flex is-flex-direction-row is-justify-content-flex-end">
                                        <button class="button is-success" @click="$emit('success'); modalClose()">قبول</button>
                                        <button class="button" @click="$emit('success'); modalClose()">بستن</button>
                                    </div>
                                </div>
                            </slot>
                        </footer>
                    </div>
                    <slot name="default" v-if="!isCard">
                    </slot>
                </div>
                <button class="modal-close is-large" aria-label="close" @click="modalClose" v-if="closeButton && isCard"></button>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
export default {
    name: "B-Modal",
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        closeButton: {
            type: Boolean,
            default: true,
        },
        closable: {
            type: Boolean,
            default: true,
        },
        escapeControl: {
            type: Boolean,
            default: true,
        },
        isCard: {
            type: Boolean,
            default: true,
        },
        backdrop: {
            type: String,
            default: 'shade'  // values: SHADE  /  BLUR  /  NONE
        },
        appendTo: {
            type: String,
            default: 'body'
        },
        headerContent: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'medium'
        },
        noCardHeader: Boolean,
        noCardFooter: Boolean,
    },
    data() {
        return {
            showModal: this.show,
            modalBackdrop: [
                'shade',
                'blur',
                'none'].includes(this.backdrop.toLowerCase()) ? this.backdrop.toLowerCase() :  'shade',
            modalClass: 'is-'+([
                'small',
                'medium',
                'large',
                'big'].includes(this.size.toLowerCase()) ? this.size.toLowerCase() :  'medium')
        }
    },
    mounted() {
        // this.modalOpen()
    },
    computed: {

    },
    watch: {
        show() {
            if (this.show) {
                this.modalOpen()
            } else {
                this.modalClose()
            }
        }
    },
    methods: {
        modalOpen() {
            if (this.escapeControl) {
                window.addEventListener('keydown', this.checkEscapeKey)
            }
            this.showModal = true;
            this.$emit('open')
        },

        modalClose() {
            window.removeEventListener('keydown', this.checkEscapeKey);
            this.showModal = false;
            this.$emit('close')
        },

        checkEscapeKey(e) {
            if (e.keyCode === 27 && !this.closable) {
                this.modalClose()
            }
        },
    }
}
</script>

<style scoped>
button.delete {
    z-index: 999;
}
.modal-background-none.modal-background{
    background-color: rgba(255, 255, 255, 0) !important;
}

.modal-background-shade.modal-background {
    background-color: rgba(70, 70, 70, 0.45) !important;
}

.modal-background-blur.modal-background{
    background-color: rgba(250, 250, 250, 0.35) !important;
}

.modal-background-blur {
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

.modal-background-blur {
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

.fade-enter-active,
.fade-leave-active {
    transition: all 120ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    backdrop-filter: blur(5px);
}
.modal-content {
    overflow: hidden;
}
</style>
