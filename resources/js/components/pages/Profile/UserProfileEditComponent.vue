<template>
    <Component :is="layout">
        <template #title>ویرایش پروفایل</template>
        <div class="account-wrapper">
            <div class="columns">
                <div class="column is-4">
                    <div class="account-box is-navigation">
                        <div class="media-flex-center">
                            <div class="h-avatar is-xl">
                                <input class="file-input" type="file" accept="image/jpeg, image/bmp" @change="changeAvatarFile" v-show="false" ref="avatarFile">
                                <img class="avatar" :src="userProfile?.avatar" v-if="userProfile?.avatar" alt="">
                                <span class="avatar is-fake has-text-grey-light is-weight-700" style="font-size: 2rem;" v-else>{{ $helpers.makeAbbr(userProfile?.display_name || '-') }}</span>
                                <button class="badge button is-rounded is-light border-1 d-block px-0" @click="$refs.avatarFile.click()">
                                    <i class="fal fa-camera"></i>
                                </button>
                                <button class="badge button is-rounded is-warning border-1 d-block px-0" @click="userProfile.avatar=null;" v-if="userProfile.avatar">
                                    <i class="fal fa-times"></i>
                                </button>
                            </div>
                            <div class="flex-meta">
                                <span>{{ userProfile?.display_name }}</span>
                                <span class="subtitle">{{ userProfile?.role || 'فاقد نقش کاربری' }}</span>
                            </div>
                        </div>

                        <div class="account-menu">
                            <a v-for="tab in tabs" @click="active_tab = tab.id" class="account-menu-item" :class="{'is-active':active_tab === tab.id}">
                                <i :class="tab.icon"></i>
                                <span v-html="tab.value"/>
                                <transition duration="350" mode="out-in" type="animation" name="fade">
                                    <span class="end" v-show="active_tab === tab.id">
                                        <i class="fas fa-arrow-left" ></i>
                                    </span>
                                </transition>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <div class="account-box is-form is-headerless">
                        <div class="form-body">
                            <div class="fieldset" id="userDetailsSection">
                                <div class="columns is-multiline" v-if="active_tab==='general'">
                                    <div class="column is-12 fieldset-heading">
                                        <h3>اطلاعات شخصی</h3>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="نام" placeholder="نام"
                                                 v-model="userProfile.fName"
                                                 :is-error="validator?.userProfile?.fName?.$errors?.length"
                                                 :error-message="validator?.userProfile?.fName?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="نام خانوادگی" placeholder="نام خانوادگی"
                                                 v-model="userProfile.lName"
                                                 :is-error="validator?.userProfile?.lName?.$errors?.length"
                                                 :error-message="validator?.userProfile?.lName?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12">
                                        <b-input label="شعار / توضیح مختصر" placeholder="شعار / توضیح مختصر"
                                                 v-model="userProfile.slogan"
                                                 :is-error="validator?.userProfile?.slogan?.$errors?.length"
                                                 :error-message="validator?.userProfile?.slogan?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12">
                                        <b-input
                                            label="درباره"
                                            :is-error="validator?.userProfile?.about?.$errors?.length"
                                            :error-message="validator?.userProfile?.about?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <textarea
                                                    v-model="userProfile.about"
                                                    rows="3"
                                                    placeholder="کدهای HTML قابل قبول است"
                                                    class="textarea"
                                                    :class="{ 'is-error': validator?.userProfile?.about?.$errors?.length}"
                                                />
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 fieldset-heading">
                                        <div class="is-divider"></div>
                                        <h3>اطلاعات تماس</h3>
                                    </div>
                                    <div class="column is-12">
                                        <b-input label="نام کاربری" placeholder="نام کاربری"
                                                 class="is-ltr"
                                                 v-model="userProfile.username"
                                                 :is-error="validator?.userProfile?.username?.$errors?.length"
                                                 :error-message="validator?.userProfile?.username?.$errors[0]?.$message || ''"
                                                 :disabled="!userProfile.username_is_changed"
                                                 is-expanded
                                                 has-icon
                                                 input-icon="fal fa-user-alt"
                                        >
                                            <template #expanded-start v-if="!userProfile.username_is_changed">
                                                <button class="button is-warning" @click="userProfile.username_is_changed=true">
                                                    <span>ویرایش</span>
                                                </button>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12">
                                        <b-input label="پست الکترونیک" placeholder="پست الکترونیک"
                                                 class="is-ltr"
                                                 v-model="userProfile.email"
                                                 :is-error="validator?.userProfile?.email?.$errors?.length"
                                                 :error-message="validator?.userProfile?.email?.$errors[0]?.$message || ''"
                                                 :is-expanded="true"
                                                 :disabled="userProfile.email_is_verified===true"
                                                 has-icon
                                                 input-icon="fal fa-envelope-open"
                                        >
                                            <template #expanded-end v-if="typeof userProfile.email_is_verified==='boolean'">
                                                <button class="button" :class="userProfile.email_is_verified===true ? 'is-static has-background-success has-text-light' : 'is-warning'">
                                                    <span v-if="userProfile.email_is_verified">تایید شده</span>
                                                    <span v-else>ارسال تاییدیه</span>
                                                </button>
                                            </template>
                                            <template #expanded-start v-if="userProfile.email_is_verified===true">
                                                <button class="button is-warning" @click="userProfile.email_is_verified=1">
                                                    <span>ویرایش</span>
                                                </button>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12">
                                        <b-input label="تلفن همراه" placeholder="تلفن همراه"
                                                 class="is-ltr"
                                                 v-model="userProfile.mobile"
                                                 :is-error="validator?.userProfile?.mobile?.$errors?.length"
                                                 :error-message="validator?.userProfile?.mobile?.$errors[0]?.$message || ''"
                                                 :is-expanded="true"
                                                 :disabled="userProfile.mobile_is_verified===true"
                                                 has-icon
                                                 input-icon="fal fa-phone"
                                        >
                                            <template #expanded-end v-if="typeof userProfile.mobile_is_verified==='boolean'">
                                                <button class="button" :class="userProfile.mobile_is_verified===true ? 'is-static has-background-success has-text-light' : 'is-warning'">
                                                    <span v-if="userProfile.mobile_is_verified">تایید شده</span>
                                                    <span v-else>ارسال تاییدیه</span>
                                                </button>
                                            </template>
                                            <template #expanded-start v-if="userProfile.mobile_is_verified===true">
                                                <button class="button is-warning" @click="userProfile.mobile_is_verified=1">
                                                    <span>ویرایش</span>
                                                </button>
                                            </template>
                                        </b-input>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='social'">
                                    <div class="column is-8">
                                        <b-input label="عنوان شبکه اجتماعی" placeholder="عنوان شبکه اجتماعی"
                                                 v-model="social.title"
                                                 :is-error="validator?.social?.title?.$errors?.length"
                                                 :error-message="validator?.social?.title?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-4">
                                        <b-input label="انتخاب آیکون" placeholder="انتخاب آیکون"
                                                 :is-error="validator?.social?.icon?.$errors?.length"
                                                 :error-message="validator?.social?.icon?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <b-icon-picker
                                                    is-rounded
                                                    v-model="social.icon"
                                                ></b-icon-picker>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12">
                                        <b-input label="نشانی کامل پروفایل شبکه اجتماعی" placeholder="@نام کاربری"
                                                 v-model="social.url"
                                                 :is-error="validator?.userProfile?.slogan?.$errors?.length"
                                                 :error-message="validator?.userProfile?.slogan?.$errors[0]?.$message || ''"
                                                 class="is-ltr"
                                        ></b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center is-ltr detailed-section" v-for="(sn, index) in userProfile.socials">
                                        <span class="has-text-primary is-flex align-items-center hint--light hint--rounded hint--top" :data-hint="sn.title" style="font-size:22px; min-width: 30px"><i :class="sn.icon"></i></span>
                                        <span class="ml-auto">{{ sn.url }}</span>
                                        <span @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='education'">
                                    <div class="column is-12">
                                        <b-input label="مقطع تحصیلی"
                                                 :is-error="validator?.education?.title?.$errors?.length"
                                                 :error-message="validator?.education?.title?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <b-select
                                                    :options="educations_grade"
                                                    v-model="education.title"
                                                ></b-select>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="رشته تحصیلی" placeholder="رشته تحصیلی"
                                                 v-model="education.major"
                                                 :is-error="validator?.education?.major?.$errors?.length"
                                                 :error-message="validator?.education?.major?.$errors[0]?.$message || ''"
                                        >
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="شاخه" placeholder="شاخه"
                                                 v-model="education.branch"
                                                 :is-error="validator?.education?.branch?.$errors?.length"
                                                 :error-message="validator?.education?.branch?.$errors[0]?.$message || ''"
                                        >
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="محل اخذ مدرک" placeholder="محل اخذ مدرک"
                                                 v-model="education.university"
                                                 :is-error="validator?.education?.university?.$errors?.length"
                                                 :error-message="validator?.education?.university?.$errors[0]?.$message || ''"
                                        >
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="تاریخ اخذ مدرک" placeholder="تاریخ اخذ مدرک"
                                                 :is-error="validator?.education?.university?.$errors?.length"
                                                 :error-message="validator?.education?.university?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <date-picker
                                                    format="jMMMM jYYYY"
                                                    display-format="jMMMM jYYYY"
                                                    type="year-month"
                                                    view="year"
                                                    clearable
                                                    v-model="education.year"
                                                    auto-submit
                                                    input-class="is-ltr has-text-left input"
                                                ></date-picker>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center detailed-section" v-for="(ed, index) in userProfile.educations">
                                        <span class="ml-2">{{ ed.title }}</span>
                                        <span class="ml-2">{{ ed.major }} - {{ ed.branch }}</span>
                                        <span class="ml-2">{{ ed.university }} ({{ ed.year }})</span>
                                        <span class="ml-auto" @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='job'">
                                    <div class="column is-6">
                                        <b-input label="نام شرکت"
                                                 v-model="job.company"
                                                 :is-error="validator?.job?.company?.$errors?.length"
                                                 :error-message="validator?.job?.company?.$errors[0]?.$message || ''"
                                        >
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="عنوان شغلی" placeholder="عنوان شغلی"
                                                 v-model="job.title"
                                                 :is-error="validator?.job?.title?.$errors?.length"
                                                 :error-message="validator?.job?.title?.$errors[0]?.$message || ''"
                                        >
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="شروع به کار"
                                                 :is-error="validator?.job?.end?.$errors?.length"
                                                 :error-message="validator?.job?.end?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <date-picker
                                                    format="jMMMM jYYYY"
                                                    display-format="jMMMM jYYYY"
                                                    type="year-month"
                                                    view="year"
                                                    clearable
                                                    v-model="job.start"
                                                    auto-submit
                                                    input-class="is-ltr has-text-left input"
                                                ></date-picker>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="پایان همکاری"
                                                 :is-error="validator?.job?.end?.$errors?.length"
                                                 :error-message="validator?.job?.end?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <date-picker
                                                    format="jMMMM jYYYY"
                                                    display-format="jMMMM jYYYY"
                                                    type="year-month"
                                                    view="year"
                                                    clearable
                                                    v-model="job.end"
                                                    auto-submit
                                                    input-class="is-ltr has-text-left input"
                                                ></date-picker>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center detailed-section" v-for="(jb, index) in userProfile.jobs">
                                        <span class="ml-2">{{ jb.company }}</span>
                                        <span class="ml-2">{{ jb.title }}</span>
                                        <span class="ml-2 small"><span>{{ jb.start }}</span><span v-if="jb.end"> تا {{ jb.end }}</span></span>
                                        <span class="ml-auto" @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='language'">
                                    <div class="column is-12">
                                        <b-input label="عنوان زبان" placeholder="عنوان زبان"
                                                 v-model="language.title"
                                                 :is-error="validator?.language?.title?.$errors?.length"
                                                 :error-message="validator?.language?.title?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label=" "
                                                 :is-error="validator?.social?.title?.$errors?.length"
                                                 :error-message="validator?.social?.title?.$errors[0]?.$message || ''"
                                        >
                                            <template #input>
                                                <b-switch
                                                    v-model="language.is_native"
                                                    label="زبان مادری / گویش بومی"
                                                    label-class="mr-0"
                                                    :checked="language.is_native == 1"
                                                ></b-switch>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input
                                            :label="'<span class=\'is-flex\'><span>میزان تسلط</span><span class=\'ml-auto\'>'+language.level+' از 5</span></span>'"
                                            class="w-100"
                                        >
                                            <template #input>
                                                <bdi class="w-100">
                                                    <star-ratings
                                                        class="m-0 pt-4 w-100 px-0"
                                                        :number-of-stars="5"
                                                        :star-size="24"
                                                        :show-control="true"
                                                        :disable-click="true"
                                                        v-model="language.level"
                                                    ></star-ratings>
                                                </bdi>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center detailed-section" v-for="(lang, index) in userProfile.languages">
                                        <span class="ml-2 has-text-centered is-ltr">
                                            <star-ratings
                                                class="m-0 p-0 w-100"
                                                :number-of-stars="5"
                                                :star-size="12"
                                                :show-control="false"
                                                :disable-click="true"
                                                readonly=""
                                                v-model="lang.level"
                                            ></star-ratings>
                                        </span>
                                        <span class="small ml-2" style="width: 45px">{{ lang.level }} از 5</span> <span>{{ lang.title }} <span class="small" v-if="lang.is_native==1">زبان مادری / گویش بومی</span></span>
                                        <span class="ml-auto" @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='skill'">
                                    <div class="column is-6">
                                        <b-input label="عنوان مهارت" placeholder="عنوان مهارت"
                                                 v-model="skill.title"
                                                 :is-error="validator?.skill?.title?.$errors?.length"
                                                 :error-message="validator?.skill?.title?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input
                                            :label="'<span class=\'is-flex\'><span>میزان تسلط</span><span class=\'ml-auto\'>'+skill.level+' از 5</span></span>'"
                                            class="w-100"
                                        >
                                            <template #input>
                                                <bdi class="w-100">
                                                    <star-ratings
                                                        class="m-0 pt-4 w-100 px-0"
                                                        :number-of-stars="5"
                                                        :star-size="24"
                                                        :show-control="true"
                                                        :disable-click="true"
                                                        v-model="skill.level"
                                                    ></star-ratings>
                                                </bdi>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center detailed-section" v-for="(sk, index) in userProfile.skills">
                                        <span class="ml-2 has-text-centered is-ltr">
                                            <star-ratings
                                                class="m-0 p-0 w-100"
                                                :number-of-stars="5"
                                                :star-size="12"
                                                :show-control="false"
                                                :disable-click="true"
                                                readonly=""
                                                v-model="sk.level"
                                            ></star-ratings>
                                        </span>
                                        <span class="small ml-2" style="width: 45px">{{ sk.level }} از 5</span> <span>{{ sk.title }}</span>
                                        <span class="ml-auto" @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='doc'">
                                    <div class="column is-6">
                                        <b-input label="عنوان مدرک / گواهینامه" placeholder="عنوان مدرک / گواهینامه"
                                                 v-model="doc.title"
                                                 :is-error="validator?.doc?.title?.$errors?.length"
                                                 :error-message="validator?.doc?.title?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="ارائه دهنده / محل اخذ" placeholder="ارائه دهنده / محل اخذ"
                                                 v-model="doc.company"
                                                 :is-error="validator?.doc?.company?.$errors?.length"
                                                 :error-message="validator?.doc?.company?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input
                                            label="کیفیت مدرک"
                                        >
                                            <template #input>
                                                <b-select
                                                    :options="['عالی', 'خوب', 'متوسط', 'ضعیف']"
                                                    v-model="doc.level"
                                                ></b-select>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="تاریخ اخذ مدرک" placeholder="تاریخ اخذ مدرک">
                                            <template #input>
                                                <date-picker
                                                    format="jMMMM jYYYY"
                                                    display-format="jMMMM jYYYY"
                                                    type="year-month"
                                                    view="year"
                                                    clearable
                                                    v-model="doc.year"
                                                    auto-submit
                                                    input-class="is-ltr has-text-left input"
                                                ></date-picker>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-12 is-flex align-items-center detailed-section" v-for="(dc, index) in userProfile.documents">
                                        <span>{{ dc.title }}</span>
                                        <span class="ml-2 has-text-info">{{ dc.company }}</span>
                                        <span class="small ml-2 has-text-success is-weight-700">{{ dc.level }}</span>
                                        <span class="small ml-2">{{ dc.year }}</span>
                                        <span class="ml-auto" @click="deleteDetail(index)"><i class="has-background-danger delete"></i></span>
                                    </div>

                                </div>
                                <div class="columns is-multiline" v-if="active_tab==='trophy'">
                                    <div class="column is-6">
                                        <b-input label="عنوان مدرک / گواهینامه" placeholder="عنوان مدرک / گواهینامه"
                                                 v-model="doc.title"
                                                 :is-error="validator?.doc?.title?.$errors?.length"
                                                 :error-message="validator?.doc?.title?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="ارائه دهنده / محل اخذ" placeholder="ارائه دهنده / محل اخذ"
                                                 v-model="doc.company"
                                                 :is-error="validator?.doc?.company?.$errors?.length"
                                                 :error-message="validator?.doc?.company?.$errors[0]?.$message || ''"
                                        ></b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input
                                            label="کیفیت مدرک"
                                        >
                                            <template #input>
                                                <b-select
                                                    :options="['عالی', 'خوب', 'متوسط', 'ضعیف']"
                                                    v-model="doc.level"
                                                ></b-select>
                                            </template>
                                        </b-input>
                                    </div>
                                    <div class="column is-6">
                                        <b-input label="تاریخ اخذ مدرک" placeholder="تاریخ اخذ مدرک">
                                            <template #input>
                                                <date-picker
                                                    format="jMMMM jYYYY"
                                                    display-format="jMMMM jYYYY"
                                                    type="year-month"
                                                    view="year"
                                                    clearable
                                                    v-model="doc.year"
                                                    auto-submit
                                                    input-class="is-ltr has-text-left input"
                                                ></date-picker>
                                            </template>
                                        </b-input>
                                    </div>

                                </div>
                            </div>
                            <div class="fieldset is-flex">
                                <button class="ml-auto button h-button is-success is-raised" @click="addUserDetail" v-if="active_tab != 'general'">افزودن</button>
                            </div>
                        </div>
                        <div class="form-foot stuck-header">
                            <div class="form-head-inner">
                                <div class="left">
                                    <span class="light-text">جهت اعمال هرگونه تغییر انجام شده، دکمه </span>
                                    <span class="has-text-success mx-1 is-weight-700">ذخیره تغییرات</span>
                                    <span class="light-text"> را کلیک کنید</span>
                                </div>
                                <div class="right">
                                    <div class="buttons">
                                        <button class="button h-button is-success is-raised" @click="saveData" :disabled="loading">
                                            <i class="fal fa-save" v-if="!loading"></i>
                                            <i class="fal fa-spinner-third fa-spin" v-else></i>
                                            <span class="ml-2">ذخیره تغییرات</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Component>
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
    name: "UserProfileEditComponent",
    inject: ['base_url'],
    props: {
        layout: {
            type: String,
            default: 'AdminLayoutComponent',
        },
        user: {
            type:  [Boolean, Object],
            default: false,
        },
    },
    components: {
        Cropper,
        CircleStencil
    },
    setup () {
        return { validator: useVuelidate() }
    },
    data() {
        return {
            active_tab: 'general',
            tabs: [
                {id: 'general', value: 'عمومی', icon: 'fal fa-user'},
                {id: 'social', value: 'شبکه‌های اجتماعی', icon: 'fal fa-smile'},
                // {id: 'education', value: 'سوابق تحصیلی', icon: 'fal fa-user-graduate'},
                // {id: 'job', value: 'سوابق شغلی', icon: 'fal fa-briefcase'},
                // {id: 'language', value: 'زبان‌های خارجی', icon: 'fal fa-globe-europe'},
                // {id: 'skill', value: 'مهارت‌ها', icon: 'fal fa-tools'},
                // {id: 'doc', value: 'مدارک و گواهینامه‌ها', icon: 'fal fa-file-certificate'},
                // {id: 'trophies', value: 'افتخارات', icon: 'fa fa-medal'},
            ],
            educations_grade: [
                'دیپلم',
                'فوق دیپلم (کاردانی)',
                'لیسانس (کارشناسی)',
                'فوق لیسانس (کارشناسی ارشد)',
                'دکترای غیر پزشکی',
                'دکترای پزشکی',
                'مدارک حوزوی',
            ],
            loading: true,
            errors: [],
            username: '',
            email: '',
            mobile: '',
            userProfile: {
                username: '',
                fName: '',
                lName: '',
                display_name: '',
                role: '',
                slogan: '',
                about: '',
                avatar: '',
                email: '',
                mobile: '',
                email_is_verified: false,
                mobile_is_verified: false,
                username_is_changed: false,
                socials: [],
                educations: [],
                jobs: [],
                languages: [],
                skills: [],
                documents: [],
                trophies: [],
                notification_email: true,
                notification_sms: true
            },
            social: {
                title: '',
                icon: '',
                url: '',
            },
            education: {
                title: '',
                major: '',
                branch: '',
                university: '',
                year: ''
            },
            job: {
                company: '',
                title: '',
                start: '',
                end: '',
            },
            language: {
                title: '',
                is_native: false,
                level: 5,
            },
            skill: {
                title: '',
                level: 5,
            },
            doc: {
                title: '',
                company: '',
                level: 'خوب',
                year: '',
            },
            avatar: '',
        }
    },
    validations() {
        return {
            userProfile: {
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
                slogan: {
                    min: {
                        $validator: (val) => {
                            return (val=='' || val?.length >= 5)
                        },
                        $message: 'شعار باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 200)
                        },
                        $message: 'شعار باید حداکثر 200 کاراکتر باشد'
                    },
                },
                about: {
                    min: {
                        $validator: (val) => {
                            return (val === null || val === '' || val?.length >= 20)
                        },
                        $message: 'متن درباره باید حداقل 20 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 200)
                        },
                        $message: 'متن درباره باید حداکثر 2500 کاراکتر باشد'
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
                            return (val?.length != 0 || this?.username?.mobile?.length != 0)
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
                            return (val?.length != 0 || this?.userProfile?.email?.length != 0)
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
            },
        }
    },
    created() {
        this.getCurrentUserData()
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
                    const userProfile = {
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
                        userProfile[key] = value
                    }
                    this.setProfileData(userProfile)
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
        changeAvatar() {
            const c = this.$refs.cropper.getResult().canvas
            this.userProfile.avatar = c.toDataURL()
            this.$refs.avatarCropper.modalClose()
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
        saveData() {
            const key = this.active_tab === 'doc' ? 'documents' : this.active_tab+'s'
            let form = {
                username: this.username
            }
            if (this.active_tab === 'general') {
                this.validator.$touch()
                if (this.validator.userProfile.$invalid) {
                    return
                }
                form['avatar'] = this.userProfile.avatar || -1
                form['fName'] = this.userProfile.fName
                form['lName'] = this.userProfile.lName
                form['slogan'] = this.userProfile.slogan
                form['about'] = this.userProfile.about
                if (this.userProfile.email_is_verified===1 && this.email.toUpperCase() !== this.userProfile.email.toUpperCase()) {
                    form['email'] = this.userProfile.email
                }
                if (this.userProfile.username_is_changed==true && this.username.toUpperCase() !== this.userProfile.username.toUpperCase()) {
                    form['uName'] = this.userProfile.username
                }
                if (this.userProfile.mobile_is_verified===1 && !this.mobile.includes(this.userProfile.mobile)) {
                    form['mobile'] = this.userProfile.mobile
                }
            } else {
                form['detail'] = this.userProfile[key]
                form['key'] = key
            }
            this.loading = true
            Requests.updateProfile(form).then(res => {
                if (res?.status) {
                    this.$helpers.notify(res?.message || 'تغییرات مورد نظر اعمال شد')
                    const details = res.result.details
                    const avatar = this.userProfile.avatar
                    let userProfile = {
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
                        userProfile[key] = value
                    }

                    this.setProfileData(userProfile)
                    this.$store.commit('setUserData', res.userData)
                    this.EventBus.emit('userDataChanged', true)
                    return {avatar: avatar}
                } else {
                    this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره تغییرات پروفایل', { type: 'error' })
                }
            }).catch(err => {

            }).finally(res => {
                this.validator.$reset()
                this.$helpers.toDataURL(this.userProfile.avatar, res => {
                    this.userProfile.avatar = res
                })
                this.userProfile.avatar = res?.avatar || this.userProfile.avatar
                this.loading = false
            })
        },
        addUserDetail() {
            const tempData = eval('JSON.stringify(this.'+this.active_tab+')')
            const key = this.active_tab === 'doc' ? 'documents' : this.active_tab+'s'
            const k = eval('this.'+this.active_tab)
            let f = {}
            for (const kKey in k) {
                f[kKey] = k[kKey]
            }
            this.userProfile[key].push(f)
            this.social = { title: '', icon: '', url: '' }
            this.education = { title: '', major: '', branch: '', university: '', year: '' }
            this.job = { company: '', title: '', start: '', end: '', }
            this.language = { title: '', is_native: false, level: 5, }
            this.skill = { title: '', level: 5, }
            this.doc = { title: '', company: '', level: 'خوب', year: '', }
            const el = document.getElementById('userDetailsSection')?.getElementsByTagName('input')[0] ?? false
            if (el) {
                el.focus()
            }
        },
        deleteDetail(index) {
            const key = this.active_tab === 'doc' ? 'documents' : this.active_tab+'s'
            this.userProfile[key].splice(index, 1)
        },
        setProfileData(profile) {
            for (const key in this.userProfile) {
                this.userProfile[key] = profile[key] || this.userProfile[key]
            }
            if (this.userProfile?.avatar && this.userProfile?.avatar.includes('placeholder')) {
                delete(this.userProfile.avatar)
            }
            if (this.userProfile?.avatar) {
                this.$helpers.toDataURL(this.userProfile?.avatar, res => {
                    this.avatar = res
                    this.userProfile.avatar = res
                })
            }
            let now = new Date
            this.userProfile.email_is_verified = profile?.email_verified_at && this.$helpers.jDate(profile?.email_verified_at) <= this.$helpers.jDate()
            this.userProfile.mobile_is_verified = profile?.mobile_verified_at && this.$helpers.jDate(profile?.mobile_verified_at) <= this.$helpers.jDate()
            this.username   = this.userProfile.username
            this.email      = this.userProfile.email
            this.mobile     = this.userProfile.mobile
        },
    }
}
</script>

<style scoped>
.fieldset {
    max-width: unset;
}
.detailed-section {
    border-radius: 10px;
    margin-bottom: 2px;
}
.detailed-section:nth-child(even) {
    background-color: rgba(245, 245, 245, 0.7);
}
.detailed-section:nth-child(odd) {
    background-color: rgba(245, 245, 245, 0.35);
}
.detailed-section:hover {
    background-color: rgba(240, 240, 240, 0.7);
    cursor: default;
}
</style>
