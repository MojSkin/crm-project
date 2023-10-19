<template>
    <ul v-bind="$attrs">
        <li>
            <RouterLink class="active" :to="{ name: 'admin.dashboard' }" data-content="پیشخوان" active-class="active">
                <i class="fal fa-desktop"></i>
            </RouterLink>
        </li>
        <li>
            <RouterLink :to="{ name: 'admin.projects' }" data-content="پروژه‌ها" active-class="active">
                <i class="fal fa-building"></i>
            </RouterLink>
        </li>
        <li>
            <RouterLink :to="{ name: 'admin.contacts' }" data-content="اشخاص" active-class="active">
                <i class="fal fa-address-book"></i>
            </RouterLink>
        </li>
        <li>
            <RouterLink :to="{ name: 'admin.formBuilder' }" data-content="فرم‌ساز" active-class="active">
                <i class="fal fa-window-alt"></i>
            </RouterLink>
        </li>
        <li>
            <RouterLink :to="{ name: 'admin.formInbox' }" data-content="صندوق ورودی" active-class="active">
                <i class="fal fa-inbox-in"></i>
            </RouterLink>
        </li>
    </ul>

    <ul :class="hideContent ? 'bottom-icon-side-menu' : 'bottom-menu'">
        <li>
            <RouterLink :to="{name: 'admin.settings'}" data-content="تنظیمات سیستم">
                <i class="fal fa-cog"></i>
            </RouterLink>
        </li> <!-- Profile -->
        <li v-if="!hideProfile">
            <div class="dropdown profile-dropdown dropdown-trigger is-spaced is-up" :class="{'is-active': showProfileMenu}">
                <div class="h-avatar is-medium" @click="showProfileMenu = !showProfileMenu">
                    <img class="avatar" :src="userData.avatar" v-if="userData.avatar" alt="">
                    <span class="avatar is-fake" v-else>{{ $helpers.makeAbbr(userData.dName) }}</span>
                </div>
                <AdminProfileDropdown/>
            </div>
        </li>
    </ul>


</template>

<script>
import AdminProfileDropdown from "./AdminProfileDropdown.vue";
export default {
    name: "AdminAsideIconMenu",
    components: { AdminProfileDropdown },
    props: {
        hideContent: {
            type: Boolean,
            default: true
        },
        hideProfile: {
            type: Boolean,
            default: true
        },
    },
    created() {
        this.EventBus.on('userDataChanged', async res => {
            this.userData = this.$store.state.userData
        })
    },
    data() {
        return {
            display: this.hideContent ? 'none' : 'block',
            showProfileMenu: false,
            userData: this.$store.state.userData,
            base_url: window.location.origin,
        }
    }
};
</script>

<style scoped>
li a::after {
    display: v-bind(display)
}
li a i {
    font-size: 1.75rem;
    width:2rem;
    display: block
}
li:has(a.active) {
    display: block;
    background-color: var(--bs-info, blue);
    border-right: 2px solid red;
}
</style>
