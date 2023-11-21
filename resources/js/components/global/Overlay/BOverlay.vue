<template>
    <Transition appear mode="in-out" name="fade">
        <div v-if="show" :class="computedWrapperClass" :style="computedWrapperStyle" v-bind="$attrs" key="loadingOverlay">
            <div :class="{'loader-text': loaderText}">
                <i class="fad fa-spin fa-2x" :class="loaderClass"></i>
                <span v-if="loaderText" :class="loaderTextClass" v-html="typeof loaderText === 'string' ? loaderText : ''"/>
            </div>
        </div>
    </Transition>
    <slot></slot>
</template>

<script>
export default {
	name: "B-Overlay",
	props: {
		loaderType: {
			type: String,
			default: 'spinner', // spinner, dots, notch
		},
        loaderCustomClass: {
            type: [String, Boolean],
            default: 'is-light'
        },
		loaderText: {
			type: [String, Boolean],
			default: false
		},
		loaderTextClass: {
			type: [String, Boolean],
			default: false
		},
		bgVariantClass: {
			default: ''
		},
		bgVariantColor: {
			default: 'rgba(240, 240, 240, 0.70)'
		},
		fullscreen: {
			type: Boolean,
			default: false
		},
		show: {
			type: Boolean,
			default: false
		},
	},
	data() {
		return {
			computedWrapperClass: '',
			computedWrapperStyle: '',
			overlayPosition: this.fullscreen ? 'fixed' : 'absolute'
		}
	},
	mounted() {
		if (this.bgVariantClass) {
			this.computedWrapperClass = 'bg-'+this.bgVariantClass.toLowerCase()
		} else {
			if (this.bgVariantColor) {
				this.computedWrapperStyle = 'background-color: '+this.bgVariantColor
			}
		}
		this.computedWrapperClass += ' loadingOverlayWrapper'
	},
	computed: {
		loaderClass() {
			let loaderClass = []
			switch (this.loaderType.toLowerCase()) {
				case 'dots' : loaderClass = ['fa-spinner']; break;
				case 'notch': loaderClass = ['fa-circle-notch']; break;
				default     : loaderClass = ['fa-spinner-third']
			}
            if (this.loaderCustomClass !== false) {
                loaderClass.push(...this.loaderCustomClass.split(' '))
            }
			return loaderClass.join(' ')
		},
	},
	methods: {},
}
</script>

<style>
.loadingOverlayWrapper {
	position: v-bind('overlayPosition');
	inset: 0;
	z-index: 999;
	display: flex;
	justify-content: center;
	align-items: center;
	backdrop-filter: blur(3.5px);
	-webkit-backdrop-filter: blur(3.5px);
	box-shadow: inset rgba(255, 255, 255, 0.25) 0 0 45px;
}

.loader-text {
    display: flex;
    align-items: center;
	font-size: 13px;
	padding: 8px 15px;
	background-color: #fff;
	border-radius: 30px;
	width: auto;
	margin: 0 auto;
	box-shadow: 1px 1px 20px 11px rgba(0, 0, 0, 0.04);
	-webkit-transition: all 2s;
	-moz-transition: all 2s;
}

.loader-text span {
	margin: 0 10px;
	transition-timing-function: ease-in-out;
	animation-name: fade;
	animation-fill-mode: both;
	animation-iteration-count: infinite;
	animation-duration: 1500ms;
	animation-direction: reverse;
}

.fade-enter-active,
.fade-leave-active {
    transition: all 120ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    backdrop-filter: blur(3.5px);
}
</style>
