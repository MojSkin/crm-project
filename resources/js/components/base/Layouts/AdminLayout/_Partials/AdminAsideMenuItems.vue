<template>
    <ul :class="subMenuClass ? subMenuClass : ''">
        <li class="has-children" :class="{ 'active': active_item === 'projects' }" @click="active('projects')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-building mr-2"></i>
                    <span>پروژه‌ها (املاک)</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="projects">
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.projects' }">
                        <span>تعریف پروژه</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.contacts' }">
                        <span>اشخاص</span>
                    </RouterLink>
                </li>
            </ul>
        </li>
        <li class="has-children" :class="{ 'active': active_item === 'definitions' }" @click="active('definitions')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-database mr-2"></i>
                    <span>تعاریف اطلاعات پایه</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="definitions">
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.contact-positions' }">
                        <span>جایگاه شخصیت‌ها</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.project-types' }">
                        <span>نوع پروژه (ملک)</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.project-statuses' }">
                        <span>وضعیت پروژه (ملک)</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{ name: 'admin.project-results' }">
                        <span>نتیجه پروژه (ملک)</span>
                    </RouterLink>
                </li>
<!--                <li>-->
<!--                    <RouterLink class="is-submenu" :to="{name: 'admin.shop.categories'}">-->
<!--                        <span>دسته‌بندی محصولات</span>-->
<!--                    </RouterLink>-->
<!--                </li>-->
            </ul>
        </li>
        <li class="has-children" :class="{ 'active': active_item === 'tasks' }" @click="active('tasks')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-tasks-alt mr-2"></i>
                    <span>وظایف و کارها</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="tasks">
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>وظایف من</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>کارهای من</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>یادآورهای من</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>اعلان‌های من</span>
                    </RouterLink>
                </li>
            </ul>
        </li>
        <li class="divider"></li>
        <li class="has-children" :class="{ 'active': active_item === 'messaging' }" @click="active('messaging')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-envelope-open-text mr-2"></i>
                    <span>سرویس پیام کوتاه</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="messaging">
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>ارسال پیام</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>تنظیمات پیام‌رسان</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" to="#">
                        <span>ایجاد الگوهای پیام</span>
                    </RouterLink>
                </li>
            </ul>
        </li>
        <li>
            <RouterLink class="is-submenu" to="#">
                <i class="fal fa-comments-alt mr-2"></i>
                <span>پیام‌رسان</span>
            </RouterLink>
        </li>
        <li class="has-children" :class="{ 'active': active_item === 'forms' }" @click="active('forms')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-window-alt mr-2"></i>
                    <span>فرم‌ساز</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="forms">
                <li>
                    <RouterLink class="is-submenu" :to="{name: 'admin.formBuilder'}">
                        <span>فرمساز</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{name: 'admin.formInbox'}">
                        <span>صندوق ورودی فرم‌ها</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{name: 'admin.under-construction'}">
                        <span>پیگیری فرم</span>
                    </RouterLink>
                </li>
            </ul>
        </li>
        <li class="divider"></li>
        <li class="has-children" :class="{ 'active': active_item === 'users' }" @click="active('users')">
            <div class="collapse-wrap">
                <a class="parent-link">
                    <i class="fal fa-users-cog mr-2"></i>
                    <span>کاربران</span>
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <ul ref="users">
                <li>
                    <RouterLink class="is-submenu" :to="{name: 'admin.users'}">
                        <span>کاربران</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink class="is-submenu" :to="{name: 'admin.permissions'}">
                        <span>نقش‌های کاربری</span>
                    </RouterLink>
                </li>
            </ul>
        </li>
    </ul>

</template>

<script>
export default {
    name: "AdminAsideMenuItems",
    props: {
        subMenuClass: {
            type: [String, Boolean],
            default: false
        }
    },
    data() {
        return {
            active_item: '',
        }
    },
    methods: {
        active(item) {
            if (this.active_item === item) {
                jQuery(this.$refs[item]).slideUp()
                this.active_item = ''
                return
            }
            jQuery(this.$refs[this.active_item]).slideUp()
            jQuery(this.$refs[item]).slideDown()
            this.active_item = item
        }
    }
};
</script>

<style scoped>
li.has-children a.parent-link i:last-child {
    margin-right: auto;
    transition: all 300ms ease-in;

}
li.has-children.active a.parent-link i:last-child {
    transform-origin: center center;
    transform: rotate(-90deg);
}
</style>
