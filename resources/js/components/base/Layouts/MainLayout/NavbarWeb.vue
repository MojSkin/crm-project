<template>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="https://bulma.io">
                <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
            </a>

            <a role="button" class="navbar-burger" :class="{ 'is-active': isActive }" @click="isActive = !isActive" aria-label="menu" :aria-expanded="isActive" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu" :class="{ 'is-active': isActive }">
            <div class="navbar-start">
                <a class="navbar-item">
                    صفحه نخست
                </a>

                <a class="navbar-item">
                    معرفی مجتمع
                </a>

                <a class="navbar-item">
                    موفقیت‌های ما
                </a>

                <div class="navbar-item has-dropdown" :class="{'is-active': showOther}" @click="showOther = !showOther">
                    <a class="navbar-link">
                        درباره‌ی ما
                    </a>

                    <div class="navbar-dropdown" :class="{'is-hidden': !showOther}">
                        <a class="navbar-item">
                            درباره‌ی ما
                        </a>
                        <a class="navbar-item">
                            تماس با ما
                        </a>
                        <hr class="navbar-divider">
                        <a class="navbar-item">
                            موقعیت‌های همکاری
                        </a>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-info" @click="modalIsActive=true">
                            <strong>پیش ثبت‌نام</strong>
                        </a>
                        <RouterLink to="/login" class="button is-outlined is-info">ورود</RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <BUModal :show="modalIsActive" @close="modalIsActive=false" header-content="یک متن هدر">
        <div class="">
            سلام!
            این یک متن الکی است!
        </div>
    </BUModal>

</template>

<script>

export default {
    name: "NavbarWeb",
    data() {
        return {
            isActive: false,
            showNavbar: true,
            showOther: false,
            modalIsActive: false,
        }
    },

    methods: {
        launchModal() {

        }
    }

}
</script>

<style scoped>
</style>
