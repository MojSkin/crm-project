<template>
    <div class="dropdown-menu text-right" role="menu" v-if="!refresh">
        <div class="dropdown-content">
            <div class="dropdown-head">
                <div class="h-avatar is-large">
                    <img class="avatar" :src="userData.avatar" v-if="userData.avatar" alt="">
                    <span class="avatar is-fake" v-else>{{ $helpers.makeAbbr(userData.dName) }}</span>
                </div>
                <div class="meta">
                    <span>{{ userData.dName }}</span>
<!--                    <span>مدیر ارشد سایت</span>-->
                </div>
            </div>
            <RouterLink :to="{name: 'admin.profile'}" class="dropdown-item is-media">
                <div class="icon">
                    <i class="fal fa-user"></i>
                </div>
                <div class="meta">
                    <span>پروفایل</span>
                    <span>پروفایل خود را مشاهده کنید</span>
                </div>
            </RouterLink>
            <a href="#" class="dropdown-item is-media">
                <div class="icon">
                    <i class="fal fa-user-cog"></i>
                </div>
                <div class="meta">
                    <span>تنظیمات</span>
                    <span>تظیمات حساب کاربری</span>
                </div>
            </a>
            <hr class="dropdown-divider">
            <div class="dropdown-item is-button">
                <button class="button h-button is-primary is-raised is-fullwidth logout-button" @click="logout">
                    <span class="icon is-small">
                        <i class="fal fa-sign-out-alt"></i>
                    </span>
                    <span>خروج</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "AdminProfileDropdown",
    data() {
        return {
            userData: this.$store.state.userData,
            base_url: window.location.origin,
            refresh: false
        }
    },
    created() {
        this.EventBus.on('avatarChanged', res => {
            this.userData.avatar = res
        })
        this.EventBus.on('userDataChanged', async res => {
            this.userData = this.$store.state.userData
        })
    },
    methods: {
        logout() {
            this.$store.commit('setUserData', null)
            this.$router.push('/login')
        }
    }
};
</script>

<style scoped>
.icon {
    font-size: 1.2rem;
}
</style>
