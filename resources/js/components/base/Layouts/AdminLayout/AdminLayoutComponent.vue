<template>
    <!--Mobile navbar-->
    <nav class="navbar mobile-navbar no-shadow is-hidden-desktop is-hidden-tablet" aria-label="main navigation">
        <div class="container">
            <!-- Brand -->
            <div class="navbar-brand">
                <!-- Mobile menu toggler icon -->
                <div class="brand-start">
                    <div class="navbar-burger" :class="{ 'is-active': activeSidebar }" @click="activeSidebar = !activeSidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <a class="navbar-item is-brand" href="#">
                    <img class="light-image" :src="$store.state.logo" alt="">
                    <img class="dark-image" :src="$store.state.logo" alt="">
                </a>

                <div class="brand-end">
                    <div class="navbar-item is-notification is-hidden-tablet is-hidden-desktop" :class="{ 'is-active': showNotifications }" @click="showNotifications = !showNotifications">
                        <a class="navbar-link is-arrowless">
                            <i class="fal fa-bell"></i>
<!--                            <span class="new-indicator pulsate"></span>-->
                        </a>
                        <div class="navbar-dropdown is-boxed is-right">
                            <div class="heading">
                                <div class="heading-left">
                                    <h6 class="heading-title">اعلان ها</h6>
                                </div>
                                <div class="heading-right">
                                    <a class="notification-link" href="#">مشاهده همه</a>
                                </div>
                            </div>
                            <div class="inner has-slimscroll">

                                <ul class="notification-list">
                                    <li>
                                        <a class="notification-item">
                                            <div class="user-content py-6 my-4 w-100 has-text-centered">
                                                <i class="fal fa-bell-slash has-text-danger fa-2x mb-2"></i>
                                                <p class="user-info">اعلان‌ها خالی است</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown is-right is-spaced dropdown-trigger user-dropdown" :class="{ 'is-active': showProfileMenu }">
                        <div class="is-trigger" aria-haspopup="true">
                            <div class="profile-avatar" @click="showProfileMenu = !showProfileMenu">
                                <img class="avatar" :src="userData.avatar" v-if="userData.avatar" alt="">
                                <span class="avatar is-fake" v-else>{{ $helpers.makeAbbr(userData.dName) }}</span>
                            </div>
                        </div>
                        <AdminProfileDropdown/>
                    </div>
                </div>

            </div>
        </div>
    </nav>
    <!--Mobile sidebar-->
    <div class="mobile-main-sidebar" :class="{ 'is-active': activeSidebar }">
        <div class="inner">
            <AdminAsideIconMenu class="icon-side-menu"/>
        </div>
    </div>
    <!--Circular menu-->
    <div id="circular-menu" class="circular-menu">

        <a class="floating-btn" onclick="document.getElementById('circular-menu').classList.toggle('active');">
            <i aria-hidden="true" class="fas fa-bars"></i>
            <i aria-hidden="true" class="fas fa-times"></i>
        </a>

        <div class="items-wrapper">
            <div class="menu-item is-flex">
                <label class="dark-mode">
                    <input type="checkbox" v-model="darkMode" checked>
                    <span></span>
                </label>
            </div>
            <a class="menu-item is-flex">
                <i data-feather="bell"></i>
            </a>
            <a class="menu-item is-flex">
                <i data-feather="grid"></i>
            </a>
        </div>

    </div>
    <!--Sidebar labels-->
    <div class="main-sidebar has-labels">
        <div class="sidebar-brand">
            <RouterLink :to="{ name: 'admin.dashboard' }">
                <img class="light-image" :src="$store.state.logo" alt="">
                <img class="dark-image" :src="$store.state.logo" alt="">
            </RouterLink>
        </div>
        <div class="sidebar-inner">
            <AdminAsideIconMenu :hide-content="false" :hide-profile="false" class="icon-menu"/>
        </div>
    </div>
    <AdminActivityPanel v-model="showActions" key="adminActivityPanel"/>
    <AdminSearchPanel/>
    <!--Page body-->

    <div id="layouts-sidebar" class="sidebar-panel is-generic" :class="{ 'is-active': activeSidebar }">
        <div class="inner" data-simplebar>
            <AdminAsideMenuItems/>
        </div>
    </div>
    <div class="mobile-subsidebar" :class="{ 'is-active': activeSidebar }">
        <div class="inner">
            <AdminAsideMenuItems sub-menu-class="submenu"/>
        </div>
    </div>
    <!-- Content Wrapper -->
    <div class="view-wrapper" :class="{ 'is-pushed-full': activeSidebar }">
        <CheckUserStatusComponent/>
        <div class="page-content-wrapper">
            <div class="page-content is-relative">

                <div class="page-title has-text-centered">
                    <!-- Sidebar Trigger -->
                    <div class="huro-hamburger nav-trigger push-resize" data-sidebar="layouts-sidebar">
                        <span class="menu-toggle has-chevron">
                          <span class="icon-box-toggle" :class="{ active: activeSidebar}" @click="activeSidebar = !activeSidebar">
                              <span class="rotate">
                                  <i class="icon-line-top"></i>
                                  <i class="icon-line-center"></i>
                                  <i class="icon-line-bottom"></i>
                              </span>
                        </span>
                        </span>
                    </div>

                    <div class="title-wrap">
                        <h1 class="title is-4">
                            <slot name="title"/>
                        </h1>
                    </div>

                    <div class="toolbar ml-auto">
                        <div class="toolbar-link">
                            <label class="dark-mode ml-auto">
                                <input type="checkbox" v-model="darkMode" checked>
                                <span></span>
                            </label>
                        </div>

                        <div class="toolbar-notifications is-hidden-mobile">
                            <div class="dropdown is-spaced is-dots is-right dropdown-trigger" :class="{ 'is-active': showNotifications }" @click="showNotifications = !showNotifications">
                                <div class="is-trigger is-dots" aria-haspopup="true">
                                    <i class="fal fa-bell"></i>
<!--                                    <span class="new-indicator pulsate"></span>-->
                                </div>
                                <div class="dropdown-menu" role="menu">
                                    <div class="dropdown-content">
                                        <div class="heading">
                                            <div class="heading-left">
                                                <h6 class="heading-title">اعلان ها</h6>
                                            </div>
                                            <div class="heading-right">
                                                <a class="notification-link" href="#">مشاهده همه</a>
                                            </div>
                                        </div>
                                        <ul class="notification-list">
                                            <li>
                                                <a class="notification-item">
                                                    <div class="user-content py-6 my-4 w-100 has-text-centered">
                                                        <i class="fal fa-bell-slash has-text-danger fa-2x mb-2"></i>
                                                        <p class="user-info">اعلان‌ها خالی است</p>
                                                    </div>
                                                </a>
                                            </li>
<!--                                            <li>-->
<!--                                                <a class="notification-item">-->
<!--                                                    <div class="img-left">-->
<!--                                                        <img class="user-photo" alt="" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" />-->
<!--                                                    </div>-->
<!--                                                    <div class="user-content">-->
<!--                                                        <p class="user-info"><span class="name">آلیس</span> دیدگاهی گذاشت.</p>-->
<!--                                                        <p class="time">1 ساعت پیش</p>-->
<!--                                                    </div>-->
<!--                                                </a>-->
<!--                                            </li>-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        Actions icon section-->
<!--                        <a class="toolbar-link right-panel-trigger" @click="showActions = !showActions">-->
<!--                            <i class="fal fa-window-alt"></i>-->
<!--                        </a>-->
                    </div>
                </div>

                <div class="page-content-inner">
                    <slot/>
                </div>

            </div>
        </div>

    </div>
</template>

<script>

import AdminAsideMenuItems from "./_Partials/AdminAsideMenuItems.vue";
import AdminProfileDropdown from "./_Partials/AdminProfileDropdown.vue";
import AdminActivityPanel from "./_Partials/AdminActivityPanel.vue";
import AdminSearchPanel from "./_Partials/AdminSearchPanel.vue";
import AdminAsideIconMenu from "./_Partials/AdminAsideIconMenu.vue";
import CheckUserStatusComponent from "@/components/base/Layouts/AdminLayout/_Partials/CheckUserStatusComponent.vue";
const url = import.meta.env.VITE_APP_URL_FULL
export default {
    name: "AdminLayoutComponent",
    components: {CheckUserStatusComponent, AdminAsideIconMenu, AdminSearchPanel, AdminActivityPanel, AdminProfileDropdown, AdminAsideMenuItems },
    data() {
        return {
            darkMode: true,
            activeSidebar: false,
            showNotifications: false,
            showActions: false,
            showProfileMenu: false,
            userData: this.$store.state.userData,
            base_url: window.location.origin,
        }
    },
    beforeCreate() {
        this.$store.commit('initStore')
        const crmState = JSON.parse(localStorage?.crmState)
        if (typeof crmState?.userData === undefined || typeof crmState?.userData?.token !== 'string' || crmState?.userData?.token?.length < 30) {
            this.$router.push('/login')
        }
    },
    watch: {
        darkMode() {
            const body = document.body
            if (body.classList.contains('is-dark')) {
                body.classList.remove('is-dark')
            } else {
                body.classList.add('is-dark')
            }
        }
    },
    created() {
    }
};
</script>

<style scoped>
.main-sidebar .sidebar-brand img {
    margin-top: 5px;
    width: 65px;
    height: auto;
}
.mobile-navbar .navbar-brand .is-brand img {
    position: relative;
    height: 65px !important;
    max-height: 65px !important;
}
.mobile-subsidebar .inner .submenu {
    margin-right: 50px;
}

</style>
