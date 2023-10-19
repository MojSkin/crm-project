<template>
    <!--Mobile navbar-->
    <nav class="navbar mobile-navbar no-shadow is-hidden-desktop is-hidden-tablet" aria-label="main navigation">
        <div class="container">
            <!-- Brand -->
            <div class="navbar-brand">
                <!-- Mobile menu toggler icon -->
                <div class="brand-start">
                    <div class="navbar-burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <a class="navbar-item is-brand" href="index-3.html">
                    <img class="light-image" src="/resources/assets/img/logos/logo/logo.svg" alt="">
                    <img class="dark-image" src="/resources/assets/img/logos/logo/logo-light.svg" alt="">
                </a>

                <div class="brand-end">
                    <div class="navbar-item has-dropdown is-notification is-hidden-tablet is-hidden-desktop">
                        <a class="navbar-link is-arrowless" href="javascript:void(0);">
                            <i data-feather="bell"></i>
                            <span class="new-indicator pulsate"></span>
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
                                            <div class="img-left">
                                                <img class="user-photo" alt="" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" />
                                            </div>
                                            <div class="user-content">
                                                <p class="user-info"><span class="name">آلیس</span> دیدگاهی گذاشت.</p>
                                                <p class="time">1 ساعت پیش</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="notification-item">
                                            <div class="img-left">
                                                <img class="user-photo" alt="" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/12.jpg" />
                                            </div>
                                            <div class="user-content">
                                                <p class="user-info"><span class="name">پاشا</span> فایلی را آپلود کرد</p>
                                                <p class="time">2 ساعت پیش</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="notification-item">
                                            <div class="img-left">
                                                <img class="user-photo" alt="" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/13.jpg" />
                                            </div>
                                            <div class="user-content">
                                                <p class="user-info"><span class="name">رویا</span> به شما پیغامی فرستاد.</p>
                                                <p class="time">2 ساعت پیش</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="notification-item">
                                            <div class="img-left">
                                                <img class="user-photo" alt="" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/25.jpg" />
                                            </div>
                                            <div class="user-content">
                                                <p class="user-info"><span class="name">سارا</span> دیدگاهی گذاشت.</p>
                                                <p class="time">3 ساعت پیش</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown is-right is-spaced dropdown-trigger user-dropdown">
                        <div class="is-trigger" aria-haspopup="true">
                            <div class="profile-avatar">
                                <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/8.jpg" alt="">
                            </div>
                        </div>
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
                                <a href="#" class="dropdown-item is-media">
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
                                        <span>تیم های خود را مدریت کنید</span>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item is-media">
                                    <div class="icon">
                                        <i class="lnil lnil-cog"></i>
                                    </div>
                                    <div class="meta">
                                        <span>تنظیمات</span>
                                        <span>تنظیمات حساب کاربری</span>
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
                </div>

            </div>
        </div>
    </nav>

</template>

<script>
export default {
    name: "AdminMobileNavbar"
};
</script>

<style scoped>

</style>
