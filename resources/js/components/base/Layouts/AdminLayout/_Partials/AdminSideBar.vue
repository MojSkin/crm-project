<template>
    <!--Sidebar labels-->
    <div class="main-sidebar has-labels">
        <div class="sidebar-brand">
            <a href="index-2.html">
                <img class="light-image" src="/resources/assets/img/logos/logo/logo.svg" alt="">
                <img class="dark-image" src="/resources/assets/img/logos/logo/logo-light.svg" alt="">
            </a>
        </div>
        <div class="sidebar-inner">

            <div class="naver"></div>

            <ul class="icon-menu">
                <!-- Activity -->
                <li>
                    <a href="admin-dashboards-personal-1.html" id="home-sidebar-menu" data-content="پیشخوان">
                        <i class="sidebar-svg" data-feather="activity"></i>
                    </a>
                </li> <!-- Layouts -->
                <li>
                    <a href="admin-grid-users-1.html" id="layouts-sidebar-menu" data-content="Layouts">
                        <i class="sidebar-svg" data-feather="grid"></i>
                    </a>
                </li> <!-- Bounties -->
                <li>
                    <a href="elements-hub.html" id="elements-sidebar-menu" data-content="Elements">
                        <i class="sidebar-svg" data-feather="box"></i>
                    </a>
                </li> <!-- Bugs -->
                <li>
                    <a href="components-hub.html" id="components-sidebar-menu" data-content="Components">
                        <i class="sidebar-svg" data-feather="cpu"></i>
                    </a>
                </li> <!-- Messaging -->
                <li id="messages-menu">
                    <a href="admin-messaging-chat.html" id="open-messages" data-content="Messaging">
                        <i class="sidebar-svg" data-feather="message-circle"></i>
                    </a>
                </li>
            </ul>

            <!-- User account -->
            <ul class="bottom-menu">
                <!-- Notifications -->
                <li class="right-panel-trigger" data-panel="search-panel">
                    <a href="javascript:void(0);" id="open-search" data-content="Search"><i class="sidebar-svg" data-feather="search"></i></a>
                    <a href="javascript:void(0);" id="close-search" class="is-hidden is-inactive"><i class="sidebar-svg" data-feather="x"></i></a>
                </li> <!-- Wallet -->
                <li>
                    <a href="admin-profile-settings.html" id="open-settings" data-content="Settings">
                        <i class="sidebar-svg" data-feather="settings"></i>
                    </a>
                </li> <!-- Profile -->
                <li id="user-menu">
                    <div id="profile-menu" class="dropdown profile-dropdown dropdown-trigger is-spaced is-up">
                        <img src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/8.jpg" alt="">
                        <span class="status-indicator"></span>

                        <div class="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                <div class="dropdown-head">
                                    <div class="h-avatar is-large">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/8.jpg" alt="">
                                    </div>
                                    <div class="meta">
                                        <span>اریک کالسکی</span>
                                        <span>مدیر محصولات</span>
                                    </div>
                                </div>
                                <a href="admin-profile-view.html" class="dropdown-item is-media">
                                    <div class="icon">
                                        <i class="lnil lnil-user-alt"></i>
                                    </div>
                                    <div class="meta">
                                        <span>پروفایل</span>
                                        <span>پروفایل خود را مشاهده کنید</span>
                                    </div>
                                </a>
                                <a class="dropdown-item is-media layout-switcher">
                                    <div class="icon">
                                        <i class="lnil lnil-layout"></i>
                                    </div>
                                    <div class="meta">
                                        <span>طرح بندی</span>
                                        <span>تغییر به admin/webapp</span>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item is-media">
                                    <div class="icon">
                                        <i class="lnil lnil-briefcase"></i>
                                    </div>
                                    <div class="meta">
                                        <span>پروژه ها</span>
                                        <span>همه پروژه های من</span>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item is-media">
                                    <div class="icon">
                                        <i class="lnil lnil-users-alt"></i>
                                    </div>
                                    <div class="meta">
                                        <span>تیم</span>
                                        <span>تیم خود را مدریت کنید</span>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item is-media">
                                    <div class="icon">
                                        <i class="lnil lnil-cog"></i>
                                    </div>
                                    <div class="meta">
                                        <span>تنظیمات</span>
                                        <span>تظیمات حساب کاربری</span>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <div class="dropdown-item is-button">
                                    <button class="button h-button is-primary is-raised is-fullwidth logout-button">
                                        <span class="icon is-small">
                                          <i data-feather="log-out"></i>
                                      </span>
                                        <span>خروج</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>



            </ul>
        </div>
    </div>

</template>

<script>
export default {
    name: "AdminSideBar"
};
</script>

<style scoped>

</style>
