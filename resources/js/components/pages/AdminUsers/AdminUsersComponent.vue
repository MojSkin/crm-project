<template>
    <AdminLayoutComponent>
        <template #title>کاربران</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newUser" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>کاربر جدید</span>
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
                :data="users"
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
                <template #details="data">
                    <span class="is-flex align-items-center">
                        <span class="h-avatar is-medium mr-2">
                            <img class="avatar" :src="data.value?.avatar" v-if="data?.value?.avatar" alt="">
                            <span class="avatar is-fake has-text-grey-light" v-else>{{ $helpers.makeAbbr(data.item.display_name) }}</span>
                        </span>
                        <span>{{ data.item.display_name }}</span>
                    </span>
                </template>
                <template #role="data">
                    {{ data.value?.title || 'فاقد نقش'}}
                </template>
                <template #action="data">
                    <button class="btn btn-outline text-primary" @click="editItem(data.item)">
                        <i class="fal fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-outline text-danger" @click="deleteItem(data.item)">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                </template>
            </b-table>
        </b-card>
        <div class="columns is-multiline" v-else>
            <div class="column is-9-desktop is-9-widescreen is-12-mobile is-12-tablet">
                <b-card no-header class="overflow-visible">
                    <div class="columns is-multiline">
                        <div class="column is-12-mobile is-6-tablet is-6">
                            <b-input
                                label="نام <i class='far fa-circle has-text-danger ml-2'></i>"
                                placeholder="نام کاربر"
                                :is-error="validator?.form?.fName?.$errors?.length"
                                :error-message="validator?.form?.fName?.$errors[0]?.$message || ''"
                                v-model="form.fName"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-6">
                            <b-input
                                label="نام خانوادگی <i class='far fa-circle has-text-danger ml-2'></i>"
                                placeholder="نام خانوادکی کاربر"
                                :is-error="validator?.form?.lName?.$errors?.length"
                                :error-message="validator?.form?.lName?.$errors[0]?.$message || ''"
                                v-model="form.lName"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-input
                                label="نام کاربری <i class='far fa-circle has-text-danger ml-2'></i>"
                                placeholder="نام کاربری"
                                description="فقط شامل حروف انگلیسی، اعداد، _ و ."
                                :is-error="validator?.form?.username?.$errors?.length"
                                :error-message="validator?.form?.username?.$errors[0]?.$message || ''"
                                v-model="form.username"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-input
                                label="پست الکترونیک <i class='far fa-circle has-text-danger ml-2'></i>"
                                placeholder="پست الکترونیک"
                                :is-error="validator?.form?.email?.$errors?.length"
                                :error-message="validator?.form?.email?.$errors[0]?.$message || ''"
                                v-model="form.email"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-input
                                label="تلفن همراه <i class='far fa-circle has-text-danger ml-2'></i>"
                                :is-error="validator?.form?.mobile?.$errors?.length"
                                :error-message="validator?.form?.mobile?.$errors[0]?.$message || ''"
                                placeholder="تلفن همراه"
                                v-model="form.mobile"
                            ></b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-select
                                label="نقش کاربری"
                                :options="roles"
                                v-model="form.user_role_id"
                            ></b-select>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
                            <b-input
                                :label="'کلمه عبور '+(newRec ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                                has-icon
                                placeholder="کلمه عبور"
                                v-model="form.password"
                                :is-error="validator?.form?.password?.$errors?.length"
                                :error-message="validator?.form?.password?.$errors[0]?.$message || ''"
                                :type="showPassword ? 'text' : 'password'"
                                @input="isSafePassword=$helpers.isSafePassword(form.password)"
                                autocomplete="off"
                            >
                                <template #icon>
                                    <i @click="showPassword = !showPassword" class="is-clickable fa" :class="{'fa-eye-slash': !showPassword, 'fa-eye': showPassword, 'has-text-primary': showPassword , 'has-text-grey-light': !showPassword }"></i>
                                </template>
                                <template #description>
                                    <ul class="has-text-grey-light mt-2">
                                        <li :class="{'has-text-success': isSafePassword.ln}"><i class="fal fa-check-circle mx-1"></i>حداقل 6 کاراکتر</li>
                                        <li :class="{'has-text-success': isSafePassword.lc}"><i class="fal fa-check-circle mx-1"></i>حداکثر یک حرف بزرگ</li>
                                        <li :class="{'has-text-success': isSafePassword.uc}"><i class="fal fa-check-circle mx-1"></i>حداقل یک حرف کوچک</li>
                                        <li :class="{'has-text-success': isSafePassword.nm}"><i class="fal fa-check-circle mx-1"></i>حداقل یک عدد</li>
                                        <li :class="{'has-text-success': isSafePassword.sc}"><i class="fal fa-check-circle mx-1"></i>حداقل حرف ویژه: !@#$%^&*()</li>
                                    </ul>
                                </template>
                            </b-input>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen" v-if="!showPassword">
                            <b-input
                                :label="'تکرار کلمه عبور '+(form.password && form.password.length ? `<i class='far fa-circle has-text-danger ml-2'></i>` : '')"
                                placeholder="تکرار کلمه عبور"
                                v-model="form.confirm_password"
                                :is-error="validator?.form?.confirm_password?.$errors?.length"
                                :error-message="validator?.form?.confirm_password?.$errors[0]?.$message || ''"
                                type="password"
                                autocomplete="off"
                            >
                            </b-input>
                        </div>
                    </div>
                    <template #footer-left>
                        <button class="button is-success mr-2" @click="saveUser()" :disabled="loading">
                            <i class="fal fa-save" v-if="!loading"></i>
                            <i class="fal fa-spinner-third fa-spin" v-else></i>
                            <span class="ml-2">ذخیره کاربر</span>
                        </button>
                        <button class="button" @click="cancelForm">انصراف</button>
                    </template>
                </b-card>
            </div>
            <div class="column is-3-desktop is-3-widescreen is-12-mobile is-12-tablet">
                <b-card no-footer header-class="has-background-light">
                    <template #header>
                        <h3 class="my-3">تصویر پروفایل</h3>
                    </template>
                    <div class="is-flex align-items-center is-flex-direction-column">
                        <input class="file-input" type="file" accept="image/jpeg, image/bmp" @change="changeAvatarFile" v-show="false" ref="avatarFile">
                        <span class="h-avatar is-profile my-6 is-clickable" @click="$refs.avatarFile.click()">
                            <img class="avatar" :src="form.avatar" v-if="form?.avatar" alt="">
                            <span class="avatar is-fake has-text-grey-light sahel is-weight-700" style="font-size: 1.45rem;" v-else>بدون تصویر</span>
                            <span class="avatar-overlay"></span>
                            <button @click.stop="avatar=null; form.avatar=null" v-if="form?.avatar" class="badge button is-rounded is-warning d-block px-0"><i class="fal fa-times"></i></button>
                        </span>
                    </div>
                </b-card>
            </div>
        </div>
    </AdminLayoutComponent>
    <b-modal
        ref="avatarCropper"
        :is-card="false"
    >
        <b-card no-header class="no-margin no-padding" body-class="no-padding no-margin">
            <div class="w-100" style="height: 300px; max-height: 300px; min-height: 300px;">
                <Cropper
                    :restrictions="pixelsRestriction"
                    :minHeight="300"
                    :minWidth="300"
                    classname="shadow"
                    :stencil-props="{
                aspectRatio: 1
            }"
                    :src="avatar"
                    ref="cropper"
                />
            </div>
            <template #footer-right>
                <span class="hint--light hint--rounded hint--top my-2 mx-5" data-hint="بزرگنمایی"><i @click="$refs.cropper.zoom(1.2)" class="fal fa-search-plus"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="کوچکنمایی"><i @click="$refs.cropper.zoom(0.8)" class="fal fa-search-minus"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="چرخش 90 درجه به چپ"><i @click="$refs.cropper.rotate(-90)" class="fal fa-undo-alt"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="چرخش 90 درجه به راست"><i @click="$refs.cropper.rotate(90)" class="fal fa-redo-alt"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="آیینه‌ای افقی"><i @click="$refs.cropper.flip(180,0)" class="fal fa-exchange"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="آیینه‌ای عمودی"><i @click="$refs.cropper.flip(0,180)" class="fal fa-exchange fa-rotate-90"></i></span>
            </template>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="اعمال تغییرات"><i @click="changeAvatar" class="fas fa-check has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="بستن پنجره"><i @click="$refs.avatarCropper.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
</template>

<script>
import Form from '/resources/js/form'
import { Cropper, CircleStencil } from 'vue-advanced-cropper'
import { useVuelidate } from '@vuelidate/core'
export default {
    name: "AdminUsersComponent",
    inject: ['app_key'],
    props: {},
    components: {
        Cropper,
        CircleStencil
    },
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
            base_url: window.location.origin,
            loading: false,
            saving: false,
            newRec: false,
            editing: false,
            editingUser: {},
            errors: [],
            form: [],
            users: [],
            roles: [],
            headers: [
                {id: 'index', title: '#', thStyle: 'width: 50px'},
                {id: 'details', title: 'نام کاربر', sortable: true},
                {id: 'username', title: 'نام کاربری', sortable: true},
                {id: 'email', title: 'ایمیل', sortable: true},
                {id: 'mobile', title: 'تلفن همراه', sortable: true},
                {id: 'role', title: 'نقش کاربری'},
                {id: 'action', title: 'عملیات', thClass: 'text-center', tdClass: 'text-center', thStyle: 'width: 100px;', tdStyle: ''},
            ],
            showPassword: false,
            isSafePassword: false,
            avatar: null,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
        }
    },
    validations () {
        return {
            form: {
                fName: {
                    required: {
                        $validator: (val) => {
                            return (val && val?.length != 0)
                        },
                        $message: 'ارائه نام کاربر الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 2)
                        },
                        $message: 'نام کاربر باید حداقل 2 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 30)
                        },
                        $message: 'نام کاربر باید حداکثر 30 کاراکتر باشد'
                    },
                },
                lName: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ارائه نام خانوادگی کاربر الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 3)
                        },
                        $message: 'نام خانوادگی کاربر باید حداقل 3 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 50)
                        },
                        $message: 'نام خانوادگی کاربر باید حداکثر 50 کاراکتر باشد'
                    },
                },
                username: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'ارائه نام کاربری الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'نام کاربری باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 25)
                        },
                        $message: 'نام کاربری باید حداکثر 25 کاراکتر باشد'
                    },
                    validUsername: {
                        $validator: (val) => {
                            return this.$helpers.username(val)
                        },
                        $message: 'قالب نام کاربری معتبر نیست'
                    },
                },
                email: {
                    requiredIf: {
                        $validator: (val) => {
                            return (val?.length != 0 || this?.form?.mobile?.length != 0)
                        },
                        $message: 'یکی از گزینه‌های «پست الکترونیک» یا «تلفن همراه» اجباری است'
                    },
                    validEmail: {
                        $validator: (val) => {
                            return val?.length == 0 || this.$helpers.isEmail(val)
                        },
                        $message: 'قالب پست الکترونیک معتبر نیست'
                    },
                },
                mobile: {
                    requiredIf: {
                        $validator: (val) => {
                            return (val?.length != 0 || this?.form?.email?.length != 0)
                        },
                        $message: 'یکی از گزینه‌های «پست الکترونیک» یا «تلفن همراه» اجباری است'
                    },
                    validEmail: {
                        $validator: (val) => {
                            return val?.length == 0 || this.$helpers.isMobile(val)
                        },
                        $message: 'شماره تلفن همراه معتبر نیست'
                    },
                },
                password: {
                    requiredIf: {
                        $validator: (val) => {
                            return (this.editing || val?.length != 0)
                        },
                        $message: 'کلمه عبور اجباری است'
                    },
                    validPassword: {
                        $validator: (val) => {
                            if (this.newRec || val?.length) {
                                const sp = this.$helpers.isSafePassword(val)
                                return sp?.score === 5
                            }
                            return true
                        },
                        $message: 'میزان امنیت کلمه عبور قابل قبول نیست'
                    },
                },
                confirm_password: {
                    requiredIf: {
                        $validator: (val) => {
                            return (this.showPassword || this.form.password === val)
                        },
                        $message: 'کلمه عبور با تایید آن مطابقت ندارد'
                    },
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
                Requests.getUsersList().then(res => {
                    if (res?.status) {
                        this.users = res?.result?.users ?? [];
                        this.roles = res?.result?.roles ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست کاربران', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست کاربران', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        setErrors(e) {
            console.log(e)
        },
        newUser() {
            this.form = {
                fName: '',
                lName: '',
                username: '',
                email: '',
                mobile: '',
                user_role_id: null,
                avatar: '',
                password: '',
                confirm_password: '',
            }
            this.errors       = []
            this.newRec       = true
            this.editing      = false
            this.showPassword = false
        },
        editItem(user) {
            this.editingUser = user
            this.newUser()
            this.newRec  = false
            this.editing = true
            this.avatar            = user?.details?.avatar ? this.base_url+'/storage/uploads/user_avatars/'+user?.details?.avatar : null
            this.form.fName        = user?.details?.fName
            this.form.lName        = user?.details?.lName
            this.form.username     = user.username
            this.form.email        = user.email
            this.form.mobile       = user.mobile
            this.form.user_role_id = user.user_role_id
            if (this.avatar) {
                this.$helpers.toDataURL(this.avatar, res => {
                    this.form.avatar = res
                })
            }
        },
        changeAvatar() {
            const c = this.$refs.cropper.getResult().canvas
            this.form.avatar = c.toDataURL()
        },
        changeAvatarFile(e) {
            let reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = () => {
                this.avatar = reader.result
                this.$refs.avatarCropper.modalOpen()
            }
        },
        pixelsRestriction({minWidth, minHeight, maxWidth, maxHeight, imageWidth, imageHeight}) {
            return {
                minWidth: minWidth,
                minHeight: minHeight,
                maxWidth: maxWidth,
                maxHeight: maxHeight,
            }
        },
        saveUser() {
            this.validator.form.$touch();
            if (!this.validator.form.$invalid) {
                this.loading = true
                const form = {
                    ...this.form
                }
                if (this.editing) {
                    form['id'] = this.editingUser.id
                }
                Requests.saveUser(form).then(res => {
                    if (res?.status) {
                        if (this.editing) {
                            for (let i = 0; i < this.users.length; i++) {
                                if (this.users[i].id == res.result.id) {
                                    this.users[i] = res.result
                                    break
                                }
                            }
                            this.cancelForm()
                        } else {
                            this.users.push(res.result)
                        }
                        this.$helpers.notify(res?.message || 'اطلاعات کاربر با موفقیت ذخیره شد')
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره اطلاعات کاربر', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطا', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام ذخیره اطلاعات کاربر', { type: 'error' })
                }).finally(err => {
                    this.loading = false
                })
            }
        },
        cancelForm() {
            this.validator.form.$reset();
            this.form        = {}
            this.editingUser = {}
            this.newRec      =false
            this.editing     = false
        },
    }
}
</script>

<style scoped>
.h-avatar.is-profile,
.h-avatar.is-profile .avatar {
    width: 100% !important;
    max-width: 300px !important;;
    height: auto !important;;
    aspect-ratio: 1 !important;;
}
.h-avatar.is-profile .avatar-overlay{
    position: absolute;
    inset: 0;
    border-radius: 50%;
    overflow: hidden;
    background-color: rgba(215, 215, 215, 0.35);
    transition: all 300ms;
    opacity: 0.35;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    background-image: url(/resources/assets/images/png/no-image.png);
    display: flex;
    align-items: center;
    justify-content: center;
}
.h-avatar.is-profile:hover .avatar-overlay{
    opacity: 0.8;
}
.h-avatar.is-profile .badge {
    position: absolute;
    bottom: 8%;
    left: 8%;
    height: 30px;
    width: 30px;
    border: 1px solid #fff;
}
</style>
