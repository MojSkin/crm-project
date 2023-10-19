<template>
    <Component :is="layout" v-if="!error404">
        <template #title>پروفایل</template>
        <b-overlay :show="loading">
            <div class="profile-wrapper">
                <div class="profile-body">
                    <div class="columns is-relative">
                        <div class="column is-4" id="profile-header">
                            <div class="profile-card">
                                <div class="profile-card-section no-padding">
                                    <div class="account-box is-navigation">
                                        <div class="media-flex-center">
                                            <div class="h-avatar is-xl">
                                                <img class="avatar" :src="base_url+'/storage/uploads/user_avatars/'+userProfile?.avatar" v-if="userProfile?.avatar && !loading" alt="">
                                                <span class="avatar is-fake has-text-grey-light is-weight-700" style="font-size: 2rem;" v-else-if="!userProfile?.avatar && !loading">{{ $helpers.makeAbbr(userProfile?.display_name || '-') }}</span>
                                                <div class="placeload-wrap" v-else>
                                                    <div class="placeload-avatar is-xl is-rounded-full loads"></div>
                                                </div>
                                                <button class="badge button is-rounded is-light border-1 d-block px-0" @click="routeToEdit('general')">
                                                    <i class="fal fa-camera"></i>
                                                </button>
                                            </div>
                                            <div class="flex-meta" v-if="!loading">
                                                <span>{{ userProfile?.display_name }}</span>
                                                <span class="subtitle">{{ userProfile?.role || 'فاقد نقش کاربری' }}</span>
                                            </div>
                                            <div class="content-shape-group mx-2" v-else>
                                                <div class="content-shape is-grow-1 mw-80 loads"></div>
                                                <div class="content-shape mw-60 loads" style="max-width: 60%;"></div>
                                            </div>
                                        </div>
                                        <div class="light-text is-flex is-justify-content-center is-flex-direction-column" v-if="loading">
                                            <span class="content-shape loads mx-auto mb-2" style="max-width: 320px;" v-if="loading"></span>
                                            <span class="content-shape loads mx-auto mb-2" style="max-width: 180px;" v-if="loading"></span>
                                        </div>
                                        <p class="light-text dark-inverted has-text-centered" v-else>
                                            <span v-if="userProfile?.slogan">{{ userProfile.slogan }}</span>
                                            <span v-if="!userProfile?.slogan && changeable">یک توضیح مختصر در مورد خودتان بنویسید...</span>
                                            <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('general')"/>
                                        </p>
                                        <div class="profile-stats is-flex justify-content-center mt-5">
                                            <div class="placeload-wrap is-xs is-flex" v-if="loading">
                                                <div class="placeload-avatar is-rounded-full loads"></div>
                                                <div class="placeload-avatar is-rounded-full loads"></div>
                                                <div class="placeload-avatar is-rounded-full loads"></div>
                                            </div>
                                            <div class="socials dark-inverted is-flex is-align-items-center" v-else>
                                                <a class="has-text-grey-dark dark-inverted" v-for="social in userProfile?.socials" :href="social.url" target="_blank">
                                                    <i :class="social.icon"></i>
                                                </a>
                                                <span class="small" v-if="!userProfile?.socials?.length">شبکه‌های اجتماعی خود را اضافه کنید</span>
                                                <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('social')"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="is-divider"></div>
                                    <div class="profile-card-section">
                                        <div class="section-title dark-inverted">
                                            <h3>درباره</h3>
                                            <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('general')"/>
                                        </div>
                                        <div class="section-content">
                                            <div class="light-text is-flex is-justify-content-start is-flex-direction-column" v-if="loading">
                                                <span class="content-shape loads mb-2"></span>
                                                <span class="content-shape loads mb-2"></span>
                                                <span class="content-shape loads mb-2"></span>
                                                <span class="content-shape loads mb-2"></span>
                                                <span class="content-shape loads mb-2" style="max-width: 220px;"></span>
                                            </div>
                                            <p class="description" v-else>
                                                <span v-if="userProfile?.about">{{ userProfile.about }}</span>
                                                <span v-if="!userProfile?.about && changeable">خودتان را به هر نحوی که فکر می‌کنید مخاطب راحت‌تر شما را می‌شناسد، معرفی کنید...</span>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="column is-8">
                        </div>
<!--                        <div class="column is-8">-->
<!--                            <div class="profile-card">-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>سوابق تحصیلی</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('education')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="experience-wrapper">-->
<!--                                            <span class="experience-item" v-if="!userProfile?.educations">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ سابقه تحصیلی درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="experience-item" v-for="ed in userProfile?.educations">-->
<!--                                                <div class="meta">-->
<!--                                                    <span>-->
<!--                                                        <span class="rem-100 dark-inverted">{{ ed.university }}</span><span class="ml-2 has-text-grey-light">{{ ed.year }}</span>-->
<!--                                                    </span>-->
<!--                                                    <span>-->
<!--                                                        <span class="mr-2 rem-100 is-inline-block has-text-primary is-weight-700">{{ ed.title }}</span>-->
<!--                                                        <span class="mr-2 is-inline-block" v-if="ed.major">{{ ed.major }}</span>-->
<!--                                                        <span class="is-inline-block" v-if="ed.branch">{{ ed.branch }}</span>-->
<!--                                                    </span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>سوابق کاری</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('job')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="experience-wrapper">-->
<!--                                            <span class="experience-item" v-if="!userProfile?.jobs">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ سابقه کاری درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="experience-item" v-for="jb in userProfile?.jobs">-->
<!--                                                <div class="meta">-->
<!--                                                    <span>-->
<!--                                                        <span class="rem-100 dark-inverted">{{ jb.title }}</span>-->
<!--                                                        <span class="ml-2 rem-100 is-inline-block has-text-primary is-weight-700">{{ jb.company }}</span>-->
<!--                                                    </span>-->
<!--                                                    <span>-->
<!--                                                        <span class="mr-2 is-inline-block" v-if="jb.start">{{ jb.start }}</span>-->
<!--                                                        <span class="is-inline-block" v-if="jb.end"> - {{ jb.end }}</span>-->
<!--                                                    </span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>زبان‌های خارجی</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('language')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="languages-wrapper">-->
<!--                                            <span class="languages-item" v-if="!userProfile?.languages">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ زبان خارجی درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="languages-item is-flex is-align-items-center" v-for="lang in userProfile?.languages">-->
<!--                                                <div class="icon-wrap is-ltr">-->
<!--                                                    <star-ratings-->
<!--                                                        class="m-0 w-100 p-0"-->
<!--                                                        :number-of-stars="5"-->
<!--                                                        :star-size="16"-->
<!--                                                        :show-control="false"-->
<!--                                                        :disable-click="true"-->
<!--                                                        readonly="readonly"-->
<!--                                                        v-model="lang.level"-->
<!--                                                    ></star-ratings>-->
<!--                                                </div>-->
<!--                                                <div class="meta">-->
<!--                                                    <span class="dark-inverted is-inline-block mr-3">{{ lang.title }}</span> <span class="is-inline-block">{{ lang.level }} از 5</span>-->
<!--                                                    <span class="small" v-if="lang.is_native==1">(زبان مادری، مسلط)</span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>مهارت‌ها</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('skill')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="languages-wrapper">-->
<!--                                            <span class="languages-item" v-if="!userProfile?.skills">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ مهارتی درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="languages-item" v-for="skill in userProfile?.skills">-->
<!--                                                <div class="icon-wrap is-ltr mr-2">-->
<!--                                                    <star-ratings-->
<!--                                                        class="m-0 w-100 p-0"-->
<!--                                                        :number-of-stars="5"-->
<!--                                                        :star-size="16"-->
<!--                                                        :show-control="false"-->
<!--                                                        :disable-click="true"-->
<!--                                                        readonly="readonly"-->
<!--                                                        v-model="skill.level"-->
<!--                                                    ></star-ratings>-->
<!--                                                </div>-->
<!--                                                <div class="skill-info">-->
<!--                                                    <span class="dark-inverted">{{ skill.title }}</span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>مدارک</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('doc')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="experience-wrapper">-->
<!--                                            <span class="experience-item" v-if="!userProfile?.jobs">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ سابقه کاری درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="experience-item" v-for="doc in userProfile?.documents">-->
<!--                                                <div class="meta">-->
<!--                                                    <span>-->
<!--                                                        <span class="is-block rem-100 dark-inverted">{{ doc.title }}</span>-->
<!--                                                        <span class="rem-100 is-inline-block has-text-primary is-weight-700">{{ doc.company }}</span>-->
<!--                                                    </span>-->
<!--                                                    <span>-->
<!--                                                        <span class="is-inline-block">{{ doc.year }}</span>-->
<!--                                                        <span class="mr-2 is-inline-block"> - {{ doc.level }}</span>-->
<!--                                                    </span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->

<!--                                </div>-->
<!--                                <div class="profile-card-section">-->
<!--                                    <div class="section-title dark-inverted">-->
<!--                                        <h3>افتخارات</h3>-->
<!--                                        <i class="fal fa-pencil edit" v-if="changeable && !loading" @click="routeToEdit('trophie')"/>-->
<!--                                    </div>-->
<!--                                    <div class="section-content">-->
<!--                                        <div class="skills-wrapper">-->
<!--                                            <span class="skills-item" v-if="!userProfile?.trophies">-->
<!--                                                <p class="light-text"><span class="dark-inverted">هنوز هیچ مورد خاصی درج نشده است...</span></p>-->
<!--                                            </span>-->
<!--                                            <div class="skills-item" v-for="tr in userProfile?.trophies">-->
<!--                                                <div class="icon-wrap">-->
<!--                                                    <img src="https://via.placeholder.com/150x150" data-demo-src="assets/img/icons/stacks/js.svg" alt="">-->
<!--                                                </div>-->
<!--                                                <div class="skill-info">-->
<!--                                                    <span class="dark-inverted">Javascript</span>-->
<!--                                                    <span>7 سال تجربه</span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->

<!--                                        <div class="more-button has-text-centered" v-if="userProfile?.skill && userProfile?.trophies?.length > 8">-->
<!--                                            <a class="button h-button is-light">مشاهده بیشتر</a>-->
<!--                                        </div>-->

<!--                                    </div>-->

<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </b-overlay>
    </Component>

    <Component is="Page404" v-else />
</template>

<script>
import Page404 from "../404.vue";
export default {
    name: "UserProfileComponent",
    props: {
        layout: {
            type: String,
            default: 'AdminLayoutComponent',
        },
        changeable: Boolean,
        username: {
            type: [Boolean, String],
            default: false
        }
    },
    data() {
        return {
            loading: true,
            userProfile: {},
            base_url: window.location.origin,
            error404: false,
        }
    },
    components: {
        Page404
    },
    created() {
        this.getCurrentUserData()
        window.onscroll = (e) => {
            if (window.innerWidth > 768) {
                const el = document.getElementById('profile-header')
                if (window.scrollY >= 75) {
                    el.style.marginTop = (window.scrollY-75)+'px'
                } else {
                    el.style.marginTop = null
                }
            }
        }
    },
    beforeUnmount() {
        window.onscroll = null
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {
        getCurrentUserData() {
            this.loading = true
            Requests.getUserProfile(this.username).then(res => {
                if (res?.status) {
                    const details = res.result.details
                    this.userProfile = {
                        ...res.result,
                        ...res.result.details
                    }
                    for (const key in details) {
                        let value = details[key]
                        if (['socials', 'educations', 'jobs', 'languages', 'skills', 'documents', 'trophies'].includes(key)) {
                            try {
                                value = JSON.parse(value)
                            } catch {
                                value = []
                            }
                        }
                        this.userProfile[key] = value
                    }
                    // delete this.userProfile['details']
                } else {
                    this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت اطلاعات پروفایل کاربر', { type: 'error' })
                }
            }).catch(err => {
                if (err?.response?.status === 404) {
                    this.error404 = true
                }
            }).finally(res => {
                this.loading = false
            })
        },
        routeToEdit(tab) {
            this.$router.push({
                name: 'admin.profile.edit',
                params: { user: JSON.stringify(this.userProfile), tab }
            })
        }
    }
}
</script>

<style scoped>
p {
    text-align: justify
}
.profile-stats i:not(.fa-pencil),
.profile-stats .placeload-avatar {
    width: 28px !important;
    height: 28px !important;
    min-width: 28px !important;
    min-height: 28px !important;
    max-width: 28px !important;
    max-height: 28px !important;
    margin: 0 2px;
    border-radius: 50%;
    padding-top: 5px;
    text-align: center;
    -webkit-box-shadow: 1px 3px 10px 0 rgba(0, 0, 0, .06);
    box-shadow: 1px 3px 10px 0 rgba(0, 0, 0, .06);
}
i.fa-pencil.edit {
    cursor: pointer;
    color: #671cc9;
    margin-right: 10px;
    transition: 300ms all ease-in-out;
}
i.fa-pencil.edit:hover {
    color: #671cc9;
    opacity: .6;
}
</style>
