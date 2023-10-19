<template>
    <div id="huro-app" class="app-wrapper">
        <div class="app-overlay"></div>
        <nav class="navbar mobile-navbar is-hidden-desktop" aria-label="main navigation">
            <div class="container">
                <!-- Brand -->
                <div class="navbar-brand">
                    <!-- Mobile menu toggler icon -->
                    <div class="brand-start">
                        <div class="navbar-burger" :class="{ 'is-active': mobileNavbarStatus }" @click="mobileNavbarStatus = !mobileNavbarStatus">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <RouterLink class="navbar-item is-brand" to="/">
                        <img :src="$store.state.logo" alt="تبیان">
                    </RouterLink>

                    <div class="brand-end is-flex align-items-center">
                        <RouterLink class="button is-info is-outlined mr-2 my-0" to="/register">پیش ثبت‌نام</RouterLink>
                        <div class="mr-6 dropdown is-right is-spaced dropdown-trigger user-dropdown" v-if="loggedIn">
                            <div class="dropdown profile-dropdown dropdown-trigger is-spaced is-right" :class="{ 'is-active': showProfileDropdown }" @click="showProfileDropdown = !showProfileDropdown">
                                <div class="h-avatar is-small is-clickable">
                                    <img class="avatar" :src="base_url+'/storage/uploads/user_avatars/'+userData.avatar" v-if="userData.avatar" alt="">
                                    <span class="avatar is-fake" v-else>{{ $helpers.makeAbbr(userData.dName) }}</span>
                                </div>
                                <AdminProfileDropdown class="mr-4"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!--Webapp navbar regular-->
        <div class="webapp-navbar">
            <div class="webapp-navbar-inner">
                <div class="left">
                    <RouterLink class="brand" to="/">
                        <img :src="$store.state.logo" alt="تبیان">
                    </RouterLink>
                </div>
                <div class="center">
                    <div class="centered-links">
                        <RouterLink to="/" class="centered-link">
                            <i class="fal fa-home-alt fa-2x is-primary"></i>
                            <span>صفحه نخست</span>
                        </RouterLink>
                        <RouterLink to="/introduce" id="layouts-navbar-menu" class="centered-link centered-link-toggle">
                            <i class="fal fa-comment-alt-dots fa-2x is-primary"></i>
                            <span>معرفی مجتمع</span>
                        </RouterLink>
                        <RouterLink to="/achievements" id="elements-navbar-menu" class="centered-link centered-link-toggle">
                            <i class="fal fa-bullseye-arrow fa-2x is-primary"></i>
                            <span>موفقیت‌ها</span>
                        </RouterLink>
                        <RouterLink to="/contact-us" id="components-navbar-menu" class="centered-link centered-link-toggle">
                            <i class="fal fa-envelope-open fa-2x is-primary"></i>
                            <span>ارتباط با ما</span>
                        </RouterLink>
                    </div>
                </div>
                <div class="right is-flex is-align-items-center">
                    <RouterLink class="button is-info is-outlined mr-2" to="/register">پیش ثبت‌نام</RouterLink>
                    <div v-if="loggedIn">
                        <div class="dropdown profile-dropdown dropdown-trigger is-spaced is-right" :class="{ 'is-active': showProfileDropdown }" @click="showProfileDropdown = !showProfileDropdown">
                            <div class="h-avatar is-clickable">
                                <img class="avatar" :src="base_url+'/storage/uploads/user_avatars/'+userData.avatar" v-if="userData.avatar" alt="">
                                <span class="avatar is-fake" v-else>{{ $helpers.makeAbbr(userData.dName) }}</span>
                            </div>
                            <AdminProfileDropdown/>
                        </div>
                    </div>
                    <div v-else>
                        <div class="is-inline-block mr-4">
                            <b-action direction="right">
                                <template #icon>
                                    <i class="fas fa-user-circle has-text-info" style="font-size:2rem"></i>
                                </template>
                                <RouterLink class="dropdown-item font-size-base" to="/register/tracking">پیگیری ثبت‌نام</RouterLink>
                                <hr class="dropdown-divider">
                                <RouterLink class="dropdown-item font-size-base" to="/login">ورود</RouterLink>
                            </b-action>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-subsidebar py-5 is-hidden-desktop" :class="{ 'is-active': mobileNavbarStatus }">
            <div class="inner">
                <ul class="submenu">
                    <li class="py-3">
                        <RouterLink to="/" class="button is-rounded is-outlined is-primary border-0 has-text-left px-2">
                            <i class="fal fa-home-alt mr-2"></i>
                            <span>صفحه نخست</span>
                        </RouterLink>
                    </li>
                    <li class="py-3">
                        <RouterLink to="/" class="button is-rounded is-outlined is-primary border-0 has-text-left px-2">
                            <i class="fal fa-home-alt mr-2"></i>
                            <span>معرفی مجتمع</span>
                        </RouterLink>
                    </li>
                    <li class="py-3">
                        <RouterLink to="/" class="button is-rounded is-outlined is-primary border-0 has-text-left px-2">
                            <i class="fal fa-bullseye-arrow mr-2"></i>
                            <span>موفقیت‌ها</span>
                        </RouterLink>
                    </li>
                    <li class="py-3">
                        <RouterLink to="/" class="button is-rounded is-outlined is-primary border-0 has-text-left px-2">
                            <i class="fal fa-envelope-open mr-2"></i>
                            <span>ارتباط با ما</span>
                        </RouterLink>
                    </li>
                    <li class="divider"></li>
                    <RouterLink to="/register/tracking" class="button w-100 is-outlined is-primary is-rounded mb-2">پیگیری ثبت‌نام</RouterLink>
                    <RouterLink to="/login" class="button w-100 is-info is-rounded mb-2"><span class="mx-4" v-if="!loggedIn">ورود</span></RouterLink>
                </ul>

            </div>
        </div>
        <!-- Content Wrapper -->
        <div class="view-wrapper is-webapp" data-naver-offset="214" data-menu-item="#layouts-navbar-menu" data-mobile-item="#home-sidebar-menu-mobile">
            <div :class="isFluid ? 'page-content-wrapper-fluid' : 'page-content-wrapper'">
                <div class="page-content is-relative">
                    <div class="page-title has-text-centered is-webapp">
                        <div class="title-wrap">
                            <slot name="page-title">
                                <h1 class="title is-4 mt-5" v-if="pageTitle">{{ pageTitle }}</h1>
                            </slot>
                        </div>
                    </div>
                    <div class="page-content-inner m-0">
                        <slot/>
                    </div>

                </div>
            </div>

        </div>
        <footer>
            <div class="container-xxl container-md container-fluid footer">
                <div class="columns is-multiline footer">
                    <div class="column is-4-desktop is-4-widescreen is-12 widget right" v-html="settings.widget_right"/>
                    <div class="column is-5-desktop is-5-widescreen is-12 widget center" v-html="settings.widget_center"/>
                    <div class="column is-3-desktop is-3-widescreen is-12 widget left has-text-centered-mobile has-text-centered-tablet" v-html="settings.widget_left"/>
                </div>
            </div>
            <div class="footer branding">
                <span>تمامی حقوق متعلق است به </span>
                <RouterLink to="/">مجتمع آموزشی، فرهنگی تبیان</RouterLink>
                <span class="mx-2 is-weight-700 is-6">|</span>
                <span>طراحی و پیاده‌سازی توسط </span>
                <a href="https://advb.ir">ADVB</a>
                <br>
                <div class="mt-3" style="font-size:1.5rem;">
                    <i class="fab fa-instagram mx-2 is-clickable"></i>
                    <i class="fab fa-google mx-2 is-clickable"></i>
                    <i class="fab fa-telegram mx-2 is-clickable"></i>
                    <i class="fab fa-facebook mx-2 is-clickable"></i>
                    <i class="fal fa-envelope mx-2 is-clickable"></i>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import AdminProfileDropdown from "@/components/base/Layouts/AdminLayout/_Partials/AdminProfileDropdown.vue";
const url = import.meta.env.VITE_APP_URL_FULL

export default {
    name: "MainLayoutComponent",
    components: {AdminProfileDropdown},
    props: {
        isFluid: Boolean,
        pageTitle: [Boolean, String]
    },
    data() {
        return {
            mobileNavbarStatus: false,
            showProfileDropdown: false,
            pageTitle: this.$router?.currentRoute?.value?.meta?.pageTitle || false,
            settings: {
                widget_right: Settings?.widget_right || '',
                widget_center: Settings?.widget_center || '',
                widget_left: Settings?.widget_left || '',
            },
            userData: this.$store.state.userData,
            base_url: window.location.origin,
        }
    },
    created() {
        const plugins = [
            // {src: url+'/assets/js/functions.js', async: false},
            // {src: url+'/assets/js/app.js', async: false},
            // {src: url+'/assets/js/main.js', async: true},
            // {src: url+'/assets/js/components.js', async: true},
            // {src: url+'/assets/js/popover.js', async: true},
            // {src: url+'/assets/js/widgets.js', async: true},
            // {src: url+'/assets/js/touch.js', async: true},
            // {src: url+'/assets/js/syntax.js', async: true},
        ]
        for (let i = 0; i < plugins.length; i++) {
            const plugin = document.createElement("script");
            plugin.setAttribute("src", plugins[i].src);
            plugin.async = plugins[i].false;
            document.body.appendChild(plugin);
        }
    },
    mounted() {
    },
    computed: {
        loggedIn() {
            const token = Requests.token()

            return !(typeof token !== 'string' || token < 30)
        }
    },
    watch: {},
    methods: {}
}
</script>

<style>
.mobile-subsidebar .inner {
    position: relative;
    height: 100%;
    width: 100%;
    margin-right: 0;
}
footer, .footer {
    background-color: #faae42;
}
.footer .widget h3 {
    font-size: 1.75rem;
    font-weight: 900;
    margin-bottom: 15px;
}
.widget p,
.widget span {
    text-align: justify;
    font-size: 1.25rem;
}
.widget.center {
    position: relative;
}
.widget.center::before,
.widget.center::after {
    position: absolute;
    content: '';
    display: inline-block;
    height: 75%;
    border-left: 2px solid rgb(190, 150, 80);
    top: 25%;
}
.widget.center::before {
    right: calc(var(--bs-gutter-x ,15px) / 2);
}
.widget.center::after {
    left: calc(var(--bs-gutter-x ,15px) / 2);
}
body.is-dark .footer.branding span,
body.is-dark .footer.branding i {
    color: #999
}
body.is-dark .footer.branding {
    background-color: #1e1e21;
    border-color: #252529;
}
.footer.branding {
    text-align: center;
    background: #f9f9f9;
    padding: 1.5rem 2rem;
    font-weight: 700;
}
@media (max-width: 1023px) {
    .widget.center {
        position: static;
    }
    .widget.center::before,
    .widget.center::after {
        position: unset;
        display: none;
    }
}
@media (max-width: 767px){
    .navbar-brand .brand-end {
        width: auto;
    }
    .view-wrapper {
        margin-top: 0 !important;
    }
}
.webapp-navbar .webapp-navbar-inner .left .brand img {
    display: block;
    min-width: 80px;
    height: 80px;
}

</style>
