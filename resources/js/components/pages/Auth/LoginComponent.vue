<template>
    <BlankLayoutComponent>
        <div id="huro-app" class="app-wrapper">
            <div class="auth-wrapper">
                <div class="modern-login">
                    <div class="underlay h-hidden-mobile h-hidden-tablet-p"></div>
                    <div class="columns is-gapless is-vcentered">
                        <div class="column is-relative is-8 h-hidden-mobile h-hidden-tablet-p">
                            <div class="hero is-fullheight is-image">
                                <div class="hero-body p-0">
                                    <div class="container-fluid p-0 overflow-hidden">
                                        <div class="columns">
                                            <div class="column p-0 has-school-background">
<!--                                                <img class="hero-image" src="/assets/img/illustrations/login/station.svg" alt="">-->
<!--                                                <img class="centered-image" src="/assets/img/bg/tebyan.jpg" alt="">-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column has-text-centered pt-5" style="height: 100vh;">
                            <RouterLink to="/" class="mt-5" style="display: inline-block; height: 120px">
                                <img class="light-image" style="max-height: 260px;" :src="this.base_url+'/assets/img/logo-main.png'" alt="لوگو">
                            </RouterLink>
                            <div class="is-form">
                                <div class="hero-body">
                                    <div class="form-text" v-if="action=='login'">
                                        <h2>ورود</h2>
                                        <p>برای ادامه، به حساب کاربری خود وارد شوید.</p>
                                    </div>
                                    <div class="form-text" v-if="action=='remember'">
                                        <h2>بازیابی حساب</h2>
                                        <p>رمز عبور حساب خود را بازنشانی کنید.</p>
                                    </div>
                                    <form class="login-wrapper" @submit.prevent="submitLogin" v-if="action=='login'">
                                        <div class="control has-validation" :class="{ 'has-error': validator.username?.$errors?.length }">
                                            <input class="input" v-model="username" type="text" autocomplete="off">
                                            <small class="error-text">{{ validator.username?.$errors[0]?.$message || '&nbsp;' }}</small>
                                            <div class="auth-label">نام کاربری / پست الکترونیک / شماره همراه</div>
                                            <div class="auth-icon text-white">
                                                <i class="fal fa-user-circle"></i>
                                            </div>
                                            <div class="validation-icon is-error">
                                                <div class="icon-wrapper">
                                                    <i class="fas fa-times text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control has-validation" :class="{ 'has-error': validator.password?.$errors?.length }">
                                            <input type="password" class="input" v-model="password" :autocomplete="false">
                                            <small class="error-text">{{ validator.password?.$errors[0]?.$message || '&nbsp;' }}</small>
                                            <div class="auth-label">رمزعبور</div>
                                            <div class="auth-icon">
                                                <i class="fal fa-lock-alt"></i>
                                            </div>
                                            <div class="validation-icon is-error">
                                                <div class="icon-wrapper">
                                                    <i class="fas fa-times text-white"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control is-flex">
                                            <label class="remember-toggle">
                                                <input type="checkbox">
                                                <span class="toggler">
                                                    <span class="active">
                                                        <i data-feather="check"></i>
                                                    </span>
                                            <span class="inactive">
                                                        <i data-feather="circle"></i>
                                                    </span>
                                            </span>
                                            </label>
                                            <div class="remember-me">مرابه خاطر بسپار</div>
                                            <a @click="action='remember'">بازیابی رمز عبور</a>
                                        </div>
                                        <div class="button-wrap has-help d-flex">
                                            <button type="submit" class="button h-button is-big is-rounded is-primary is-bold is-raised ms-auto" :class="{ 'w-100': loading }" :disabled="loading">
                                                <span v-if="loading">
                                                    <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                                                </span>
                                                <span v-else>اکنون وارد شوید</span>
                                            </button>
                                            <RouterLink tag="button" type="button"  to="/" class="button h-button is-warning is-outlined is-big is-rounded is-lower border-0" v-if="!loading">
                                                <span>بازگشت</span>
                                            </RouterLink>
                                        </div>
                                    </form>

                                    <form class="login-wrapper" @submit.prevent="false" v-if="action=='remember'">
                                        <p class="recover-text">ایمیل خود را وارد کرده و بر روی دکمه تایید کلیک کنید تا رمز عبور خود را بازنشانی کنید. ما یک ایمیل برای شما ارسال خواهیم کرد که مراحل تکمیل مراحل را شرح می دهد.</p>
                                        <div class="control has-validation">
                                            <input class="input" v-model="username" type="text" autocomplete="off">
                                            <small class="error-text">{{ validator.username?.$errors[0]?.$message || '&nbsp;' }}</small>
                                            <div class="auth-label">نام کاربری / پست الکترونیک / شماره همراه</div>
                                            <div class="auth-icon text-white">
                                                <i class="fal fa-user-circle"></i>
                                            </div>
                                            <div class="validation-icon is-error">
                                                <div class="icon-wrapper">
                                                    <i class="fas fa-times text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-wrap">
                                            <button id="cancel-recover" type="button" class="button h-button is-white is-big is-rounded is-lower" @click="action='login'">لغو</button>
                                            <button type="submit" class="button h-button is-solid is-big is-rounded is-lower is-raised is-colored">تایید</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BlankLayoutComponent>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
const isEmail  = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
const isMobile = /^(\+989|989|09|9)(0|1|2|3|9)\d{8}/;

export default {
    name: "LoginComponent",
    props: {},
    inject: ['base_url'],
    setup () {
        return { validator: useVuelidate() }
    },
    data() {
        return {
            username: '',
            password: '',
            loading: false,
            action: 'login',
            responseErrors: {},
            defaultRedirect: (import.meta?.env?.VITE_ADMIN_ROUTE_PREFIX && import.meta?.env?.VITE_ADMIN_ROUTE_PREFIX.length) ? '/'+import.meta.env.VITE_ADMIN_ROUTE_PREFIX : '/',
        }
    },
    validations () {
        return {
            username: {
                required: {
                    $validator: (val) => {
                        return (val?.length != 0)
                    },
                    $message: 'ارائه نام کاربری الزامی است'
                },
            },
            password: {
                required: {
                    $validator: (val) => {
                        return (val?.length != 0)
                    },
                    $message: 'ارائه کلمه عبور الزامی است'
                }
            },
        }
    },
    created() {
    },
    mounted() {
    },
    computed: {
    },
    watch: {},
    methods: {
        submitLogin() {
            if (!this.loading) {
                this.validator.$touch()
                if (!this.validator.$invalid) {
                    this.loading = true
                    Requests.login(this.username, this.password).then(res => {
                        if (res?.status) {
                            this.$store.commit('setUserData', res.userData)
                            if (res.userData?.avatar.includes('placeholder') || !res.userData?.fName || !res.userData?.lName) {
                                this.$helpers.notify('خوش آمدید', 'لطفا قبل از هر چیز پروفایل خود را کامل کنید!');
                                this.$router.push({name: 'admin.profile'})
                            } else {
                                this.$helpers.notify('خوش آمدید', res?.data?.message);
                                this.$router.push(res?.redirect || this.defaultRedirect)
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.response?.data?.message ?? "بروز خطا در برقراری ارتباط با سرور");
                        }
                    }).catch(err => {
                        this.$helpers.notify('خطا', err?.response?.data?.message || "بروز خطای ناشناخته در برقراری ارتباط با سرور", {type: 'error'});
                    }).finally(res => {
                        this.loading = false
                    })
                }
            }
        }
    }
}
</script>

<style scoped>
.hero.is-fullheight .hero-body {
    align-items: center;
    display: flex;
}
.hero .nav, .hero.is-success .nav {
    -webkit-box-shadow: none;
    box-shadow: none;
}
.box {
    margin-top: 5rem;
}
.avatar {
    margin-top: -70px;
    padding-bottom: 20px;
}
.avatar img {
    padding: 5px;
    background: #fff;
    border-radius: 50%;
    -webkit-box-shadow: 0 2px 3px rgba(10,10,10,.1), 0 0 0 1px rgba(10,10,10,.1);
    box-shadow: 0 2px 3px rgba(10,10,10,.1), 0 0 0 1px rgba(10,10,10,.1);
}
input {
    font-weight: 300;
}
p {
    font-weight: 700;
}
p.subtitle {
    padding-top: 1rem;
}

.login-hr{
    border-bottom: 1px solid black;
}

.has-text-black{
    color: black;
}

.field{
    padding-bottom: 10px;
}

.fa{
    margin-left: 5px;
}
.centered-image {
    width: auto;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.has-school-background {
    background-image: url(/assets/img/logo-dark-bg.png);
    height: 100vh;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
