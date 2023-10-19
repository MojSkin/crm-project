<template>
    <!--Search panel-->
    <div id="search-panel" class="right-panel-wrapper is-search is-left">
        <div class="panel-overlay"></div>

        <div class="right-panel">
            <div class="right-panel-head">
                <img class="light-image" src="/resources/assets/img/logos/logo/logo.svg" alt="" />
                <img class="dark-image" src="/resources/assets/img/logos/logo/logo-light.svg" alt="" />
                <a class="close-panel">
                    <i data-feather="chevron-left"></i>
                </a>
            </div>
            <div class="right-panel-body has-slimscroll">
                <div class="field">
                    <div class="control has-icon">
                        <input type="text" class="input is-rounded search-input" placeholder="Search...">
                        <div class="form-icon">
                            <i data-feather="search"></i>
                        </div>
                        <div class="search-results has-slimscroll"></div>
                    </div>
                </div>

                <div class="recent">
                    <h4>اخیرا دیده اید</h4>
                    <div class="recent-block">
                        <a class="media-flex-center">
                            <div class="h-icon is-info is-rounded is-small">
                                <i data-feather="chrome"></i>
                            </div>
                            <div class="flex-meta">
                                <span>پشتیبانی مرورگر</span>
                                <span>Blog post</span>
                            </div>
                        </a>
                        <a class="media-flex-center">
                            <div class="h-icon is-orange is-rounded is-small">
                                <i data-feather="tv"></i>
                            </div>
                            <div class="flex-meta">
                                <span>API توییچ</span>
                                <span>Blog post</span>
                            </div>
                        </a>
                        <a class="media-flex-center">
                            <div class="h-icon is-green is-rounded is-small">
                                <i data-feather="twitter"></i>
                            </div>
                            <div class="flex-meta">
                                <span>احراز هویت توییچ</span>
                                <span>Blog post</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="recent">
                    <h4>کاربرانی که اخیرا اضافه شده اند</h4>
                    <div class="recent-block">
                        <a class="media-flex-center">
                            <div class="h-avatar is-small">
                                <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" alt="" data-user-popover="0">
                            </div>
                            <div class="flex-meta">
                                <span>آلیس</span>
                                <span>مهندس نرم افزار</span>
                            </div>
                        </a>
                        <a class="media-flex-center">
                            <div class="h-avatar is-small">
                                <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/13.jpg" alt="" data-user-popover="6">
                            </div>
                            <div class="flex-meta">
                                <span>رویا</span>
                                <span>طراح UI/UX</span>
                            </div>
                        </a>
                        <a class="media-flex-center">
                            <div class="h-avatar is-small">
                                <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/22.jpg" alt="" data-user-popover="5">
                            </div>
                            <div class="flex-meta">
                                <span>Jimmy H.</span>
                                <span>مدیر پروژه ها</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
export default {
    name: "AdminSearchPanel"
};
</script>

<style scoped>

</style>
