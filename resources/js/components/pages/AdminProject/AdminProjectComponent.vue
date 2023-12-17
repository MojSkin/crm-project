<template>
    <AdminLayoutComponent>
        <template #title>پروژه‌ها</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newItem" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>پروژه جدید</span>
                    </button>
                    <button @click="refreshTable(false, 12)" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست نتایج پروژه">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'px-0': newRec || editing}" class="overflow-visible position-relative">
            <b-overlay :show="saving || loading" loader-custom-class="has-text-info" :loader-text="loading ? 'در حال دریافت اطلاعات...' : 'در حال ذخیره پروژه... '">
                <div class="columns is-multiline is-flex" v-if="!newRec && !editing">
                    <div class="column is-12" v-if="projects.length > 0">
<!--                        <b-accordion-->
<!--                            :headers="[{id: 'searchPanel', title: 'جستجوی پیشرفته',}]"-->
<!--                            is-exclusive-->
<!--                            class="mb-5"-->
<!--                        >-->
<!--                            <template #content-searchPanel>-->
<!--                                <div class="columns is-multiline">-->
<!--                                </div>-->
<!--                                <div class="columns is-multiline">-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">-->
<!--                                        <b-input-->
<!--                                            label='نام مخاطب:'-->
<!--                                            v-model="filters.fName"-->
<!--                                            placeholder="نام مخاطب"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">-->
<!--                                        <b-input-->
<!--                                            label='نام خانوادگی مخاطب:'-->
<!--                                            v-model="filters.lName"-->
<!--                                            placeholder="نام خانوادگی مخاطب"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="columns is-multiline">-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">-->
<!--                                        <b-input-->
<!--                                            label='نام مستعار:'-->
<!--                                            v-model="filters.nickname"-->
<!--                                            placeholder="نام مستعار"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">-->
<!--                                        <b-input-->
<!--                                            label='عنوان:'-->
<!--                                            v-model="filters.title"-->
<!--                                            placeholder="عنوان"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-12-mobile is-12-tablet is-4-widescreen is-4-desktop">-->
<!--                                        <b-input-->
<!--                                            label='نام تجاری:'-->
<!--                                            v-model="filters.org"-->
<!--                                            placeholder="نام تجاری"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="columns is-multiline">-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">-->
<!--                                        <b-input-->
<!--                                            label='تلفن:'-->
<!--                                            v-model="filters.phone"-->
<!--                                            placeholder="تلفن"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">-->
<!--                                        <b-input-->
<!--                                            label='ایمیل:'-->
<!--                                            v-model="filters.email"-->
<!--                                            placeholder="ایمیل"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">-->
<!--                                        <b-input-->
<!--                                            label='آدرس:'-->
<!--                                            v-model="filters.address"-->
<!--                                            placeholder="آدرس"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                    <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">-->
<!--                                        <b-input-->
<!--                                            label='سایر مشخصات:'-->
<!--                                            v-model="filters.other"-->
<!--                                            placeholder="سایر مشخصات"-->
<!--                                        ></b-input>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="columns is-multiline">-->
<!--                                    <div class="column is-12 is-flex">-->
<!--                                        <button class="button is-success mr-2 ml-auto" @click="refreshTable(filters, 12)" :disabled="loading">-->
<!--                                            <i class="fal fa-filter" v-if="!loading"></i>-->
<!--                                            <i class="fal fa-spinner-third fa-spin" v-else></i>-->
<!--                                            <span class="ml-2">اعمال شرایط</span>-->
<!--                                        </button>-->
<!--                                        <button class="button" @click="cancelFilter">پاک کردن شرایط</button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </template>-->
<!--                        </b-accordion>-->
                    </div>
                    <div class="no-projects py-6 w-100 is-flex is-align-items-center is-justify-content-center is-flex-direction-column" style="min-height: 60vh;" v-else>
                        <img style="max-width: 25vw" class="light-image" :src="base_url+'/assets/img/illustrations/placeholders/thinking-canvas.svg'" alt="" />
                        <img style="max-width: 25vw" class="dark-image" :src="base_url+'/assets/img/illustrations/placeholders/thinking-canvas-dark.svg'" alt="" />
                        <div class="text">
                            <h3>هنوز پروژه‌ای ثبت نشده است</h3>
                        </div>
                    </div>
                    <div class="column is-12-mobile is-6-tablet is-4-desktop is-3-widescreen" :key="'project-'+project?.id" v-for="project in projects">
                        <ProjectSingleCardComponent :project="project" @edit="editItem(project)"/>
                    </div>
                </div>
                <b-tabs v-model="active_tab" :headers="tabs" v-else>
                    <template #general>
                        <div class="px-5">
                            <div class="columns is-multiline is-flex">
                                <div class="column is-12-mobile is-12-tablet is-6-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>عنوان پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.title?.$errors?.length"
                                        :error-message="validator?.form?.title?.$errors[0]?.$message || ''"
                                        v-model="form.title"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-12">
                                    <b-input
                                        label="<span>توضیحات پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.description?.$errors?.length"
                                        :error-message="validator?.form?.description?.$errors[0]?.$message || ''"
                                        v-model="form.description"
                                    >
                                        <template #input>
                                        <textarea
                                            v-model="form.description"
                                            rows="3"
                                            class="textarea no-resize"
                                            :class="{'is-error': validator?.form?.description?.$errors?.length}"
                                        />
                                        </template>
                                    </b-input>
                                </div>
                            </div>
                            <div class="columns is-multiline is-flex">
                                <div class="column is-6-mobile is-6-tablet is-6-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>نوع پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :error-message="validator?.form?.project_type?.id?.$errors[0]?.$message || ''"
                                        v-model="form.project_type.id"
                                    >
                                        <template #input>
                                            <b-select
                                                autoclose
                                                searchable
                                                clearable
                                                v-model="form.project_type.id"
                                                :options="this.types"
                                                :is-error="validator?.form?.project_type.id?.$errors?.length"
                                            />
                                        </template>
                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>درصد پورسانت بازاریاب</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.user_percentage?.$errors?.length"
                                        :error-message="validator?.form?.user_percentage?.$errors[0]?.$message || ''"
                                        v-model="form.user_percentage"
                                        step="0.5"
                                        type="number"
                                    >

                                    </b-input>
                                </div>

                            </div>
                            <div class="columns is-multiline is-flex">
                                <div class="column is-4-mobile is-4-tablet is-4-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>تعداد بلوک</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.blocks?.$errors?.length"
                                        :error-message="validator?.form?.blocks?.$errors[0]?.$message || ''"
                                        v-model="form.blocks"
                                        type="number"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-4-mobile is-4-tablet is-4-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>تعداد طبقات</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        v-model="form.floors"
                                        type="number"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-4-mobile is-4-tablet is-4-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>تعداد واحدها</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        v-model="form.units"
                                        type="number"
                                    >

                                    </b-input>
                                </div>
                            </div>
                            <hr class="has-background-grey mt-0">
                            <div class="columns is-multiline is-flex">
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>شهرستان</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :error-message="validator?.form?.city.id?.$errors[0]?.$message || ''"
                                        v-model="form.city.id"
                                    >
                                        <template #input>
                                            <b-select
                                                autoclose
                                                searchable
                                                clearable
                                                v-model="form.city.id"
                                                :options="this.cities"
                                                :is-error="validator?.form?.city.id?.$errors?.length"
                                            />
                                        </template>
                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>منطقه شهرداری</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.region?.$errors?.length"
                                        :error-message="validator?.form?.region?.$errors[0]?.$message || ''"
                                        v-model="form.region"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>خیابان اصلی</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.main_street?.$errors?.length"
                                        :error-message="validator?.form?.main_street?.$errors[0]?.$message || ''"
                                        v-model="form.main_street"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="خیابان فرعی 1"
                                        v-model="form.aux1"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="خیابان فرعی 2"
                                        v-model="form.aux2"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="کوچه اصلی"
                                        v-model="form.alley1"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="کوچه فرعی"
                                        v-model="form.alley2"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="کد پستی"
                                        :is-error="validator?.form?.po_code?.$errors?.length"
                                        :error-message="validator?.form?.po_code?.$errors[0]?.$message || ''"
                                        v-model="form.po_code"
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-12">
                                    <b-input
                                        label="<span>نشانی پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.form?.address?.$errors?.length"
                                        :error-message="validator?.form?.address?.$errors[0]?.$message || ''"
                                        v-model="form.address"
                                    >
                                        <template #input>
                                        <textarea
                                            v-model="form.address"
                                            rows="3"
                                            class="textarea no-resize"
                                            :class="{'is-error': validator?.form?.address?.$errors?.length}"
                                        />
                                        </template>
                                    </b-input>
                                </div>
                            </div>
                            <div class="columns is-multiline is-flex">
                                <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>طول جغرافیایی</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        v-model="form.long"
                                        readonly
                                        disabled
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                    <b-input
                                        label="<span>عرض جغرافیایی</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        v-model="form.lat"
                                        readonly
                                        disabled
                                    >

                                    </b-input>
                                </div>
                                <div class="column is-12-mobile is-12-tablet is-3-desktop is-3-widescreen">
                                    <button class="button is-light mt-5" @click="getCurrentPositionFromMap(true)" :disabled="gettingLocationInfo">
                                        <i class="fas fa-spinner-third fa-spin has-text-grey" v-if="gettingLocationInfo"></i>
                                        <i class="fal fa-location has-text-info" v-else></i>
                                        <span class="ml-2">دریافت موقعیت</span>
                                    </button>
                                </div>
                            </div>
                            <div class="columns is-multiline is-flex">
                                <div class="column is-12">
                                    <ol-map
                                        ref="map"
                                        style="height: 400px; border-radius: 5px; overflow: hidden"
                                        :loadTilesWhileAnimating="true"
                                        :loadTilesWhileInteracting="true"
                                        @click="featureSelected"
                                    >
                                        <ol-view
                                            ref="view"
                                            :center="mapOptions.center"
                                            :rotation="mapOptions.rotation"
                                            :zoom="mapOptions.zoom"
                                            :projection="mapOptions.projection"
                                        />

                                        <ol-tile-layer>
                                            <ol-source-osm />
                                        </ol-tile-layer>

                                        <ol-vector-layer>
                                            <ol-source-vector ref="vectorsource">
                                                <ol-feature ref="feature">
                                                    <ol-geom-point ref="geompoint" :coordinates="mapOptions.center"/>

                                                    <ol-style>
                                                        <ol-style-icon ref="geompointicon" :src="mapOptions.icon" :scale="0.65"/>
                                                    </ol-style>
                                                </ol-feature>
                                            </ol-source-vector>
                                        </ol-vector-layer>
                                    </ol-map>
                                </div>
                            </div>
                            <hr class="has-background-grey mt-0">
                            <div class="columns is-multiline is-flex">
                                <div class="column is-12">
                                    <div>اعضاء پروژه:</div>
                                    <div class="small has-text-grey">در صورت خالی بودن این بخش، پروژه به صورت عمومی نمایش داده خواهد شد!</div>
                                </div>
                                <div class="column is-12-mobile is-12-tablet is-6-desktop is-4-widescreen">
                                    <b-select
                                        autoclose
                                        searchable
                                        clearable
                                        :options="this.users"
                                        @selected="addMember"
                                        ref="memberSelect"
                                    />
                                </div>
                                <div class="column is-12">
                                    <div class="snacks border border-1 py-4 is-rounded" style="min-height: 140px; border-radius: 5px;">
                                        <div class="snack has-background-info-light" v-for="member in form.members">
                                            <div class="snack-media">
                                                <img class="avatar" :src="member?.details?.avatar || ''" alt="">
                                            </div>
                                            <span class="snack-text has-text-info-dark" v-text="member.display_name"/>
                                            <span class="snack-action" @click="removeMember(member.id)">
                                            <i class="fal fa-times has-text-danger"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #contacts>
                        <div class="px-5">
                            <div class="columns is-multiline is-flex is-align-items-center">
                                <div class="column is-12-mobile is-5-tablet is-4-desktop is-4-widescreen">
                                    <b-select
                                        autoclose
                                        searchable
                                        clearable
                                        :options="this.contacts"
                                        ref="contactSelect"
                                        v-model="tempContact"
                                    />
                                </div>
                                <div class="column is-10-mobile is-5-tablet is-4-desktop is-4-widescreen">
                                    <b-select
                                        autoclose
                                        searchable
                                        clearable
                                        :options="this.positions"
                                        ref="positionSelect"
                                        v-model="tempPosition"
                                    />
                                </div>
                                <div class="column is-2-mobile is-2-tablet is-4-desktop is-4-widescreen pb-4">
                                    <span class="hint--light hint--rounded hint--top pt-1" data-hint="افزودن مخاطب به پروژه">
                                        <button class="button border-0 p-3 is-info" @click="addContact">
                                            <i class="fal fa-plus has-text-info-light"></i>
                                        </button>
                                    </span>
                                    <span class="hint--light hint--rounded hint--top pt-1" data-hint="افزودن مخاطب جدید">
                                        <button class="button border-0 p-3 is-primary ml-2" @click="addNewContact">
                                            <i class="fal fa-user-plus"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="column is-12">
                                    <div class="snacks py-4 is-rounded" style="min-height: 140px; border-radius: 5px;">
                                        <div class="snack pt-1 has-background-info-light" v-for="contact in form.contacts">
                                        <span class="snack-text ml-4 mt-1 has-text-info-dark">
                                            <span v-text="contact.name" class="mr-2" />
                                            <span v-text="contact?.position?.title" class="tag is-info is-rounded"/>
                                        </span>
                                            <span class="snack-action" @click="removeContact(contact.id, contact.position.id)">
                                            <i class="fal fa-times has-text-danger"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #images>
                        <div class="px-5">
                            <div class="columns is-multiline">
                                <div class="column is-12 pb-2">
                                    <b-dropzone v-model="files" @dropped="fileDropped" accept="image/jpeg, image/png"/>
                                    <span class="small pl-3">
                                        <span>فقط تصاویر با پسوند</span>
                                        <span class="mx-2 has-text-primary">jpg / jpeg / png</span>
                                        <span>مورد تایید است.</span>
                                    </span>
                                </div>
                                <div class="column is-12 pt-0">
                                    <progress class="progress is-success is-tiny" :value="uploadProgress" max="100" v-if="saving"/>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    تصاویر پروژه:
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-2-widescreen is-flex align-items-center flex-wrap image-thumb-wrapper" v-if="!form?.images || !form?.images?.length">
                                    <div class="image-thumb">
                                        <div class="image is-flex is-align-items-center is-justify-content-center is-flex-direction-column">
                                        <span class="fa-stack fa-3x">
                                            <i class="fal fa-camera fa-stack-1x"></i>
                                            <i class="fas fa-ban fa-stack-2x has-text-grey-dark opacity-50"></i>
                                        </span>
                                            <span class="small has-text-grey mt-2">تصویری موجود نیست</span>
                                        </div>
                                        <div class="field has-addons is-disabled">
                                            <div class="control is-expanded">
                                                <input class="input is-small" type="text" placeholder="عنوان تصویر" disabled="">
                                            </div>
                                            <div class="control">
                                                <a class="button is-static is-small">
                                                    <i class="fal fa-check has-text-success mr-3"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-2-widescreen is-flex align-items-center flex-wrap image-thumb-wrapper" v-for="(image, imageIndex) in form?.images">
                                    <div class="image-thumb">
                                        <button @click.stop="deleteImage(image.id, 1)">
                                            <i class="fal fa-trash-alt"></i>
                                        </button>
                                        <div class="image" :style="{ 'background-image': 'url('+image.filename+')' }" @click="previewImage(image)"></div>
                                        <div class="image-type w-100 d-flex pb-1">
                                            <span class="w-100 has-text-centered is-clickable" :class="image.type==='PROJECT_COVER' ? 'has-background-primary has-text-light' : 'has-background-light'" @click="image.type='PROJECT_COVER'">کاور</span>
                                            <span class="w-100 has-text-centered is-clickable" :class="image.type==='PROJECT_IMAGE' ? 'has-background-primary has-text-light' : 'has-background-light'" @click="image.type='PROJECT_IMAGE'">تصویر</span>
                                        </div>
                                        <div class="field has-addons pb-1" :class="{ 'is-disabled': updatingAlts === image.id }">
                                            <div class="control is-expanded">
                                                <input v-model="image.alt" class="input is-small" type="text" placeholder="عنوان تصویر" :disabled="updatingAlts === image.id" @keydown.enter="changeAlt(image, 1)">
                                            </div>
                                            <div class="control">
                                                <a class="button is-light is-small is-clickable" v-if="updatingAlts != image.id" @click="changeAlt(image, 1)">
                                                    <i class="fal fa-check has-text-success mx-1 is-clickable"></i>
                                                </a>
                                                <a class="button is-static is-small" v-else>
                                                    <i class="fal fa-spinner-third fa-spin mx-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #files>
                        <div class="px-5">
                            <div class="columns is-multiline">
                                <div class="column is-12 pb-2">
                                    <div class="file is-boxed is-align-items-center" style="min-height: 170px">
                                        <label class="has-background-light is-rounded rounded-3 p-5 w-100 file-label is-block" v-if="attachment === null">
                                            <input class="file-input" type="file" @change="handleFileSelect" :multiple="false" accept=".zip, .rar, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .mp3">
                                            <span class="file-cta has-background-info has-text-white">
                                                <span class="file-icon">
                                                    <i class="fal fa-upload"></i>
                                                </span>
                                                <span class="file-label is-weight-700">انتخاب کنید...</span>
                                            </span>
                                            <div class="column is-12 mt-2">
                                                <span class="mr-2">فرمت‌های مورد قبول:</span><bdi>zip, rar, pdf, doc, docx, xls, xlsx, ppt, pptx, mp3</bdi>
                                            </div>
                                        </label>
                                        <div class="has-background-light is-rounded rounded-3 p-5 is-flex w-100 is-align-items-center is-flex-direction-column" v-if="attachment != null">
                                            <div class="is-flex w-100 is-align-items-center">
                                                <div class="is-inline-block">
                                                    <i class="fad fa-5x has-text-warning mx-3" :class="fileTypes[attachment.name.split('.').pop().toLowerCase()] ?? 'fa-file'"></i>
                                                </div>
                                                <div class="is-inline-block">
                                                    <div v-text="attachment.name"></div>
                                                    <span class="tag is-light" v-text="getFileSize(attachment.size)"/>
                                                </div>
                                            </div>
                                            <div class="is-flex w-100 is-align-items-center my-3">
                                                <button class="button is-danger is-hoverable ml-auto is-clickable" @click="attachment = null" :disabled="updatingAlts !== null">
                                                    <i class="fal fa-times"></i>
                                                    <span class="ml-2">انصراف</span>
                                                </button>
                                                <button class="button is-info ml-2" @click="uploadSelectedFile" :disabled="updatingAlts !== null">
                                                    <i class="fal fa-upload" v-if="updatingAlts === null"></i>
                                                    <i class="fas fa-spinner-third fa-spin" v-else></i>
                                                    <span class="ml-2">افزودن پیوست</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 pt-0">
                                        <progress class="progress is-success is-tiny w-100" :value="uploadProgress" max="100" v-if="updatingAlts !== null"/>
                                    </div>
                                </div>
                            </div>
                            <div class="columns is-multiline mt-6" id="files-zone">
                                <div class="column is-6-mobile is-4-tablet is-3-desktop is-2-widescreen is-flex align-items-center flex-wrap image-thumb-wrapper" v-for="(file, fileIndex) in form?.files">
                                    <div class="image-thumb">
                                        <button @click.stop="deleteImage(file.id, 2)">
                                            <i class="fal fa-trash-alt"></i>
                                        </button>
                                        <div class="image pt-4 has-text-centered cursor-normal">
                                            <i class="fad fa-6x" :class="fileTypes[file.ext]"></i>
                                        </div>
                                        <div class="image-type w-100 is-flex mb-2">
                                            <span class="mx-auto tag has-text-centered is-info">{{ getFileSize(file.size) }}</span>
                                            <span class="mx-auto tag has-text-centered is-primary is-clickable" @click="downloadImage(file)">
                                                <i class="fal fa-download"></i>
                                                <span class="ml-2">دریافت</span>
                                            </span>
                                        </div>
                                        <div class="image-type w-100 mb-2">
                                            <div class="is-flex is-align-items-center">
                                                <i class="fal fa-user-circle mr-1 has-text-info-dark"></i>
                                                <span class="small" v-text="file.user.display_name ?? 'نامشخص'"/>
                                            </div>
                                            <div class="is-flex is-align-items-center">
                                                <i class="fal fa-calendar mr-1 has-text-info-dark"></i>
                                                <span class="small" v-text="$helpers.jDate(file.created_at)"/>
                                            </div>
                                        </div>
                                        <div class="field has-addons pb-1" :class="{ 'is-disabled': updatingAlts === file.id }">
                                            <div class="control is-expanded">
                                                <input v-model="file.alt" class="input is-small" type="text" placeholder="عنوان فایل" :disabled="updatingAlts === file.id" @keydown.enter="changeAlt(file, 2)">
                                            </div>
                                            <div class="control">
                                                <a class="button is-light is-small is-clickable" v-if="updatingAlts != file.id" @click="changeAlt(file, 2)">
                                                    <i class="fal fa-check has-text-success mx-1 is-clickable"></i>
                                                </a>
                                                <a class="button is-static is-small" v-else>
                                                    <i class="fal fa-spinner-third fa-spin mx-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #comments>
                        <div class="mx-5 px-5 has-background-light is-rounded rounded-3" style="height: 60vh">
                            <div class="is-chat animated preFadeInUp fadeInUp">
                                <div class="chat-body-wrap overflow-hidden">
                                    <ol class="chat-body" ref="chatBody">
                                        <li class="no-messages" v-if="form?.comments?.length === 0">
                                            <img class="light-image" :src="base_url+'/assets/img/illustrations/placeholders/search-1.svg'" alt="" />
                                            <img class="dark-image" :src="base_url+'/assets/img/illustrations/placeholders/search-1-dark.svg'" alt="" />
                                            <div class="text">
                                                <h3>هنوز دیدگاهی وجود ندارد</h3>
                                            </div>
                                        </li>
                                        <li v-for="comment in form.comments" :class="comment.user.username === currentUser.username ? 'self' : 'other'">
                                            <div class="avatar" :class="{ visible: prevUser !== comment.user.id }">
                                                <img :src="comment.user.avatar" :alt="comment.user.display_name" draggable="false">
                                            </div>
                                            <div class="msg">
                                                <span class="name" :class="{ visible: prevUser !== comment.user.id }">{{ comment.user.display_name }}</span>
                                                <div class="msg-inner">
                                                    <p v-text="comment.comment"/>
                                                </div>
                                                <time class="is-ltr">{{ $helpers.jDate(comment.created_at, 'jYYYY/jMM/jDD - H:mm') }}</time>
                                            </div>
                                            {{ changePrevUser(comment.user.id) }}
                                        </li>
                                    </ol>
                                </div>
                                <div class="message-field-wrapper" style="padding: 0 35px;">
                                    <div class="control">
                                        <input v-model="comment" class="input is-rounded" type="text" placeholder="دیدگاه شما چیست؟" ref="commentInput" @keydown.enter="savingComment"/>
                                        <div class="send-message">
                                            <button class="button is-primary is-raised is-rounded" :disabled="savingComment" @click="saveComment">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA7UlEQVR4nO3UrU5DQRCG4UNJShMSEjBcQAUYFI5yCzWQWiQWieUeUKgmvQUktkGgGgwYFAaBA0P4eTBTsmnooQ17jurndjPzvbszs1sUC9UpNNDFMLfxKo5xL5TLeBNneB4bZwFgB328JZ5DHPwLgH1c4it8PmO9l8TMB0ATR7hNTvuCC2z9Ej8bAGs4wWNi/BQ13yjJKwegjXO8JsajuEWzJG8F21MB2MUA7xON62IpYtYjrofTKNMVHvBROkWVAyovUW1NnjKmPdxkHdM5H1onC6Dyr6K2z25SaMWU3VUCGAvLOMT1z+ZCxR/6BkTjZthcylLUAAAAAElFTkSuQmCC" v-if="!savingComment">
                                                <i class="fas fa-spinner-third fa-spin" v-else></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #notes>
                        <div class="p-3 mx-5">
                            <div class="columns is-multiline is-flex pb-0">
                                <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen">
                                    <b-input
                                        label="<span>متن پیگیری</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :is-error="validator?.note?.note?.$errors?.length"
                                        :error-message="validator?.note?.note?.$errors[0]?.$message || ''"
                                        v-model="form.note"
                                    >
                                        <template #input>
                                        <textarea
                                            v-model="note.note"
                                            rows="3"
                                            class="textarea no-resize"
                                            :class="{'is-error': validator?.note?.note?.$errors?.length}"
                                        />
                                        </template>
                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-4-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>وضعیت پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :error-message="validator?.note?.project_status?.$errors[0]?.$message || ''"
                                        v-model="note.project_status"
                                    >
                                        <template #input>
                                            <b-select
                                                autoclose
                                                searchable
                                                clearable
                                                v-model="note.project_status"
                                                :options="this.statuses"
                                                :is-error="validator?.note?.project_status?.$errors?.length"
                                            />
                                        </template>
                                    </b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-4-desktop is-4-widescreen">
                                    <b-input
                                        label="<span>نتیجه پروژه</span><i class='far fa-circle has-text-danger ml-2'></i>"
                                        :error-message="validator?.note?.project_result?.$errors[0]?.$message || ''"
                                        v-model="note.project_result"
                                    >
                                        <template #input>
                                            <b-select
                                                autoclose
                                                searchable
                                                clearable
                                                v-model="note.project_result"
                                                :options="this.results"
                                                :is-error="validator?.note?.project_result?.$errors?.length"
                                            />
                                        </template>
                                    </b-input>
                                </div>
                                <div class="column is-12-mobile is-12-tablet is-4-desktop is-4-widescreen is-flex pt-5" v-if="editing && editingItem.id > 0">
                                    <button class="button is-info ml-auto mt-3" @click="saveNote" :disabled="savingComment">
                                        <i class="fal fa-save" v-if="!savingComment"></i>
                                        <i class="fas fa-spinner-third fa-spin" v-else></i>
                                        <span class="ml-2">ثبت پیگیری</span>
                                    </button>
                                </div>
                            </div>
                            <hr v-if="form.notes.length" class="mt-0">
                            <div class="w-100 overflow-scroll" style="max-height: 480px;">
                                <div class="columns is-multiline is-rounded rounded-3 is-note overflow-hidden is-relative" v-for="(note, noteIndex) in form.notes">
                                    <div class="column is-12 is-flex">
                                        <span class="current-status has-background-warning" v-if="noteIndex === 0 & (noteIndex !== form.notes.length-1)">هم‌اکنون</span>
                                        <span class="current-status has-background-success" v-if="noteIndex === form.notes.length-1">شـروع</span>
                                        <div class="note-meta">
                                            <div class="h-avatar is-large">
                                                <img class="avatar" :src="note.user.avatar" alt="">
                                            </div>
                                            <div class="flex-meta">
                                                <div class="small">{{ note.user.display_name }}</div>
                                                <bdi class="small">{{ $helpers.jDate(note.created_at, 'jYYYY/jMM/jDD - H:mm') }}</bdi>
                                                <div class="w-100 mt-2 mb-1">
                                                    <span v-text="note?.project_status?.title" class="has-text-centered tag w-100" :style="{backgroundColor: note?.project_status?.bgColor, color: note?.project_status?.textColor}"/>
                                                </div>
                                                <div class="w-100 mb-2">
                                                    <span v-text="note?.project_result?.title" class="has-text-centered tag w-100" :style="{backgroundColor: note?.project_result?.bgColor, color: note?.project_result?.textColor}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="note-comment">
                                            {{ note.note }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #other>
                        <div class="p-3 mx-5">
                            <div class="columns is-multiline is-flex pb-0">
                                <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen">
                                    <b-card no-footer class="overflow-hidden" header-class="has-background-light">
                                        <template #header>
                                            <span class="mt-1">
                                                <b-switch
                                                    v-model="form.addTodo"
                                                >
                                                    <span class="has-text-dark is-inverted is-weight-700">افزودن به کارهای من</span>
                                                </b-switch>
                                            </span>
                                            <i class="fad fa-tasks fa-3x has-text-primary"></i>
                                        </template>
                                        <template #body>
                                            <Transition>
                                                <div class="card-body" v-if="form.addTodo">
                                                    <div class="inner-content">
                                                        <div class="columns is-flex is-multiline">
                                                            <div class="column is-12-mobile is-12-tablet is-6-desktop is-6-widescreen">
                                                                <b-input
                                                                    label='عنوان:'
                                                                    placeholder="عنوان"
                                                                    v-model="todo.title"
                                                                    :is-error="validator?.todo?.title?.$errors?.length"
                                                                    :error-message="validator?.todo?.title?.$errors[0]?.$message || ''"
                                                                ></b-input>
                                                            </div>
                                                            <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                                                <b-input
                                                                    label='سررسید:'
                                                                    placeholder="سررسید"
                                                                    v-model="todo.due_date"
                                                                >
                                                                    <template #input>
                                                                        <date-picker
                                                                            :initial-value='Date(Date.now())'
                                                                            format='YYYY/MM/DD'
                                                                            display-format='jYYYY/jMM/jDD'
                                                                            type="date"
                                                                            v-model="todo.due_date"
                                                                            input-class="is-ltr has-text-left input"
                                                                            key="todo-due_date"
                                                                        ></date-picker>
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                                                <b-input
                                                                    label='درجه اهمیت:'
                                                                    placeholder="درجه اهمیت"
                                                                    v-model="todo.due_date"
                                                                    :is-error="validator?.todo?.flag?.$errors?.length"
                                                                    :error-message="validator?.todo?.flag?.$errors[0]?.$message || ''"
                                                                >
                                                                    <template #input>
                                                                        <b-select
                                                                            autoclose
                                                                            searchable
                                                                            clearable
                                                                            v-model="todo.flag"
                                                                            :options="this.todoFlags"
                                                                            :is-error="validator?.todo?.flag?.$errors?.length"
                                                                            :error-message="validator?.todo?.flag?.$errors[0]?.$message || ''"
                                                                        />
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen">
                                                                <b-input
                                                                    label='توضیحات:'
                                                                    placeholder="توضیحات"
                                                                    v-model="todo.description"
                                                                    :error-message="validator?.todo?.description?.$errors[0]?.$message || ''"
                                                                >
                                                                    <template #input>
                                                            <textarea
                                                                v-model="todo.description"
                                                                rows="3"
                                                                class="textarea no-resize"
                                                                :class="{ 'is-error': validator?.todo?.description?.$errors?.length }"
                                                            />
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen is-flex" v-if="todo?.id">
                                                                <button class="button is-warning is-hoverable ml-auto" @click="deleteTodo(todo.id)" :disabled="saving">
                                                                    <i class="fal fa-times"></i>
                                                                    <span class="ml-2">حذف از فهرست کارها</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </template>
                                    </b-card>
                                    <b-card no-footer class="overflow-hidden" header-class="has-background-light">
                                        <template #header>
                                            <span class="mt-2">
                                                <b-switch
                                                    v-model="form.addAlarm"
                                                >
                                                    <span class="has-text-dark is-inverted is-weight-700">تنظیم یادآور</span>
                                                </b-switch>
                                            </span>
                                            <i class="fad fa-alarm-clock fa-3x has-text-primary"></i>
                                        </template>
                                        <template #body>
                                            <Transition>
                                                <div class="card-body" v-if="form.addAlarm">
                                                    <div class="inner-content">
                                                        <div class="columns is-flex is-multiline">
                                                            <div class="column is-12-mobile is-12-tablet is-6-desktop is-6-widescreen">
                                                                <b-input
                                                                    label='عنوان:'
                                                                    placeholder="عنوان"
                                                                    v-model="alarm.title"
                                                                    :is-error="validator?.alarm?.title?.$errors?.length"
                                                                    :error-message="validator?.alarm?.title?.$errors[0]?.$message || ''"
                                                                ></b-input>
                                                            </div>
                                                            <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                                                <b-input
                                                                    label='تاریخ یادآور:'
                                                                    placeholder="تاریخ یادآور"
                                                                    v-model="alarm.alarm_date"
                                                                    :is-error="validator?.alarm?.alarm_date?.$errors?.length"
                                                                    :error-message="validator?.alarm?.alarm_date?.$errors[0]?.$message || ''"
                                                                >
                                                                    <template #input>
                                                                        <date-picker
                                                                            :initial-value='Date(Date.now())'
                                                                            format='YYYY/MM/DD'
                                                                            display-format='jYYYY/jMM/jDD'
                                                                            type="date"
                                                                            v-model="alarm.alarm_date"
                                                                            input-class="is-ltr has-text-left input"
                                                                            key="todo-due_date"
                                                                        ></date-picker>
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-6-mobile is-6-tablet is-3-desktop is-3-widescreen">
                                                                <b-input
                                                                    label='ساعت یادآور:'
                                                                    placeholder="ساعت یادآور"
                                                                    v-model="alarm.alarm_time"
                                                                    :is-error="validator?.alarm?.alarm_time?.$errors?.length"
                                                                    :error-message="validator?.alarm?.alarm_time?.$errors[0]?.$message || ''"
                                                                >
                                                                    <template #input>
                                                                        <date-picker
                                                                            :initial-value='Date(Date.now())'
                                                                            type="time"
                                                                            format='H:mm'
                                                                            v-model="alarm.alarm_time"
                                                                            input-class="is-ltr has-text-left input"
                                                                            key="todo-due_date"
                                                                        ></date-picker>
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen">
                                                                <b-input
                                                                    label='توضیحات:'
                                                                    placeholder="توضیحات"
                                                                    v-model="alarm.description"
                                                                >
                                                                    <template #input>
                                                            <textarea
                                                                v-model="alarm.description"
                                                                rows="3"
                                                                class="textarea no-resize"
                                                                :class="{ 'is-error': validator?.alarm?.description?.$errors?.length }"
                                                            />
                                                                    </template>
                                                                </b-input>
                                                            </div>
                                                            <div class="column is-12-mobile is-12-tablet is-12-desktop is-12-widescreen is-flex" v-if="todo?.id">
                                                                <button class="button is-warning is-hoverable ml-auto" @click="deleteAlarm(alarm.id)" :disabled="saving">
                                                                    <i class="fal fa-times"></i>
                                                                    <span class="ml-2">حذف از فهرست یادآورها</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </template>
                                    </b-card>
                                </div>
                            </div>
                        </div>
                    </template>
                </b-tabs>
            </b-overlay>
            <template #footer-left>
                <button class="button is-success mr-2" @click="saveItem()" :disabled="saving || loading">
                    <i class="fal fa-spinner-third fa-spin" v-if="saving || loading"></i>
                    <i class="fal fa-save" v-else></i>
                    <span class="ml-2">ذخیره پروژه</span>
                </button>
                <button class="button" @click="form={}; newRec=false; editing=false;this.$router.push({ name : 'admin.projects' })">انصراف</button>
            </template>
        </b-card>
    </AdminLayoutComponent>
    <b-modal
        ref="previewImage"
        :is-card="false"
        @close="imageToPreview=null"
    >
        <b-card no-header class="no-margin no-padding" body-class="no-padding no-margin">
            <div class="w-100 p-5 has-text-centered" style="">
                <img :src="imageToPreview?.filename" :alt="imageToPreview?.alt" class="is-rounded rounded-3 mb-3">
                <span v-text="imageToPreview.alt"/>
            </div>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="دانلود تصویر"><i @click="downloadImage(imageToPreview)" class="fas fa-download has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="بستن پنجره"><i @click="imageToPreview= null; $refs.previewImage.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
    <b-modal
        ref="contactForm"
        :is-card="false"
    >
        <b-card card-size='s' class="no-margin no-padding" body-class="p-5" no-header>
            <div class="inner-content" style="max-height: 350px">
                <FormContentComponent
                    v-model="contactForm"
                    :errors="[]"
                    ref="newContactform"
                />
            </div>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="ذخیره"><i @click="saveNewContact" class="fas fa-check has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top mr-2 " data-hint="انصراف"><i @click="$refs.contactForm.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import 'vue3-openlayers/styles.css';
import ProjectSingleCardComponent from "@/components/pages/AdminProject/ProjectSingleCardComponent.vue";
import Form from '/resources/js/form'

export default {
    name: "AdminProjectComponent",
    components: {ProjectSingleCardComponent},
    inject: ['base_url', 'ol-format', 'ol-selectconditions'],
    setup () {
        return { validator: useVuelidate() }
    },
    data() {
        return {
            currentPosition: {
                long: 52.5452,
                lat: 29.6151,
            },
            permission: {
                add: this.permissions?.ucaBaseRequests || true,
                edit: this.permissions?.uceBaseRequests || true,
                delete: this.permissions?.ucdBaseRequests || true,
            },
            loading: false,
            saving: false,
            newRec: false,
            editing: false,
            savingComment: false,
            gettingLocationInfo: false,
            form: {
                title: '',
                description: '',
                project_type: {id: 0, title: ''},
                project_status: {id: 0, title: ''},
                project_result: {id: 0, title: ''},
                user_percentage: 0.0,
                images: [],
                files: [],
                comments: [],
                notes: [],
                members: [],
                contacts: [],
                city: {id: 728, title: ''},
                region: '',
                main_street: '',
                aux1: '',
                aux2: '',
                alley1: '',
                alley2: '',
                address: '',
                po_code: '',
                lat: '',
                long: '',
                blocks: 1,
                units: 1,
                floors: 1,
                addTodo: false,
                addAlarm: false,
            },
            todo: {
                title: '',
                due_date: '',
                flag: 'عادی',
                description: '',
            },
            alarm: {
                title: '',
                description: '',
                alarm_date: '',
                alarm_time: '',
            },
            todoFlags: [
                'کم',
                'متوسط',
                'عادی',
                'زیاد',
                'اضطراری',
            ],
            contact_template: {
                fName: {title: 'نام',                                   col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['min:2', 'max:100']},
                lName: {title: 'نام خانوادگی', is_required: true,       col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['required', 'min:2', 'max:100']},
                phone: {title: 'شماره تلفن (همراه)', is_required: true, col_mobile: 12, col_tablet: 12, col_desktop: 12, col_widescreen: 12, validator: ['required', 'digits:11', 'is_mobile']},
            },
            contactForm: {},
            comment: null,
            default_status: null,
            default_result: null,
            uploadProgress: 0,
            files: [],
            attachment: null,
            note: {
                note: '',
                project_status: null,
                project_result: null,
            },
            fileTypes: {
                zip:     'fa-file-archive',
                rar:     'fa-file-archive',
                doc:     'fa-file-word',
                docx:    'fa-file-word',
                xls:     'fa-file-excel',
                xlsx:    'fa-file-excel',
                ppt:     'fa-file-powerpoint',
                pptx:    'fa-file-powerpoint',
                pdf:     'fa-file-pdf',
                mp3:     'fa-file-music',
                unknown: 'fa-file'
            },
            updatingAlts: null,
            editingItem: [],
            filters: [],
            projects: [],
            types: [],
            statuses: [],
            results: [],
            contacts: [],
            original_contacts: [],
            positions: [],
            cities: [],
            original_cities: [],
            users: [],
            original_users: [],
            tempContact: null,
            tempPosition: null,
            active_tab: 'contacts',
            tabs: [
                {
                    id: 'contacts',
                    text: 'مخاطبین پروژه',
                },
                {
                    id: 'general',
                    text: 'اطلاعات کلی پروژه',
                },
                {
                    id: 'images',
                    text: 'تصاویر پروژه',
                },
                {
                    id: 'files',
                    text: 'پیوست‌های پروژه',
                },
                {
                    id: 'comments',
                    text: 'دیدگاه‌ها',
                },
                {
                    id: 'notes',
                    text: 'پیگیری‌ها',
                },
                {
                    id: 'other',
                    text: 'سایر',
                },
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 25, 50, 100, 250],
            filter: null,
            mapOptions: {
                center: [52.5452, 29.6151],
                projection: 'EPSG:4326',
                projectionDef: '+proj=utm +zone=40 +datum=WGS84 +units=m +no_defs',
                zoom: 10,
                rotation: 0,
                geoJson: new this['ol-format'].GeoJSON(),
                icon: this.base_url+'/assets/images/png/location.png',
                conditions: this['ol-selectconditions'],
            },
            imageToPreview: null,
            currentUser: null,
            prevUser: null,
        }
    },
    validations() {
        return {
            form: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'عنوان پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'عنوان پروژه باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 100)
                        },
                        $message: 'عنوان پروژه باید حداکثر 100 کاراکتر باشد'
                    },
                },
                description: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'توضیحات پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'توضیحات پروژه باید حداقل 10 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 250)
                        },
                        $message: 'توضیحات پروژه باید حداکثر 250 کاراکتر باشد'
                    },
                },
                project_type: {
                    id: {
                        required: {
                            $validator: (val) => {
                                return (val >= 1)
                            },
                            $message: 'انتخاب نوع پروژه الزامی است'
                        }
                    }
                },
                project_status: {
                    id: {
                        required: {
                            $validator: (val) => {
                                return (this.newRec || val > 0)
                            },
                            $message: 'انتخاب وضعیت پروژه الزامی است'
                        }
                    }
                },
                project_result: {
                    id: {
                        required: {
                            $validator: (val) => {
                                return (this.newRec || val > 0)
                            },
                            $message: 'انتخاب نتیجه پروژه الزامی است'
                        }
                    }
                },
                city: {
                    id: {
                        required: {
                            $validator: (val) => {
                                return (val > 0)
                            },
                            $message: 'انتخاب شهر پروژه الزامی است'
                        }
                    }
                },
                region: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'منطقه شهرداری الزامی است'
                    }
                },
                main_street: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'خیابان اصلی الزامی است'
                    },
                },
                address: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'آدرس پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'آدرس پروژه باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 250)
                        },
                        $message: 'آدرس پروژه باید حداکثر 250 کاراکتر باشد'
                    },
                },
                po_code: {
                    digits: {
                        $validator: (val) => {
                            return ((!val?.length || (val?.length && val?.length === 0)) || (val?.length && val?.length === 10))
                        },
                        $message: 'کد پستی پروژه باید دقیقا 10 رقم باشد'
                    },
                    numbers: {
                        $validator: (val) => {
                            return !val?.length || /^\d+$/.test(val)
                        },
                        $message: 'فقط ورود اعداد قابل قبول است'
                    },
                },
                user_percentage: {
                    min: {
                        $validator: (val) => {
                            return (val >= 0)
                        },
                        $message: 'درصد پورسانت بازاریاب نمی‌تواند کمتر از صفر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val <= 100)
                        },
                        $message: 'درصد پورسانت بازاریاب نمی‌تواند بیشتر از 100 باشد'
                    },
                    numbers: {
                        $validator: (val) => {
                            return /^[\.0-9]*$/.test(val)
                        },
                        $message: 'فقط ورود اعداد (صحیح یا اعشاری) قابل قبول است'
                    },
                },
                blocks: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'تعداد بلوک‌های پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val >= 0)
                        },
                        $message: 'تعداد بلوک‌های پروژه نمی‌تواند کمتر از صفر باشد'
                    },
                    numbers: {
                        $validator: (val) => {
                            return /^\d+$/.test(val)
                        },
                        $message: 'فقط ورود اعداد قابل قبول است'
                    },
                },
                units: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'تعداد واحدهای پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val >= 1)
                        },
                        $message: 'تعداد واحدهای پروژه نمی‌تواند کمتر از یک باشد'
                    },
                    numbers: {
                        $validator: (val) => {
                            return /^\d+$/.test(val)
                        },
                        $message: 'فقط ورود اعداد قابل قبول است'
                    },
                },
                floors: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'تعداد طبقات پروژه الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val >= 1)
                        },
                        $message: 'تعداد طبقات پروژه نمی‌تواند کمتر از یک باشد'
                    },
                    numbers: {
                        $validator: (val) => {
                            return /^\d+$/.test(val)
                        },
                        $message: 'فقط ورود اعداد قابل قبول است'
                    },
                },
            },
            note: {
                note: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'متن پیگیری الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'متن پیگیری باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 100)
                        },
                        $message: 'متن پیگیری باید حداکثر 1000 کاراکتر باشد'
                    },
                },
                project_status: {
                    required: {
                        $validator: (val) => {
                            return (val > 0)
                        },
                        $message: 'انتخاب وضعیت پروژه الزامی است'
                    }
                },
                project_result: {
                    required: {
                        $validator: (val) => {
                            return (val > 0)
                        },
                        $message: 'انتخاب نتیجه پروژه الزامی است'
                    }
                },
            },
            todo: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'عنوان کار الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'عنوان کار باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 100)
                        },
                        $message: 'عنوان کار باید حداکثر 100 کاراکتر باشد'
                    },
                },
                description: {
                    min: {
                        $validator: (val) => {
                            return (!val?.length || (val?.length && val?.length >= 10))
                        },
                        $message: 'توضیحات کار باید حداقل 10 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 500)
                        },
                        $message: 'توضیحات کار باید حداکثر 500 کاراکتر باشد'
                    }
                },
                flag: {
                    required: {
                        $validator: (val) => {
                            return (val && val.length > 0)
                        },
                        $message: 'انتخاب اولویت کار الزامی است'
                    }
                },
            },
            alarm: {
                title: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'عنوان یادآور الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 5)
                        },
                        $message: 'عنوان یادآور باید حداقل 5 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 100)
                        },
                        $message: 'عنوان یادآور باید حداکثر 100 کاراکتر باشد'
                    },
                },
                description: {
                    required: {
                        $validator: (val) => {
                            return (val?.length != 0)
                        },
                        $message: 'توضیحات یادآور الزامی است'
                    },
                    min: {
                        $validator: (val) => {
                            return (val?.length >= 10)
                        },
                        $message: 'توضیحات یادآور باید حداقل 10 کاراکتر باشد'
                    },
                    max: {
                        $validator: (val) => {
                            return (val?.length <= 500)
                        },
                        $message: 'توضیحات یادآور باید حداکثر 500 کاراکتر باشد'
                    }
                },
                alarm_date: {
                    required: {
                        $validator: (val) => {
                            return (val?.length && val?.length === 10)
                        },
                        $message: 'تاریخ یادآور الزامی است'
                    },
                },
                alarm_time: {
                    required: {
                        $validator: (val) => {
                            return (val?.length && val?.length === 5)
                        },
                        $message: 'زمان یادآور الزامی است'
                    },
                },
            }
        }
    },
    created() {
        let crmState = JSON.parse(localStorage?.getItem('crmState'))
        this.currentUser = crmState.userData
    },
    mounted() {
        const project_id = this.$route.params.project_id ?? null
        if (project_id != null) {
            const project = this.projects.find(item => {
                if (item.id == project_id) {
                    return item
                }
            })
            if (project) {
                this.editItem(project)
            } else {
                this.loading = true
                Requests.getSingleProject(project_id).then(res => {
                    if (res.status) {
                        this.manageExtData(res?.ext_data)
                        setTimeout(() => {
                            this.editItem(res.result)
                            this.loading = false
                        }, 1000);
                    } else {
                        this.$router.push({ name : 'pageNotFound' })
                    }
                })
            }
        } else {
            this.refreshTable(false, 12)
        }
        // this.getCurrentPosition(false)
    },
    watch: {
        active_tab(o, n) {
            if (this.active_tab==='comments' && o !== n) {
                setTimeout(() => {
                    this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollTopMax;
                }, 300);
                const commentInput = this.$refs.commentInput
            }
        },
        $route() {
            this.form={}
            this.newRec=false
            this.editing=false
            const project_id = this.$route.params.project_id ?? null
            if (project_id != null) {
                const project = this.projects.find(item => {
                    if (item.id == project_id) {
                        return item
                    }
                })
                if (project) {
                    this.editItem(project)
                } else {
                    this.loading = true
                    Requests.getSingleProject(project_id).then(res => {
                        if (res.status) {
                            this.manageExtData(res?.ext_data)
                            setTimeout(() => {
                                this.editItem(res.result)
                                this.loading = false
                            }, 1000);
                        } else {
                            this.$router.push({ name : 'pageNotFound' })
                        }
                    })
                }
            } else {
                this.refreshTable(false, 12)
            }
        }
    },
    methods: {
        refreshTable(filters = false, rows = 12) {
            if (!this.loading) {
                let projectFilters = null
                if (filters !== false) {

                }
                this.loading = true
                Requests.getProjectsList(projectFilters, rows).then(res => {
                    if (res?.status) {
                        this.projects = res?.result ?? [];
                        this.manageExtData(res?.ext_data)
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست پروژه‌ها', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست پروژه‌ها', { type: 'error' })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        manageExtData(ext) {
            this.types     = []
            this.statuses  = []
            this.results   = []
            this.contacts  = []
            this.positions = []
            this.cities    = []
            this.users     = []
            this.original_contacts = ext?.contacts || []
            this.original_cities   = ext?.cities || []
            this.original_users    = ext?.users || []
            this.default_status    = null
            this.default_result    = null
            for (let i = 0; i < ext?.statuses?.length; i++) {
                if (ext?.statuses[i].is_default) {
                    this.default_status = ext?.statuses[i]?.id || null
                }
            }
            for (let i = 0; i < ext?.results?.length; i++) {
                if (ext?.results[i].is_default) {
                    this.default_result = ext?.results[i]?.id || null
                }
            }
            this.types = (ext?.types || []).map(item => {
                return {
                    value: item.id,
                    text: item.title,
                    description: item.description
                }
            })
            this.statuses = (ext?.statuses || []).map(item => {
                return {
                    value: item.id,
                    text: item.title,
                    description: item.description
                }
            })
            this.results = (ext?.results || []).map(item => {
                return {
                    value: item.id,
                    text: item.title,
                    description: item.description
                }
            })
            this.contacts = (ext?.contacts || []).map(item => {
                let phone = ''
                if (item.details) {
                    for (let i = 0; i < item.details.length; i++) {
                        if (item.details[0]?.section === 'phone') {
                            phone = item.details[0]?.value
                            break
                        }
                    }
                }
                let name = item.name
                if (item.organization && item.organization.length) {
                    name += '('+item.organization+')'
                }
                if (phone.length > 0) {
                    name += ' - '+phone
                }
                return {
                    value: item.id,
                    text: name,
                }
            })
            this.positions = (ext?.positions || []).map(item => {
                return {
                    value: item.id,
                    text: item.title,
                    description: item.description
                }
            })
            this.cities = (ext?.cities || []).map(item => {
                return {
                    value: item.id,
                    text: item.province.title + ' - ' + item.title,
                }
            })
            this.users = (ext?.users || []).map(item => {
                return {
                    value: item.id,
                    text: item.display_name+' '+(item?.mobile || ''),
                }
            })
        },
        newItem() {
            let perm = false
            let message = ''
            if (this.editing) {
                if (this.permission.edit) {
                    perm = true
                } else {
                    message = 'شما مجوز ویرایش در این قسمت را ندارید!'
                }
            } else {
                if (this.permission.add) {
                    perm = true
                } else {
                    message = 'شما مجوز افزودن در این قسمت را ندارید!'
                }
            }
            if (perm) {
                if (this.default_status === null) {
                    message  = 'جدول وضعیت‌های پروژه خالی است یا وضعیتی به عنوان پیش‌فرض ثبت نشده است'
                } else if (!this.default_result === null) {
                    message  = 'جدول نتایج پروژه خالی است یا نتیجه‌ای به عنوان پیش‌فرض ثبت نشده است'
                }
            }
            this.note.project_status = this.default_status
            this.note.project_result = this.default_result
            if (message.length) {
                this.$helpers.notify('خطای دسترسی', message, {type: 'error'})
                return
            }
            this.newData()
        },
        newData() {
            this.form = {
                title: '',
                description: '',
                project_type: { id: 0, title: '' },
                project_status: { id: this.default_status, title: '' },
                project_result: { id: this.default_result, title: '' },
                user_percentage: 0.0,
                images: [],
                files: [],
                comments: [],
                notes: [],
                members: [],
                contacts: [],
                city: { id: 728, title: '' },
                region: '',
                main_street: '',
                aux1: '',
                aux2: '',
                alley1: '',
                alley2: '',
                address: '',
                po_code: '',
                lat: '',
                long: '',
                blocks: 1,
                units: 1,
                floors: 1,
                addTodo: false,
                addAlarm: false,
            }
            this.todo = {
                title: '',
                due_date: '',
                flag: 'عادی',
                description: '',
            }
            this.alarm = {
                title: '',
                description: '',
                alarm_date: '',
                alarm_time: '',
            }
            this.note = {
                note: '',
                project_status: null,
                project_result: null,
            }
            this.tabs = [
                {
                    id: 'contacts',
                    text: 'مخاطبین پروژه',
                },
                {
                    id: 'general',
                    text: 'اطلاعات کلی پروژه',
                },
                {
                    id: 'images',
                    text: 'تصاویر پروژه',
                },
                {
                    id: 'notes',
                    text: 'پیگیری‌ها',
                },
                {
                    id: 'other',
                    text: 'سایر',
                },
            ]
            this.active_tab  = 'contacts'
            this.newRec      = true
            this.editing     = false
            this.editingItem = []
            this.$nextTick(res => {
                this.getCurrentPosition(false)
            })
            this.comment = null
            this.savingComment = false
        },
        saveItem() {
            let perm = false
            let message = ''
            if (this.editing) {
                if (this.permission.edit) {
                    perm = true
                } else {
                    message = 'شما مجوز ویرایش در این قسمت را ندارید!'
                }
            } else {
                if (this.permission.add) {
                    perm = true
                } else {
                    message = 'شما مجوز افزودن در این قسمت را ندارید!'
                }
            }
            if (!perm) {
                this.$helpers.notify('خطای دسترسی', message, {type: 'error'})
                return false
            }
            if (!this.loading) {
                this.validator.$reset();
                this.validator.note.$reset();
                this.validator.todo.$reset();
                this.validator.alarm.$reset();
                this.validator.form.$touch()
                let formInvalid = this.validator.form.$invalid
                if (this.newRec) {
                    this.validator.note.$touch();
                    formInvalid = this.validator.note.$invalid || formInvalid
                }
                if (this.form.addTodo) {
                    this.validator.todo.$touch()
                    formInvalid = this.validator.todo.$invalid || formInvalid
                }
                if (this.form.addAlarm) {
                    this.validator.alarm.$touch()
                    formInvalid = this.validator.alarm.$invalid || formInvalid
                }
                if (formInvalid) {
                    this.$helpers.notify('خطای کاربر', 'خطاهی فرم را برطرف کنید', { type: 'error' })
                } else {
                    this.saving = true
                    let record = new FormData()
                    const contacts = JSON.stringify(this.form.contacts)
                    const members = JSON.stringify(this.form.members)
                    record.append('title', this.form.title)
                    record.append('description', this.form.description)
                    record.append('project_type_id', this.form.project_type.id)
                    record.append('project_status_id', this.form.project_status.id)
                    record.append('project_result_id', this.form.project_result.id)
                    record.append('user_percentage', this.form.user_percentage || 0.0)
                    record.append('members', members)
                    record.append('contacts', contacts)
                    record.append('city_id', this.form.city.id)
                    record.append('region', this.form.region || '')
                    record.append('main_street', this.form.main_street || '')
                    record.append('aux1', this.form.aux1 || '')
                    record.append('aux2', this.form.aux2 || '')
                    record.append('alley1', this.form.alley1 || '')
                    record.append('alley2', this.form.alley2 || '')
                    record.append('address', this.form.address || '')
                    record.append('po_code', this.form.po_code || '')
                    record.append('lat', this.form.lat || '')
                    record.append('long', this.form.long || '')
                    record.append('blocks', this.form.blocks || '')
                    record.append('units', this.form.units || '')
                    record.append('floors', this.form.floors || '')
                    if (this.editing) {
                        record.append('id', this.editingItem.id)
                    } else {
                        record.append('note', this.note.note)
                        record.append('project_status', this.note.project_status)
                        record.append('project_result', this.note.project_result)
                    }
                    this.files.forEach((file, index) => {
                        record.append("file-"+index+1, file);
                    });
                    if (this.form.addTodo) {
                        record.append('addTodo', true)
                        record.append('todo_id', this.todo?.id || null)
                        record.append('todo_title', this.todo.title)
                        record.append('todo_due_date', this.todo.due_date)
                        record.append('todo_flag', this.todoFlags.indexOf(this.todo.flag))
                        record.append('todo_description', this.todo.description)
                    }
                    if (this.form.addAlarm) {
                        record.append('addAlarm', true)
                        record.append('alarm_id', this.alarm?.id || null)
                        record.append('alarm_title', this.alarm.title)
                        record.append('alarm_date', this.alarm.alarm_date)
                        record.append('alarm_time', this.alarm.alarm_time)
                        record.append('alarm_description', this.alarm.description)
                    }
                    Requests.saveProject(record).then(res => {
                        if (res?.status) {
                            if (this.editing) {
                                for (let i = 0; i < this.projects.length; i++) {
                                    if (this.projects[i].id === this.editingItem.id) {
                                        // this.projects[i] = res.result
                                        break;
                                    }
                                }
                            } else {
                                // this.projects.unshift(res.result)
                                // this.EventBus.emit('projectInserted', res.result)
                            }
                            // this.$helpers.notify(res?.message || 'پروژه مورد نظر با موفقیت ذخیره شد')
                        } else {
                            // this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره پروژه', { type: 'error' })
                        }
                    }).catch(err => {
                        // this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره پروژه', { type: 'error' })
                    }).finally(res => {
                        // this.validator.$reset();
                        // this.saving = false
                        // this.loading = false
                        // this.editing = false
                        // this.newItem()
                        // this.$router.push({ name : 'admin.projects' })
                    })
                }
            }
        },
        editItem(item) {
            if (!this.permission.edit) {
                this.$helpers.notify('خطای دسترسی', 'شما مجوز ویرایش در این قسمت را ندارید!', {type: 'error'})
                return false
            }

            this.validator.$reset();

            this.newItem()

            this.tabs = [
                {
                    id: 'general',
                    text: 'اطلاعات کلی پروژه',
                },
                {
                    id: 'contacts',
                    text: 'مخاطبین پروژه',
                },
                {
                    id: 'images',
                    text: 'تصاویر پروژه',
                },
                {
                    id: 'files',
                    text: 'پیوست‌های پروژه',
                },
                {
                    id: 'comments',
                    text: 'دیدگاه‌ها',
                },
                {
                    id: 'notes',
                    text: 'پیگیری‌ها',
                },
                {
                    id: 'other',
                    text: 'سایر',
                },
            ]

            this.newRec = false
            this.editingItem = item
            this.editing = true
            let images = []
            for (let i = 0; i < item.images.length; i++) {
                if (item.images[i].id > 0) {
                    images.push(item.images[i])
                }
            }
            this.form = {
                title: item.title,
                description: item.description,
                project_type: item.project_type,
                project_status: item?.last_note?.project_status,
                project_result: item?.last_note?.project_result,
                user_percentage: item.user_percentage || 0.0,
                images: images,
                files: item.files || [],
                comments: item.comments || [],
                notes: item.notes || [],
                members: item.members || [],
                contacts: item.contacts || [],
                city: item.city,
                region: item.region || '',
                main_street: item.main_street || '',
                aux1: item.aux1 || '',
                aux2: item.aux2 || '',
                alley1: item.alley1 || '',
                alley2: item.alley2 || '',
                address: item.address || '',
                po_code: item.po_code || '',
                lat: item.lat || 29.6151,
                long: item.long || 52.5452,
                blocks: item.blocks || 1,
                units: item.units || 1,
                floors: item.floors || 1,
                addTodo: item.addTodo || false,
            }
            if (item?.todo?.id) {
                this.form.addTodo = true
                this.todo = {
                    id: item?.todo?.id,
                    title: item?.todo?.title,
                    due_date: item?.todo?.due_date,
                    flag: this.todoFlags[item?.todo?.flag],
                    description: item?.todo?.description,
                }
            }
            if (item?.alarm?.id) {
                this.form.addAlarm = true
                this.alarm = {
                    title: item?.alarm?.title,
                    description: item?.alarm?.description,
                    alarm_date: item?.alarm?.alarm_date,
                    alarm_time: item?.alarm?.alarm_time,
                }
            }
            this.$nextTick(res => {
                this.mapOptions.center = [item.long, item.lat]
            })
        },
        deleteItem(item_id) {
            this.$swal.fire({
                title: "حذف پروژه",
                html: "حذف پروژه یک عملیات غیرقابل بازگشت بوده و پس از حذف، دسترسی به اطلاعات این رکورد غیرممکن خواهد بود.<span class='ml-2 has-text-danger has-text-weight-bold'>پروژه مورد نظر حذف گردد؟</span>",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "بلی",
                cancelButtonText: "خیر",
                showCloseButton: true,
            }).then(result => {
                return
                if (result.value) {
                    this.saving = true
                    Requests.deleteProject(item_id).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.projects.length; i++) {
                                if (this.projects[i].id == item_id) {
                                    this.projects.splice(i, 1)
                                    break;
                                }
                            }
                            this.$helpers.notify('پروژه مورد نظر با موفقیت حذف گردید.')
                        } else {
                            this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف پروژه مورد نظر با مشکل مواجه شد...', { type: 'error' })
                        }
                    }).catch(err => {
                        console.log(err);
                    }).then(res => {
                        this.saving = false
                    })
                }
            });
        },
        getCurrentPositionFromMap() {
            if (!this.gettingLocationInfo) {
                this.gettingLocationInfo = true
                const position = this.$refs?.view?.view?.values_?.center ?? this.mapOptions.center

                Requests.reverseGeocode(position).then(res => {
                    let addressArray = (res?.display_name || '').split(', ').reverse()
                    this.form.region = res?.address?.suburb || res?.address?.town || ''
                    this.form.main_street = res?.address?.neighbourhood || null
                    this.form.aux1 = res?.address?.road || null
                    this.form.aux2 = null
                    this.form.alley1 = null
                    this.form.alley2 = null
                    this.form.po_code = res?.address?.postcode || ''
                    this.form.city = { id: 728, title: '' }
                    this.original_cities.find(item => {
                        if (item.title.localeCompare(res?.address?.city) === 0 || item.title.localeCompare(res?.address?.town?.replace('شهر ','')) === 0) {
                            this.form.city.id = item.id
                        }
                    })
                    let i = 0;
                    while (addressArray.length > 0) {
                        if (this.form.region.localeCompare(addressArray[i]) === 0) {
                            addressArray.splice(i, 1)
                            break;
                        }
                        addressArray.splice(i, 1)
                    }
                    this.form.address = addressArray.join('، ')
                    this.form.region = this.form.region.replace("منطقه ", "");
                    this.form.po_code = this.form.po_code.replace(/-/g, "");
                }).catch(err => {
                    this.$helpers.notify('خطا', 'خطا هنگام دریافت اطلاعات تکمیلی موقعیت مورد نظر.', { type: 'error' })
                }).finally(res => {
                    this.gettingLocationInfo = false
                })
            }
        },
        getCurrentPosition(centerMap = false) {
            if (!navigator.geolocation) {
                axios.get('https://api.ipgeolocation.io/ipgeo?apiKey=0d3a52966a2b443fbbef13ece6b436db&fields=geo').then(res => {
                    this.currentPosition.lat = res?.data?.latitude
                    this.currentPosition.long = res?.data?.longitude
                }).catch(err => {
                    this.$helpers.notify('خطا', 'خطا در دریافت موقعیت مکانی کاربر', { type: 'error' })
                })
                return
            } else {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.currentPosition.lat = position.coords.latitude;
                    this.currentPosition.long = position.coords.longitude;
                }, (err) => {
                    axios.get('https://api.ipgeolocation.io/ipgeo?apiKey=0d3a52966a2b443fbbef13ece6b436db&fields=geo').then(res => {
                        this.currentPosition.lat = res?.data?.latitude
                        this.currentPosition.long = res?.data?.longitude
                        if (centerMap) {
                            this.mapOptions.center = [this.currentPosition.long, this.currentPosition.lat]
                            this.mapOptions.zoom = 18
                            this.$refs.map.updateSize()
                            const view = this.$refs.view
                        }

                        const position = this.$refs?.view?.view?.values_?.center ?? this.mapOptions.center
                        this.form.lat = position[1]
                        this.form.long = position[0]
                    }).catch(err => {
                        this.$helpers.notify('خطا', 'خطا در دریافت موقعیت مکانی کاربر', { type: 'error' })
                    })
                }, {
                    enableHighAccuracy: true,
                })
            }

            if (centerMap) {
                this.mapOptions.center = [this.currentPosition.long, this.currentPosition.lat]
                this.mapOptions.zoom = 18
                this.$refs.map.updateSize()
                const view = this.$refs.view
            }

            const position = this.$refs?.view?.view?.values_?.center ?? this.mapOptions.center
            this.form.lat = position[1]
            this.form.long = position[0]
        },
        featureSelected(e) {
            if (e?.coordinate) {
                this.mapOptions.center = e.coordinate
                this.form.long = this.mapOptions.center[0]
                this.form.lat = this.mapOptions.center[1]
            }
        },
        addMember(user_id) {
            if (user_id) {
                const user_exist = this.form.members.find(item => {
                    return item.id === user_id
                })
                if (!user_exist) {
                    const user = this.original_users.find(item => {
                        return item.id === user_id
                    })
                    if (user) {
                        this.form.members.push(user)
                    }
                }

                this.$refs.memberSelect.clearSelect()
            }
        },
        removeMember(user_id) {
            if (user_id) {
                this.form.members = this.form.members.filter(item => {
                    return item.id !== user_id
                })
            }
        },
        addContact() {
            if (this.tempContact && this.tempPosition) {
                const contact_exists = this.form.contacts.find(item => {
                    return item.id === this.tempContact && item.position.id === this.tempPosition
                })
                if (!contact_exists) {
                    const contact = this.original_contacts.find(item => {
                        return item.id === this.tempContact
                    })
                    if (contact) {
                        const position = this.positions.find(item => {
                            return item.value === this.tempPosition
                        })
                        this.form.contacts.push({
                            ...contact,
                            position: {
                                id: position.value,
                                title: position.text,
                            }
                        })
                    }
                }
                this.tempContact  = null
                this.tempPosition = null
            }
        },
        addNewContact() {
            this.contactForm = new Form(this.contact_template)
            this.$refs.contactForm.modalOpen()
        },
        saveNewContact() {
            if (!this.saving) {
                this.$refs.newContactform.validateAll().then(res => {
                    if (res) {
                        const newContact = {
                            prefix: 1,
                            fName: this.contactForm.fName.value,
                            lName: this.contactForm.lName.value,
                            additional_infos: [
                                {
                                    section: 'phone',
                                    title: 'تلفن همراه',
                                    label: 'تلفن همراه',
                                    value: this.contactForm.phone.value,
                                }
                            ],
                        }
                        Requests.saveContact(newContact).then(res => {
                            if (res?.status) {
                                this.contacts.push({
                                    value: res.result.id,
                                    text: (this.contactForm.lName.value + ' ' + this.contactForm.fName.value).trim() + ' - '+this.contactForm.phone.value,
                                })
                                this.original_contacts.push(res.result)
                                this.$helpers.notify('مخاطب جدید با موفقیت ثبت شد.');
                                this.tempContact = res.result.id
                                this.$refs.contactForm.modalClose()
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره مخاطب مورد نظر', { type: 'error' })
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای غیر منتظره', err?.response?.data?.message || 'بروز خطای غیرمنتظره هنگام ذخیره مخاطب مورد نظر', { type: 'error' })
                        }).finally(res => {
                            this.saving = false
                        })
                    }
                })
            }
        },
        removeContact(contact_id, position_id) {
            if (contact_id) {
                for (let i = 0; i < this.form.contacts.length; i++) {
                    if (this.form.contacts[i].id === contact_id && this.form.contacts[i].position.id === position_id) {
                        this.form.contacts.splice(i, 1)
                        break;
                    }
                }
            }
        },
        fileDropped(e) {
            this.files = e
        },
        changeAlt(image, type=1) {
            if (this.updatingAlts !== image.id) {
                const target = (type===1 ? 'تصویر' : 'پیوست')
                const targetType = (type===1 ? 'images' : 'files')
                if (image.alt?.trim()?.length > 1) {
                    this.updatingAlts = image.id
                    const formData = {
                        image: image.id,
                        alt: image.alt,
                        type: image.type,
                    }
                    Requests.renameAlt(formData).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.form[targetType].length; i++) {
                                if (this.form[targetType][i].id === image.id) {
                                    this.form[targetType][i].alt = image.alt
                                    this.form[targetType][i].type = image.type
                                    for (let k = 0; k < this.projects.length; k++) {
                                        if (this.projects[k].id === this.editingItem.id) {
                                            this.projects[k].images = res.result.images
                                            this.projects[k].covers = res.result.covers
                                            this.projects[k].files = res.result.files
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ویرایش عنوان/وضعیت '+target, { type: 'error' })
                        }
                    }).catch(err => {
                        this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام ویرایش عنوان/وضعیت '+target, { type: 'error' })
                    }).finally(() => {
                        this.updatingAlts = null
                        this.loading = false
                    })
                } else {
                    this.$helpers.notify('خطا', 'عنوان '+target+' نمی‌تواند خالی باشد', { type: 'error' })
                }
            }
        },
        deleteImage(image, type = 1) {
            if (this.updatingAlts === null) {
                const target = (type===1 ? 'تصویر' : 'پیوست')
                const targetType = (type===1 ? 'images' : 'files')
                this.$swal.fire({
                    title: "حذف "+target,
                    text: target+" مورد نظر حذف گردد؟",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "بلی",
                    cancelButtonText: "خیر",
                    showCloseButton: true,
                }).then(result => {
                    if (result.value) {
                        this.updatingAlts = image
                        Requests.deleteImage({image: image}).then(res => {
                            if (res?.status) {
                                for (let i = 0; i < this.form[targetType]?.length; i++) {
                                    if (this.form[targetType][i].id === image) {
                                        this.form[targetType].splice(i, 1);
                                        for (let k = 0; k < this.projects.length; k++) {
                                            if (this.projects[k].id === this.editingItem.id) {
                                                this.projects[k].images = res.result.images
                                                this.projects[k].covers = res.result.covers
                                                this.projects[k].files = res.result.files
                                                break;
                                            }
                                        }
                                        this.$helpers.notify(target+' مورد نظر با موفقیت حذف گردید.')
                                        break
                                    }
                                }
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام حذف '+target+' مورد نظر', {type: "error"})
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام '+target+' تصویر مورد نظر', {type: 'error'})
                        }).finally(res => {
                            this.uploadProgress = 0
                            this.updatingAlts = null
                        })
                    }
                });
            }
        },
        previewImage(image) {
            this.imageToPreview = image
            this.$refs.previewImage.modalOpen()
        },
        downloadImage(target) {
            if (target && target?.filename) {
                Requests.downloadFile(target.id).then(res => {
                    if (res) {
                        let a = document.createElement('a')
                        let url = window.URL.createObjectURL(new Blob([res], {type: target.mime}));
                        a.href = url
                        a.download  = this.editingItem.title+' '+target.alt+'.'+(target.ext ?? target.filename.split('.').pop())
                        a.innerText = a.download
                        a.download  = a.download.toLowerCase().replace(/ /g, "-")
                        document.body.appendChild(a)
                        a.click();
                        document.body.removeChild(a)
                    }
                }).catch(err => {
                    console.log(err)
                })
            }
        },
        getFileSize(size) {
            return this.$helpers.humanReadableFileSize(size) ?? 'صفر بایت'
        },
        handleFileSelect(event) {
            if (!this.saving && !this.loading) {
                this.attachment = event.target.files[0]

                if (this.attachment.size / 1024 > 51200) {
                    this.$helpers.notify('اخطار', 'حجم فایل انتخاب شده نباید بزرگتر از 50 مگابایت باشد', { type: 'error' })
                    this.attachment = null
                    return
                }
            }
        },
        uploadSelectedFile() {
            if (!this.saving && !this.loading && this.updatingAlts === null) {
                const file = this.attachment

                if (file.size / 1024 > 51200) {
                    this.$helpers.notify('اخطار', 'حجم فایل انتخاب شده نباید بزرگتر از 50 مگابایت باشد', { type: 'error' })
                    return
                }

                this.updatingAlts = -1

                let formData = new FormData()
                formData.append('project', this.editingItem.id)
                formData.append('attachment', file)
                Requests.addProjectAttachment(formData, (progressEvent) => {
                    this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                }).then(res => {
                    if (res?.status) {
                        if (this.editing) {
                            for (let i = 0; i < this.projects.length; i++) {
                                if (this.projects[i].id === this.editingItem.id) {
                                    this.projects[i] = res.result
                                    this.form.files = res?.result?.files ?? []
                                    break;
                                }
                            }
                        } else {
                            this.projects.push(res.result)
                        }
                        this.$helpers.notify(res?.message || 'پروژه مورد نظر با موفقیت ذخیره شد')
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره پروژه', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره پروژه', { type: 'error' })
                }).finally(res => {
                    this.uploadProgress = 0
                    this.updatingAlts = null
                    this.attachment = null
                })
            }
        },
        changePrevUser(id) {
            this.prevUser = id
        },
        saveComment() {
            if (!this.savingComment && this.comment != null && this.comment.length > 0) {
                this.savingComment = true
                Requests.addProjectComment({project: this.editingItem.id, comment: this.comment}).then(res => {
                    if (res?.status) {
                        for (let i = 0; i < this.projects.length; i++) {
                            if (this.projects[i].id === this.editingItem.id) {
                                this.form.comments = res.result
                                this.projects[i].comments = res.result
                                break;
                            }
                        }
                        setTimeout(() => {
                            this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollTopMax;
                        }, 300);
                        this.$helpers.notify(res?.message || 'دیدگاه شما ارسال شد')
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره دیدگاه', { type: 'error' })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره دیدگاه', { type: 'error' })
                }).finally(res => {
                    this.savingComment = null
                    this.comment = null
                })
            }
        },
        saveNote() {
            if (!this.savingComment)
            this.validator.note.$touch();
            if (!this.validator.note.$invalid) {
                this.savingComment = true
                const formData = {
                    project: this.editingItem.id,
                    note: this.note.note,
                    status: this.note.project_status,
                    result: this.note.project_result,
                }
                Requests.addProjectNote(formData).then(res => {
                    if (res?.status) {
                        for (let i = 0; i < this.projects.length; i++) {
                            if (this.projects[i].id === this.editingItem.id) {
                                this.form.notes = res.result
                                this.projects[i].notes = res.result
                                this.projects[i].last_note = res.result[0]
                                break;
                            }
                        }
                        this.$helpers.notify(res?.message || 'پیگیری شما ثبت شد')
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره پیگیری', { type: 'error' })
                    }
                }).catch(err => {
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره پیگیری', { type: 'error' })
                }).finally(res => {
                    this.savingComment = null
                    this.note = {
                        note: '',
                        project_status: null,
                        project_result: null,
                    }
                    this.validator.note.$reset()
                })
            }
        },
        deleteTodo(todo) {
            this.$swal.fire({
                title: "حذف کار",
                html: "کار مورد نظر حذف گردد؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "بلی",
                cancelButtonText: "خیر",
                showCloseButton: true,
            }).then(result => {
                if (result.value) {
                    this.savingComment = true
                    Requests.deleteTodo(todo).then(res => {
                        if (res?.status) {
                            this.$helpers.notify(res?.message || 'کار مورد نظر حذف شد')
                            this.todo = {
                                title: '',
                                due_date: '',
                                flag: 'عادی',
                                description: '',
                            }
                            this.form.addTodo = false
                        } else {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطا هنگام حذف کار مورد نظر', { type: 'error' })
                        }
                    }).finally(res => {
                        this.savingComment = false
                    })
                }
            });
        },
    }
};
</script>

<style scoped>

.no-resize {
    resize: none;
}
.snack .snack-text {
    top: -2px;
}
.snack .snack-action {
    top: 0;
}

.image-thumb {
    position: relative;
    aspect-ratio: 1/1;
    background-image: linear-gradient(to top, #d5d5d5, #fff);
    padding: 6px 6px 0 6px;
    border-radius: 7%;
    border: 1px dashed #ededed;
    transition: 200ms all ease-in-out;
}
.image-thumb .image {
    width: 100%;
    height: calc(100% - 40px);
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    overflow: hidden;
    border-radius: 7%;
    cursor: zoom-in;
    background-color: rgba(221, 228, 253, 0.35);
    margin-bottom: 5px;
    pointer-events: auto;
    transition: 200ms all ease-in-out;
}
.image-thumb .image.cursor-normal {
    cursor: default;
}

.image-thumb:hover {
    box-shadow: 0 0 5px #646464;
    scale: 1.05;
}
.image:hover {
    box-shadow: 0 0 2px #161c76;
}
.image-thumb .input,
.image-thumb .control .button {
    padding: 2px 5px !important;
    height: 30px;
}
.is-small.textarea, .is-small.input {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}
.field.has-addons .control:last-child:not(:only-child) .button {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.image-thumb button {
    position: absolute;
    background-color: rgba(240, 240, 240, 0.7);
    border: none;
    color: #e62965;
    width: 32px;
    height: 32px;
    padding: 4px 2px 0 2px;
    border-radius: 50%;
    left: -12px;
    top: -12px;
    transition: all 200ms ease-in-out;
    box-shadow: 0 0 5px #cfcfcf;
    z-index: 3;
}
.image-thumb button:hover {
    background-color: #e62965;
    color: rgba(250, 250, 250, 1);
}
.image-thumb .image-type span:nth-child(1){
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
}
.image-thumb .image-type span:nth-child(2){
    border-bottom-left-radius: 3px;
    border-top-left-radius: 3px;
}
.message-field-wrapper .control input {
    padding-right: 20px;
    padding-left: 100px;
}
.message-field-wrapper {
    width: 100%;
}
.chat-body {
    width: 100%;
    height: calc(100% - 70px);
    padding: 0 30px 0 30px;
    scroll-behavior: smooth;
}
.chat-body .no-messages {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}
.message-field-wrapper .control .send-message .button {
    width: 37px !important;
    height: 37px !important;
    max-width: 37px !important;
    max-height: 37px !important;
    min-width: 37px !important;
    min-height: 37px !important;
    border-radius: 50% !important;
    padding: 2px 2px 2px 2px !important;
}
.message-field-wrapper .control .send-message .button img {
    margin-right:4px;
}
.visible {
    visibility: visible !important;
}
.is-note {
    border: 1px dashed #9a9a9a;
    margin: 10px 0;
}
.is-note:nth-child(odd) {
    background-color: rgb(245, 245, 245);
}
.note-meta {
    width: 145px;
}
.note-comment {
    margin: 5px 30px 5px 5px;
    padding: 5px 15px;
    border-right: 5px solid rgb(215, 215, 215);
    background-image: linear-gradient(to left, rgba(225, 225, 225, 0.35), transparent);
    width: 100%;
    min-height: 80px;
    border-bottom-left-radius: 10px;
    border-top-left-radius: 10px;
}
.current-status {
    padding: 20px 0 4px 0;
    width: 110px;
    color: #fff;
    font-weight: bold;
    position: absolute;
    top: -12px;
    left: -38px;
    transform: rotate(-35deg);
    text-align: center;
    display: inline-block;
}
</style>

<style>
.vel-img-title {
    padding: 7px 15px;
    background-color: #FFF !important;
    border-radius: 5px !important;
    color: #0A0A0A !important;
    font-size: 1rem !important;
}
</style>
