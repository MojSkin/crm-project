<template>
    <AdminLayoutComponent>
        <template #title>نقش‌های کاربری</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newRole" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>نقش جدید</span>
                    </button>
                    <button @click="refreshTable" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست فرم‌ها">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                    <input class="input ml-auto w-auto" style="height: auto" v-model="filter" type="search" placeholder="جستجو کنید..." v-if="!newRec && !editing">
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'p-0': (!newRec && !editing), 'p-5': newRec || editing}" v-if="!newRec && !editing">
            <b-table
                :headers="headers"
                :data="roles"
                :is-busy="loading"
                table-header-class="bg-light"
                :current-page="currentPage"
                :per-page="perPage"
                :search-field="filter"
                striped
                hoverable
                ref="dataTable"
                key="formsDataTable"
            >
                <template #index="data">
                    {{ ((currentPage - 1) * perPage) + data.item.index + 1 }}
                </template>
                <template #default_route="data">
                    <bdi>{{ data.value }}</bdi>
                </template>
                <template #active="data">
                    <i class="fal" :class="data.value?'fa-check text-success':'fa-times text-danger'"></i>
                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger" @click="deleteItem(data.item.id)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
        </b-card>
        <div class="columns is-multiline" v-else>
            <div class="column is-12">
                <b-card no-header class="overflow-visible">
                    <div class="columns is-multiline">
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label="عنوان نقش کاربری <i class='far fa-circle has-text-danger ml-2'></i>"
                                placeholder="عنوان نقش کاربری"
                                :is-error="validator?.userRoleTitle?.$errors?.length"
                                :error-message="validator?.userRoleTitle?.$errors[0]?.$message || ''"
                                v-model="userRoleTitle"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label="مسیر پیش‌فرض پس از ورود <i class='far fa-circle has-text-danger ml-2'></i>"
                                hint="کاربر پس از ورود موفقیت‌آمیز به حساب کاربری خود، به این نشانی منتقل خواهد شد."
                                description="حتما باید با '/' شروع شود و نیازی به '/' پایانی نیست."
                                placeholder="/admin"
                                :is-error="validator?.userRoleDefaultRoute?.$errors?.length"
                                :error-message="validator?.userRoleDefaultRoute?.$errors[0]?.$message || ''"
                                class="is-ltr"
                                v-model="userRoleDefaultRoute"
                            ></b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label="دسترسی به پنل ادمین"
                                >
                                <template #input>
                                    <b-switch
                                        v-model="userRoleIsAdmin"
                                        :checked="userRoleIsAdmin == true"
                                        @change="addRemovePermission('isAdmin')"
                                    >
                                        <span class="has-text-success" v-if="userRoleIsAdmin">دارد</span>
                                        <span class="has-text-danger" v-else>ندارد</span>
                                    </b-switch>
                                </template>
                            </b-input>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                            <b-input
                                label="دسترسی به پنل دانش‌آموز"
                                >
                                <template #input>
                                    <b-switch
                                        v-model="userRoleIsUser"
                                        :checked="userRoleIsUser == true"
                                        @change="addRemovePermission('isStudent')"
                                    >
                                        <span class="has-text-success" v-if="userRoleIsUser">دارد</span>
                                        <span class="has-text-danger" v-else>ندارد</span>
                                    </b-switch>
                                </template>
                            </b-input>
                        </div>
                        <div class="column is-12">
                            <b-accordion
                                :headers="accordionHeaders"
                                open="adminPanel"
                                is-exclusive
                            >
                                <template v-for="header in accordionHeaders" v-slot:[`content-`+header.id]>
                                    <b-card no-footer v-for="child in userPermissions[header.id].child" :class="child.group+'-wrapper'" class="no-margin-bottom">
                                        <template #header-right>
                                            <i class="fas fa-chevron-right fa-rotate-90 px-3 py-2 is-rounded mr-2 has-text-grey-light is-clickable" @click="openCloseCard(child.group)"></i>
                                            <span class="is-weight-700 has-text-primary">{{ child.title }}</span>
                                        </template>
                                        <template #header-left>
                                                <span class="w-100">
                                                    <button @click="checkAll(child.group)" class="button is-small is-success is-outlined is-light">انتخاب همه</button>
                                                    <button @click="unCheckAll(child.group)" class="button is-small is-info is-outlined is-light mx-2">هیچکدام</button>
                                                    <button @click="inverseCheck(child.group)" class="button is-small is-warning is-outlined is-light">انتخاب معکوس</button>
                                                </span>
                                        </template>
                                        <table class="table table-borderless table-condensed table-hover m-0">
                                            <thead>
                                                <tr>
                                                    <th class="px-2 py-4" style="width: 30px"></th>
                                                    <th class="px-2 py-1" style="min-width: 40%"></th>
                                                    <th class="px-2 py-1" v-for="header in child.permission_titles" :style="{textAlign:'center', width:'auto', maxWidth: '15%'}">
                                                        {{ header }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="child?.subMenu && child?.subMenu?.length">
                                                <tr v-for="item in child.subMenu" v-if="item?.dependency ? userRolePermissions.includes(item.dependency) : true">
                                                    <td class="px-2 py-4">
                                                        <div class="field">
                                                            <div class="control">
                                                                <label class="checkbox is-solid is-warning p-0">
                                                                    <input type="checkbox"
                                                                           @change="checkUnCheckSingleGroup(child.group, item.group)"
                                                                           :checked="groupStateCheck(child.group, item.group)"
                                                                    >
                                                                    <span class="mx-0 py-0"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 py-4">{{ item.title }}</td>
                                                    <td class="px-2 py-4 has-text-centered"
                                                        v-for="permission in item.permissions">
                                                        <div class="field">
                                                            <div class="control">
                                                                <label class="checkbox is-outlined p-0"
                                                                       :class="[permission?.disabled ? 'is-disabled' : 'is-info', (permission.disabled && permission.default) || (!permission.disabled && !permission.default) ? 'is-outlined' : 'is-solid']"
                                                                >
                                                                    <input type="checkbox"
                                                                           @change="addRemovePermission(permission.id+(item.group[0].toUpperCase())+(item.group.substr(1)))"
                                                                           :name="permission.id+(item.group[0].toUpperCase())+(item.group.substr(1))"
                                                                           :checked="permission.default || userRolePermissions.includes(permission.id+(item.group[0].toUpperCase())+(item.group.substr(1)))"
                                                                           :disabled="permission.disabled"
                                                                    >
                                                                    <span class="mx-0 py-0"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                            <tr v-if="child.dependency?userRolePermissions.includes(child.dependency):true">
                                                <td class="px-2 py-4">
                                                </td>
                                                <td class="px-2 py-4">{{ child.title }}</td>
                                                <td class="px-2 py-4 has-text-centered"
                                                    v-for="permission in child.permissions">
                                                    <div class="field">
                                                        <div class="control">
                                                            <label class="checkbox is-outlined p-0"
                                                                   :class="[permission?.disabled ? (permission.default || userRolePermissions.includes(permission.id+(child.group[0].toUpperCase())+(child.group.substr(1))) ? 'is-info is-disabled' : 'is-dark') : 'is-info', (permission.disabled && permission.default) || (!permission.disabled && !permission.default) ? 'is-outlined' : 'is-solid']"
                                                            >
                                                                <input type="checkbox"
                                                                       @change="addRemovePermission(permission.id+(child.group[0].toUpperCase())+(child.group.substr(1)))"
                                                                       :name="permission.id+(child.group[0].toUpperCase())+(child.group.substr(1))"
                                                                       :checked="permission.default || userRolePermissions.includes(permission.id+(child.group[0].toUpperCase())+(child.group.substr(1)))"
                                                                       :disabled="permission.disabled"
                                                                >
                                                                <span class="mx-0 py-0"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </b-card>
                                </template>
                            </b-accordion>
                        </div>
                    </div>
                    <template #footer-left>
                        <button class="button is-success mr-2" @click="saveRole()" :disabled="loading">
                            <i class="fal fa-save" v-if="!loading"></i>
                            <i class="fal fa-spinner-third fa-spin" v-else></i>
                            <span class="ml-2">ذخیره نقش کاربری</span>
                        </button>
                        <button class="button" @click="cancelForm">انصراف</button>
                    </template>
                </b-card>
            </div>
        </div>
    </AdminLayoutComponent>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import { Crypton, Encrypter } from 'laravel-crypton'
export default {
    name: "AdminRolesComponent",
    props: {},
    setup () {
        return { validator: useVuelidate() }
    },
    data() {
        return {
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            saving: false,
            newRec: false,
            editing: false,
            editingRole: {},
            errors: [],
            userRoleId: null,
            userRoleTitle: '',
            userRoleDefaultRoute: '/',
            userRoleIsAdmin: false,
            userRoleIsUser: false,
            roles: [],
            userPermissions: [],
            userRolePermissions: [],
            availablePermissions: [],
            accordionHeaders: [],
            perms: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'title', title: 'عنوان نقش', sortable: true},
                {id: 'default_route', title: 'مسیر پیش فرض'},
                {id: 'active', title: 'وضعیت', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 50px;', tdStyle: ''},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 100px;', tdStyle: ''},
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
        }
    },
    validations () {
        return {
            userRoleTitle: {
                required: {
                    $validator: (val) => {
                        return (val?.length != 0)
                    },
                    $message: 'ارائه عنوان نقش الزامی است'
                },
                min: {
                    $validator: (val) => {
                        return (val?.length >= 4)
                    },
                    $message: 'عنوان نقش باید حداقل 4 کاراکتر باشد'
                },
            },
            userRoleDefaultRoute: {
                min: {
                    $validator: (val) => {
                        return (val?.length && val?.length >= 1 && (val[0] || '') === '/' && (val?.length > 1 ? val[val.length-1] !== '/' : true))
                    },
                    $message: 'مسیر پیش‌فرض باید یک مسیر استاندارد باشد'
                },
            },
        }
    },
    created() {
        this.refreshTable()
    },
    mounted() {
    },
    computed: {},
    watch: {
    },
    methods: {
        refreshTable() {
            if (!this.loading) {
                this.loading = true
                Requests.getUserRoles().then(res => {
                    let crypt = new Encrypter(import.meta.env.VITE_APP_KEY);
                    this.roles = res?.result?.roles || []
                    this.userPermissions = crypt.decrypt(res?.result?.permissions)
                    for (let i = 0; i < this.roles.length; i++) {
                        this.roles[i].permissions = crypt.decrypt(this.roles[i].permissions)
                    }
                    this.accordionHeaders = []
                    for (const index in this.userPermissions) {
                        const children = []
                        for (const childrenIndex in this.userPermissions[index]?.child) {
                            children.push({
                                id: this.userPermissions[index]?.child[childrenIndex].group,
                                title: this.userPermissions[index]?.child[childrenIndex].title,
                            })
                        }

                        this.accordionHeaders.push({
                            id: this.userPermissions[index]?.section,
                            title: this.userPermissions[index]?.title,
                            children: children
                        })
                    }
                }).finally(res => {
                    this.loading = false
                    this.cancelForm()
                })
            }
        },
        getPermissionHeaders(header, subHeader) {
            for (let i = 0; i < this.userPermissions.length; i++) {
                if (this.userPermissions[i].section === header) {
                    for (let j = 0; j < this.userPermissions[i].child.length; j++) {
                        if (this.userPermissions[i].child[j].group === subHeader) {
                            return this.userPermissions[i].child[j]
                        }
                    }
                }
            }
            return {}
        },
        handlePerms(e) {
        },
        newRole() {
            this.loading =  false
            this.saving =  false
            this.newRec =  true
            this.editing =  false
            this.editingRole =  {}
            this.errors =  []
            this.userRoleId =  null
            this.userRoleTitle =  ''
            this.userRoleDefaultRoute =  '/'
            this.userRoleIsAdmin =  false
            this.userRoleIsUser =  false
        },
        cancelForm() {
            this.newRole()
            this.editing = false
            this.newRec = false
            this.userRolePermissions = []
            this.availablePermissions = []
            for (const i in this.userPermissions) {
                let section = this.userPermissions[i]
                for (let j = 0; j < section.child.length; j++) {
                    let child = section.child[j]
                    if (child?.subMenu) {
                        for (let k = 0; k < child.subMenu.length; k++) {
                            let item = child.subMenu[k]
                            for (let l = 0; l < item.permissions.length; l++) {
                                let permission = item.permissions[l]
                                this.availablePermissions.push({
                                    group: child.group,
                                    permission: permission.id + (item.group[0].toUpperCase()) + (item.group.substr(1)),
                                    disabled: permission.disabled
                                })
                                if (permission.default) {
                                    this.userRolePermissions.push(permission.id + (item.group[0].toUpperCase()) + (item.group.substr(1)))
                                }
                            }
                        }
                    } else {
                        for (let k = 0; k < child.permissions.length; k++) {
                            let permission = child.permissions[k]
                            this.availablePermissions.push({
                                group: child.group,
                                permission: permission.id + (child.group[0].toUpperCase()) + (child.group.substr(1)),
                                disabled: permission.disabled
                            })
                            if (permission.default) {
                                this.userRolePermissions.push(permission.id + (child.group[0].toUpperCase()) + (child.group.substr(1)))
                            }
                        }
                    }
                }
            }
            this.validator.$reset()
        },
        checkAll(group) {
            for (let i = 0; i < this.availablePermissions.length; i++) {
                let p = this.availablePermissions[i]
                if (!p.disabled) {
                    if (p.group == group) {
                        this.addPermission(p.permission)
                    }
                }
            }
        },
        unCheckAll(group) {
            for (let i = 0; i < this.availablePermissions.length; i++) {
                let p = this.availablePermissions[i]
                if (!p.disabled) {
                    if (p.group == group) {
                        this.removePermission(p.permission)
                    }
                }
            }
        },
        inverseCheck(group) {
            for (let i = 0; i < this.availablePermissions.length; i++) {
                let p = this.availablePermissions[i]
                if (!p.disabled) {
                    if (p.group.substr(0, group.length) == group) {
                        this.addRemovePermission(p.permission)
                    }
                }
            }
        },
        checkUnCheckSingleGroup(groupName, item) {
            let perms = []
            for (let i = 0; i < this.availablePermissions.length; i++) {
                const p = this.availablePermissions[i]
                if (!p.disabled) {
                    const pr = p.permission.toUpperCase()
                    const it = item.toUpperCase()
                    if (p.group === groupName && pr.includes(it)) {
                        perms.push(p.permission)
                    }
                }
            }
            if (this.groupStateCheck(groupName, item)) {
                for (let i = 0; i < perms.length; i++) {
                    this.userRolePermissions.splice(this.userRolePermissions.indexOf(perms[i]), 1)
                }
            } else {
                for (let i = 0; i < perms.length; i++) {
                    this.userRolePermissions.push(perms[i])
                }
            }
        },
        groupStateCheck(groupName, item) {
            let allExists = true
            for (let i = 0; i < this.availablePermissions.length; i++) {
                const p = this.availablePermissions[i]
                if (!p.disabled) {
                    const pr = p.permission.toUpperCase()
                    const it = item.toUpperCase()
                    if (p.group === groupName && pr.includes(it)) {
                        if (!this.userRolePermissions.includes(p.permission)) {
                            allExists = false
                            break;
                        }
                    }
                }
            }
            return allExists
        },
        addRemovePermission(permission) {
            if (this.userRolePermissions.includes(permission)) {
                this.userRolePermissions.splice(this.userRolePermissions.indexOf(permission), 1)
            } else {
                this.userRolePermissions.push(permission)
            }
        },
        removePermission(permission) {
            if (this.userRolePermissions.includes(permission)) {
                this.userRolePermissions.splice(this.userRolePermissions.indexOf(permission))
            }
        },
        addPermission(permission) {
            if (!this.userRolePermissions.includes(permission)) {
                this.userRolePermissions.push(permission)
            }
        },
        openCloseCard(groupName) {
            let cardBody = $('.'+groupName+'-wrapper .card-body')
            let cardToggle = $('.'+groupName+'-wrapper .card-head i.fas:first-child')
            let cardHead = $('.'+groupName+'-wrapper .card-head .left')
            cardBody.toggleClass('closed');
            cardToggle.toggleClass('fa-rotate-90')
            cardBody.slideToggle(300);
            cardHead.fadeToggle(200);
        },
        saveRole() {
            if (!this.loading) {
                this.validator.$touch();
                if (!this.validator.$invalid) {
                    this.loading = true
                    let crypt = new Encrypter(import.meta.env.VITE_APP_KEY);
                    const formData = {
                        id: this.editing === true ? this.userRoleId : -1,
                        title: this.userRoleTitle,
                        default_route: this.userRoleDefaultRoute,
                        active: this.editing ? this.userRole?.active == true : true,
                        permissions: crypt.encrypt(this.userRolePermissions)
                    }
                    let perm = false
                    let message = ''
                    if (this.editing) {
                        if (this.permission.uceSystemUserRoles) {
                            perm = true
                        } else {
                            message = 'شما مجوز ویرایش در این قسمت را ندارید!'
                        }
                    } else {
                        if (this.permission.ucaSystemUserRoles) {
                            perm = true
                        } else {
                            message = 'شما مجوز افزودن در این قسمت را ندارید!'
                        }
                    }
                    // Delete line below when PERMISSIONS are ready
                    perm = true
                    // Delete line above when PERMISSIONS are ready
                    if (perm) {
                        Requests.saveUserRole(formData).then(res => {
                            if (res?.status) {
                                let crypt = new Encrypter(import.meta.env.VITE_APP_KEY);
                                let role = res.result
                                role.permissions = crypt.decrypt(role.permissions)
                                if (this.editing) {
                                    for (let i = 0; i < this.roles.length; i++) {
                                        if (this.roles[i].id == role.id) {
                                            this.roles[i] = role
                                            break
                                        }
                                    }
                                } else {
                                    this.roles.push(role)
                                }

                                this.totalRows = this.roles.length
                                this.$helpers.notify(res.message)
                                // this.$refs.dataTable.update()
                                this.cancelForm()
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره نقش کاربری', {type: 'error'})
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای غیرمنتظره', err?.response?.message || 'بروز خطای غیرمنتظره هنگام ذخیره نقش کاربری', {type: 'error'})
                        }).finally(res => {
                            this.loading = false
                        })
                    } else {
                        this.$helpers.notify(message, {type: 'error', icon: 'fa-times'})
                    }
                }

            }
        },
        editItem(role) {
            const role_id = role.id
            // if (this.permission.uceSystemUserRoles) {
                let index = -1
                for (let i = 0; i < this.roles.length; i++) {
                    if (this.roles[i].id == role_id) {
                        index = i
                        break
                    }
                }
                this.cancelForm()
                if (index >= 0) {
                    this.editing = true
                    this.userRole = this.roles[index]
                    this.userRoleId = this.userRole.id
                    this.userRoleTitle = this.userRole.title
                    this.userRoleDefaultRoute = this.userRole.default_route
                    for (let i = 0; i < this.roles[index].permissions.length; i++) {
                        this.userRolePermissions.push(this.roles[index].permissions[i].action)
                    }
                    this.userRoleIsAdmin = this.userRolePermissions.includes('isAdmin')
                    this.userRoleIsUser = this.userRolePermissions.includes('isStudent')
                } else {
                    this.$helpers.notify('خطای غیرمنتظره!', 'نقش کاربری مورد نظر یافت نشد...', { type: 'error' })
                }
            // } else {
            //     this.$helpers.notify('اخطار', 'شما مجوز ویرایش در این قسمت را ندارید!', {
            //         type: 'error',
            //     });
            // }
        }
    }
}
</script>

<style scoped>
th, td {
    vertical-align: middle;
}
.card-head i.fas:first-child:hover {
    color: #4a4a4a !important
}
.card-head i.fas:first-child {
    transition: all 200ms ease-in-out;
}
</style>
