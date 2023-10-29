<template>
    <AdminLayoutComponent>
        <template #title>پیشخوان</template>
        <div class="business-dashboard hr-dashboard">

            <div class="columns">

                <div class="column is-8">

                    <div class="columns is-multiline">

                        <!--Header-->
                        <div class="column is-12">
                            <div class="block-header" :style="'background-size: contain; background-repeat: no-repeat; background-image: url('+base_url+'/assets/img/illustrations/placeholders/welcome.svg)'">
                                <div class="current-user is-flex is-align-items-end">
                                    <div class="h-avatar is-large">
                                        <img class="avatar is-squared" :src="currentUser.avatar" alt="">
                                    </div>
                                    <h2 class="ml-2 has-text-white h2">
                                        <span class="has-text-info-light" v-text="currentUser.dName"/>
                                        <span class="ml-2">عزیز، خوش آمدید</span>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="recent-rookies">
                                <div class="recent-rookies-header">
                                    <h3 class="dark-inverted">آخرین اعلان‌ها</h3>
                                    <a class="action-link">مشاهده همه</a>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-table-header">
                                        <span class="is-grow-lg">پیام</span>
                                        <span class="cell-end">فعالیت ها</span>
                                        <span class="cell-end">فعالیت ها</span>
                                    </div>
                                    <div class="flex-table-item">
                                        <div class="flex-table-cell is-media is-grow-lg" data-th="">
                                            <div class="h-icon is-green">
                                                <i class="lnil lnil-envelope-alt"></i>
                                            </div>
                                            <div>
                                                <span class="item-name dark-inverted">پیام جدید</span>
                                                <span class="item-meta">
                                                    <span>پیام جدید از ...</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-table-cell cell-end">
                                            <span class="tag is-success is-rounded">جدید</span>
                                        </div>
                                        <div class="flex-table-cell cell-end">
                                            <a class="action-link is-pushed-mobile">باز کردن</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="recent-rookies position-relative" style="min-height: 301px">
                                <b-overlay :show="loadingProjects">
                                    <div class="recent-rookies-header">
                                        <h3 class="dark-inverted">جدیدترین پروژه‌ها</h3>
                                        <RouterLink :to="{ name: 'admin.projects' }" class="action-link">مشاهده همه</RouterLink>
                                    </div>

                                    <div class="columns user-grid user-grid-v4">
                                        <div class="column is-12 is-flex" v-if="!projects?.length">
                                            <div class="inner-list-item media-flex-center mx-auto">
                                                <img :src="base_url+'/assets/img/illustrations/placeholders/no-tasks-3.svg'" alt="" style="height: 280px">
                                            </div>
                                        </div>
                                        <div class="column is-4" v-for="project in projects">
                                            <ProjectSingleCardComponent :project="project" header-position="BOTTOM" no-date no-status no-result no-details no-description/>
                                        </div>
                                    </div>
                                </b-overlay>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="column is-4">

                    <!--Widget-->
                    <div class="list-widget list-widget-v2 tabbed-widget">
                        <div class="widget-head">
                            <h3 class="dark-inverted">کارهای من</h3>
                            <div class="tabbed-controls">
                                <a class="tabbed-control" :class="{'is-active': todoListTab=='waiting'}" @click="todoListTab='waiting'">
                                    <span>منتظر</span>
                                </a>
                                <a class="tabbed-control" :class="{'is-active': todoListTab=='all'}" @click="todoListTab='all'">
                                    <span>همه</span>
                                </a>
                                <div class="tabbed-naver"></div>
                            </div>
                        </div>

                        <div class="inner-list-wrapper is-active">
                            <div class="inner-list">
                                <!--List Item-->
                                <div class="inner-list-item media-flex-center">
                                    <img :src="base_url+'/assets/img/illustrations/placeholders/dashboard-no-todo.svg'" alt="">
                                </div>
<!--                                <div class="inner-list-item media-flex-center">-->
<!--                                    <div class="animated-checkbox is-unchecked">-->
<!--                                        <input type="checkbox">-->
<!--                                        <div class="checkmark-wrap">-->
<!--                                            <div class="shadow-circle"></div>-->
<!--                                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">-->
<!--                                                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"></circle>-->
<!--                                                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>-->
<!--                                            </svg>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="flex-meta is-light">-->
<!--                                        <a href="#">بررسی گزارش  آنا</a>-->
<!--                                        <span>9:45am</span>-->
<!--                                    </div>-->
<!--                                    <div class="flex-end">-->
<!--                                        <span class="tag is-rounded">در حال انجام</span>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </AdminLayoutComponent>
</template>

<script>

import ProjectSingleCardComponent from "@/components/pages/AdminProject/ProjectSingleCardComponent.vue";

export default {
    name: "StudentsDashboardComponent",
    components: {ProjectSingleCardComponent},
    inject: ['base_url'],
    data() {
        return {
            currentUser: null,
            todoListTab: 'waiting',
            projects: [],
            loadingProjects: false,
        }
    },
    created() {
        let crmState = JSON.parse(localStorage?.getItem('crmState'))
        this.currentUser = crmState.userData
    },
    mounted() {
        this.getProjects()
    },
    computed: {},
    watch: {},
    methods: {
        getProjects() {
            this.loadingProjects = true
            Requests.getProjectsList(null, 3).then(res => {
                this.projects = res.result || []
            }).finally(res => {
                this.loadingProjects=false
            })
        },
    }
}
</script>

<style scoped>
</style>
