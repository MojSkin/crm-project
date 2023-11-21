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
                                        <span class="has-text-info-light" v-text="currentUser.dName"/> <span>عزیز، خوش آمدید</span>
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
                    <div class="list-widget list-widget-v2 tabbed-widget is-relative" style="min-height:475px; max-height:460px">
                        <b-overlay :show="loadingTodos">
                            <div class="widget-head">
                                <h3 class="dark-inverted is-flex is-align-items-center">
                                    <span>کارهای من</span>
                                    <span class="hint--light hint--rounded hint--top pt-1" data-hint="افزودن کار جدید">
                                        <i class="ml-2 is-clickable has-text-info fal fa-plus" @click="openTodoModal(null)"></i>
                                    </span>
                                </h3>
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

                            <div class="inner-list-wrapper is-active overflow-scroll pr-3" style="max-height: 390px; margin-left: -13px; margin-right: -8px">
                                <div class="inner-list is-relative">
                                    <!--List Item-->
                                    <div class="inner-list-item media-flex-center" v-if="!todos.length">
                                        <img :src="base_url+'/assets/img/illustrations/placeholders/dashboard-no-todo.svg'" alt="">
                                    </div>
                                    <template v-for="todo in todos">
                                        <div class="inner-list-item media-flex-center is-relative p-1" v-if="!todo.is_done || todoListTab === 'all'">
                                            <b-overlay :show="updatingTodos.includes(todo.id)" bg-variant-color="rgba(250, 250, 250, 0.05)" >
                                                <div class="animated-checkbox is-clickable" :class="todo.is_done ? 'is-checked' : 'is-unchecked'" @click.exact="changeTodoStatus(todo)">
                                                    <div class="checkmark-wrap">
                                                        <div class="shadow-circle"></div>
                                                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"></circle>
                                                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-meta is-light is-clickable" @click.stop="openTodoModal(todo)">
                                                    <div class="is-weight-500 text-13 w-100 dark-inverted" v-text="todo.title"/>

                                                    <span class="is-flex is-flex-direction-row align-items-center" v-if="todo.due_date != null">
                                                        <span class="is-ltr text-10 has-text-left mr-2">{{ $helpers.jDate(todo.due_date, 'jYYYY/jMM/jDD - HH:mm') }}</span>
                                                        <span class="tag is-rounded block text-10">{{ todo.flag }}</span>
                                                    </span>

                                                </div>
                                                <div class="flex-end is-flex-direction-row">
                                                    <span class="tag is-rounded is-weight-700 is-success block mb-0" v-if="todo.is_done">پایان</span>
                                                    <span class="tag is-rounded is-weight-700 block mb-0" :class="checkTodoStatus(todo.due_date) ? '' : 'is-warning'" v-else v-text="checkTodoStatus(todo.due_date) ? 'منتظر' : 'تاخیر'"/>
                                                </div>
                                            </b-overlay>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayoutComponent>
    <AdminTodoComponent ref="todoModal" :record="givenRecord"/>
</template>

<script>

import ProjectSingleCardComponent from "@/components/pages/AdminProject/ProjectSingleCardComponent.vue";
import AdminTodoComponent from "@/components/pages/AdminTodo/AdminTodoComponent.vue";

export default {
    name: "StudentsDashboardComponent",
    components: {AdminTodoComponent, ProjectSingleCardComponent },
    inject: ['base_url'],
    data() {
        return {
            currentUser: null,
            todoListTab: 'waiting',
            projects: [],
            todos: [],
            updatingTodos: [],
            loadingProjects: false,
            loadingTodos: false,
            givenRecord: null,
        }
    },
    created() {
        let crmState = JSON.parse(localStorage?.getItem('crmState'))
        this.currentUser = crmState.userData
        this.EventBus.on('todoInserted', todo => {
            for (let i = 0; i < this.todos.length; i++) {
                if (this.todos[i].id === todo.id) {
                    this.todos.splice(i, 1)
                    break
                }
            }
            this.todos.push(todo)
            this.todos = this.todos.sort((a,b) => {
                const checkA = a.due_date != null ? a.due_date : a.created_at
                const checkB = b.due_date != null ? b.due_date : b.created_at
                return (Date.parse(checkB) > Date.parse(checkA))
            })
        })
    },
    mounted() {
        this.getProjects()
        this.getTodos()
    },
    computed: {},
    watch: {},
    methods: {
        getProjects() {
            this.loadingProjects = true
            Requests.getProjectsList(null, 3).then(res => {
                if (res?.result) {
                    this.projects = res.result || []
                }
            }).finally(res => {
                this.loadingProjects=false
            })
        },
        getTodos() {
            this.loadingTodos = true
            Requests.getTodoList().then(res => {
                if (res?.result) {
                    this.todos = res.result || []
                }
            }).finally(res => {
                this.loadingTodos=false
            })
        },
        changeTodoStatus(todo) {
            if (!this.updatingTodos.includes(todo.id)) {
                this.updatingTodos.push(todo.id)
                Requests.saveTodo({id: todo.id, is_done: !todo.is_done}).then(res => {
                    if (res?.result) {
                        for (let i = 0; i < this.todos.length; i++) {
                            if (this.todos[i].id === todo.id) {
                                this.todos[i].is_done = res.result.is_done
                                break;
                            }
                        }
                    }
                }).finally(res => {
                    this.updatingTodos.splice(this.updatingTodos.indexOf(todo.id), 1)
                })
            }
        },
        checkTodoStatus(date) {
            return date === null || Date.parse(date) >= Date.now()
        },
        openTodoModal(todo = null) {
            this.givenRecord = todo
            this.$nextTick(res => {
                this.$refs.todoModal.modalOpen()
            })
        }
    }
}
</script>

<style scoped>
</style>
