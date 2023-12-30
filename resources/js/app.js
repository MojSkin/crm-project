import './bootstrap';

import { createApp } from 'vue';
import { createStore } from 'vuex'

import mitt from 'mitt'
import Swal from 'sweetalert2'
import VuePersianDatetimePicker from 'vue3-persian-datetime-picker'
import vue3StarRatings from "vue3-star-ratings";
import Vue3ColorPicker from "vue3-colorpicker";
import VueEasyLightbox from 'vue-easy-lightbox'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import Flicking from "@egjs/vue3-flicking";
import OpenLayersMap from "vue3-openlayers";
import Toast from "vue-toastification";

import Helpers from './helpers'
window.Helpers = Helpers
import Requests from './requests'
window.Requests = Requests
import jquery from 'jquery'
window.$ = window.jQuery = jquery;

import router from './router'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import VueTippy, { useTippy } from "vue-tippy";
import App from "./components/App.vue";
import Page404 from "./components/pages/404.vue";

const app = createApp(App, {
    // beforeCreate() {
    //     this.$store.commit('initStore')
    //     const crmState = JSON.parse(localStorage?.crmState)
    //     if (typeof crmState?.userData === undefined || typeof crmState?.userData?.token !== 'string' || crmState?.userData?.token?.length < 30) {
    //         router.push('/login')
    //     }
    // },
});
// VueX Configuration
const store = createStore({
    state () {
        return {
            userData: null,
            basePath: import.meta.env.VITE_APP_URL,
            logo: import.meta.env.VITE_LOGO
        }
    },
    mutations: {
        setUserData(state, data = null) {
            state.userData = data
        },

        setStateData(state, data) {
            if (data?.key !== undefined && data?.key !== null) {
                state[data.key] = typeof data?.value === undefined ? null : data?.value
            }
        },

        initStore(state) {
            if (localStorage.getItem('crmState')) {
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('crmState')))
                );
            }
        }
    }
})

store.subscribe((mutation, state) => {
    localStorage.setItem('crmState', JSON.stringify(state));
});
store.commit('initStore')
app.component('Page404', Page404)

// =======================================================================
// Custom templates
// =======================================================================
import BlankLayoutComponent from "./components/base/Layouts/BlankLayout/BlankLayoutComponent.vue";
import MainLayoutComponent from "./components/base/Layouts/MainLayout/MainLayoutComponent.vue";
import AdminLayoutComponent from "./components/base/Layouts/AdminLayout/AdminLayoutComponent.vue";
import UserLayoutComponent from "./components/base/Layouts/UserLayout/UserLayoutComponent.vue";
import FormContentComponent from "./components/global/Form/FormContentComponent.vue";
app.component('BlankLayoutComponent', BlankLayoutComponent)
app.component('MainLayoutComponent', MainLayoutComponent)
app.component('AdminLayoutComponent', AdminLayoutComponent)
app.component('UserLayoutComponent', UserLayoutComponent)
app.component('FormContentComponent', FormContentComponent)

// =======================================================================
// Custom components based on HURO Theme
// =======================================================================
import Dropzone         from './components/global/Dropzone/Dropzone.vue';
import BModal           from './components/global/Modal/B-Modal.vue';
import Accordion        from './components/global/Accordion/BAccordion.vue';

import Form             from './components/global/Form/FormComponent.vue'
import BOverlay         from './components/global/Overlay/BOverlay.vue';
import BTabs            from './components/global/Tabs/BTabs.vue';
import BAction          from './components/global/Action/B-Action.vue'
import BCard            from './components/global/Card/BCard.vue'
import BTable           from './components/global/Table/BTable.vue'
import BDraggableTable  from './components/global/Table/BDraggableTable.vue'
import BDropdown        from './components/global/Dropdown/BDropdown.vue'
import BInput           from './components/global/Input/B-Input.vue'
import BSelect          from './components/global/Input/B-Select.vue'
import BSwitch          from './components/global/Input/B-Switch.vue'
import BCheckbox        from './components/global/Input/B-Checkbox.vue'
import BRadio           from './components/global/Input/B-Radio.vue'
import BIconPicker      from './components/global/IconPicker/B-IconPicker.vue'

app.component('b-dropzone', Dropzone)
app.component('b-modal', BModal)
app.component('b-accordion', Accordion)

app.component('form-builder', Form)
app.component('b-overlay', BOverlay)
app.component('b-tabs', BTabs)
app.component('b-action', BAction)
app.component('b-card', BCard)
app.component('b-table', BTable)
app.component('b-draggable-table', BDraggableTable)
app.component('b-dropdown', BDropdown)
app.component('b-input', BInput)
app.component('b-select', BSelect)
app.component('b-switch', BSwitch)
app.component('b-checkbox', BCheckbox)
app.component('b-radio', BRadio)
app.component('b-icon-picker', BIconPicker)

// =======================================================================
// Packaged components
// =======================================================================
app.component("star-ratings", vue3StarRatings);
app.component('Carousel', Carousel);
app.component('Slide', Slide);
app.component('Pagination', Pagination);
app.component('Navigation', Navigation);
app.component('Flicking', Flicking);

app.config.globalProperties.EventBus = mitt();
app.config.globalProperties.$swal = Swal;
app.config.globalProperties.$helpers = Helpers;
app.config.globalProperties.$settings = window.Settings;

app
    .use(router)
    .use(store)
    .use(VueEasyLightbox)
    .use(Vue3ColorPicker)
    .use(OpenLayersMap /* options */)
    .use(
        VueTippy,
        {
            directive: 'tooltip', // => v-tooltip
            component: 'tooltip', // => <tooltip/>
        }
    ).use(VuePersianDatetimePicker, {
        name: 'date-picker',
        props: {
            editable: false,
            inputClass: 'input',
            placeholder: 'انتخاب تاریخ',
            color: '#671cc9',
            autoSubmit: true,
        }
    })
    .use(Toast, {})
    .provide('base_url', window.location.origin)
    .provide('neshanWeb_api_key', 'web.6634a312455947c4b24280fa05e39143')
    .provide('neshanSrv_api_key', 'service.7649ecbd7fd6425a9da2013265bc395c')
    .provide('app_key', import.meta.VITE_APP_KEY)
    .mount("#app")
