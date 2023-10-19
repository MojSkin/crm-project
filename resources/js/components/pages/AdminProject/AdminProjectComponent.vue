<template>
    <AdminLayoutComponent>
        <template #title>پروژه‌ها</template>
        <b-card no-footer no-header class="overflow-visible">
            <div class="columns is-multiline">
                <div class="column is-12 is-flex align-items-center flex-wrap">
                    <button @click="newItem" v-if="permission.add" class="button is-primary font-weight-bolder mr-2 is-flex align-items-center" :disabled="loading || saving">
                        <i class="fal fa-plus mr-2"></i> <span>پروژه جدید</span>
                    </button>
                    <button @click="refreshTable(false)" class="button is-solid hint--light hint--rounded hint--top is-flex align-items-center" v-if="!loading && !saving && !newRec && !editing" data-hint="دریافت مجدد فهرست نتایج پروژه">
                        <i class="fal fa-undo"></i>
                    </button>
                    <span v-if="loading">
                        <i class="fas fa-spinner-third fa-spin" v-if="loading"></i>
                    </span>
                </div>
            </div>
        </b-card>
        <b-card no-header :no-footer="!newRec && !editing" :body-class="{'px-0': newRec || editing}" class="overflow-visible position-relative">
            <div class="columns is-multiline is-flex" v-if="!newRec && !editing">
                <div class="column is-12">
                    <b-accordion
                        :headers="[
                    {
                        id: 'searchPanel',
                        title: 'جستجوی پیشرفته',
                    }
                ]"
                        is-exclusive
                        class="mb-5"
                    >
                        <template #content-searchPanel>
                            <div class="columns is-multiline">
                                <!--                        <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">-->
                                <!--                            <b-input-->
                                <!--                                label='عنوان مخاطب:'-->
                                <!--                            >-->
                                <!--                                <template #input>-->
                                <!--                                    <b-select-->
                                <!--                                        autoclose-->
                                <!--                                        clearable-->
                                <!--                                        label='عنوان مخاطب:'-->
                                <!--                                        :options="prefixes"-->
                                <!--                                        v-model="filters.prefix"-->
                                <!--                                    >-->
                                <!--                                        <template #label="item">-->
                                <!--                                            {{ item.title }}-->
                                <!--                                        </template>-->
                                <!--                                    </b-select>-->
                                <!--                                </template>-->
                                <!--                            </b-input>-->
                                <!--                        </div>-->
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                    <b-input
                                        label='نام مخاطب:'
                                        v-model="filters.fName"
                                        placeholder="نام مخاطب"
                                    ></b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                    <b-input
                                        label='نام خانوادگی مخاطب:'
                                        v-model="filters.lName"
                                        placeholder="نام خانوادگی مخاطب"
                                    ></b-input>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                    <b-input
                                        label='نام مستعار:'
                                        v-model="filters.nickname"
                                        placeholder="نام مستعار"
                                    ></b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-4-widescreen is-4-desktop">
                                    <b-input
                                        label='عنوان:'
                                        v-model="filters.title"
                                        placeholder="عنوان"
                                    ></b-input>
                                </div>
                                <div class="column is-12-mobile is-12-tablet is-4-widescreen is-4-desktop">
                                    <b-input
                                        label='نام تجاری:'
                                        v-model="filters.org"
                                        placeholder="نام تجاری"
                                    ></b-input>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                                    <b-input
                                        label='تلفن:'
                                        v-model="filters.phone"
                                        placeholder="تلفن"
                                    ></b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                                    <b-input
                                        label='ایمیل:'
                                        v-model="filters.email"
                                        placeholder="ایمیل"
                                    ></b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                                    <b-input
                                        label='آدرس:'
                                        v-model="filters.address"
                                        placeholder="آدرس"
                                    ></b-input>
                                </div>
                                <div class="column is-6-mobile is-6-tablet is-3-widescreen is-3-desktop">
                                    <b-input
                                        label='سایر مشخصات:'
                                        v-model="filters.other"
                                        placeholder="سایر مشخصات"
                                    ></b-input>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-12 is-flex">
                                    <button class="button is-success mr-2 ml-auto" @click="refreshTable(filters)" :disabled="loading">
                                        <i class="fal fa-filter" v-if="!loading"></i>
                                        <i class="fal fa-spinner-third fa-spin" v-else></i>
                                        <span class="ml-2">اعمال شرایط</span>
                                    </button>
                                    <button class="button" @click="cancelFilter">پاک کردن شرایط</button>
                                </div>
                            </div>
                        </template>
                    </b-accordion>
                </div>
                <div class="column is-12-mobile is-6-tablet is-4-desktop is-3-widescreen" v-for="project in projects" :key="'project-'+project?.id">
                    <div class="card h-card">
                        <div class="card-image is-relative">
                            <div class="is-4by3">
                                <Carousel items-to-show="1" snap-align="center" :wrap-around="true" dir="rtl" :loop="false" transition="300" :autoplay="(project?.covers && project?.covers.length > 1) ? 3000 : false">
                                    <Slide v-for="image in project?.covers" :key="image?.id">
                                        <div class="carousel__item">
                                            <div class="gallery-item">
                                                <img :src="image?.filename" :alt="image?.alt">
                                            </div>
                                        </div>
                                    </Slide>
                                </Carousel>
                            </div>
                            <span v-text="project?.title" class="is-font-alt has-text-grey is-rounded rounded-1 is-weight-900" :style="{position: 'absolute', top: '5px', right: '5px', width: 'calc(100% - 10px)', padding: '3px 5px', backgroundColor: 'rgba(250, 250, 250, 0.85)', boxShadow: '0 0 4px rgba(0, 0, 0, 0.95)'}"/>
                            <span v-text="project?.project_type?.title" class="tag" :style="{position: 'absolute', bottom: '5px', right: '5px', backgroundColor: project?.project_type?.bgColor, color: project?.project_type?.textColor}"/>
                            <span v-text="$helpers.jDate(project.created_at)" class="tag" :style="{position: 'absolute', bottom: '5px', left: '5px'}"/>
                        </div>
                        <div class="card-content p-3">
                            <div class="media-flex-center no-margin">
                                <div class="w-100 is-flex">
                                    <span v-text="project?.last_note?.project_status?.title" class="tag" :style="{backgroundColor: project?.last_note?.project_status?.bgColor, color: project?.last_note?.project_status?.textColor}"/>
                                    <span v-text="project?.last_note?.project_result?.title" class="tag ml-auto" :style="{backgroundColor: project?.last_note?.project_result?.bgColor, color: project?.last_note?.project_result?.textColor}"/>
                                </div>
                            </div>
                            <div class="inner-content mt-2 mb-1 is-relative">
                                <p class="has-text-justified" v-text="project?.description"/>
                                <div class="content-gradient"/>
                            </div>
                            <hr class="my-2 divider">
                            <div class="w-100 is-flex is-align-items-center">
                                <i class="fal fa-location mr-1 has-text-info-dark"></i>
                                <div class="small">
                                    <span>{{ project?.city?.province?.title }}</span> - <span>{{ project?.city?.county?.title }}</span><span v-if="project?.city?.title != project?.city?.county?.title"> - {{ project?.city?.title }}</span> - <span>منطقه {{ project?.region }}</span>
                                </div>
                            </div>
                            <div class="w-100 is-flex is-align-items-center">
                                <i class="fal fa-user-circle mr-1 has-text-info-dark"></i><span class="small" v-text="project?.user?.display_name ?? ''"/>
                                <a class="ml-auto action-link small" @click="editItem(project)">جزییات</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <b-overlay :show="saving || loading" :loader-text="'در حال ذخیره پروژه، لطفا کمی صبور باشید... '+uploadProgress+'%'" v-else>
                <b-tabs v-model="active_tab" :headers="tabs">
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
                            <div class="columns is-multiline is-flex">
                                <div class="column is-12-mobile is-6-tablet is-4-desktop is-4-widescreen">
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
                                <div class="column is-2-mobile is-1-tablet is-1-desktop is-1-widescreen">
                                    <button class="button border-0 p-3 has-background-info-dark" @click="addContact">
                                        <i class="fal fa-plus has-text-info-light"></i>
                                    </button>
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
                                    <b-dropzone v-model="files" @dropped="fileDropped" :is-busy="saving" accept="image/jpeg, image/png" />
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
                                        <button @click.stop="deleteImage(image.id)">
                                            <i class="fal fa-trash-alt"></i>
                                        </button>
                                        <div class="image" :style="{ 'background-image': 'url('+image.filename+')' }" @click="previewImage(image)"></div>
                                        <div class="image-type w-100 d-flex pb-1">
                                            <span class="w-100 has-text-centered is-clickable" :class="image.type==='PROJECT_COVER' ? 'has-background-primary has-text-light' : 'has-background-light'" @click="image.type='PROJECT_COVER'">کاور</span>
                                            <span class="w-100 has-text-centered is-clickable" :class="image.type==='PROJECT_IMAGE' ? 'has-background-primary has-text-light' : 'has-background-light'" @click="image.type='PROJECT_IMAGE'">تصویر</span>
                                        </div>
                                        <div class="field has-addons pb-1" :class="{ 'is-disabled': updatingAlts === image.id }">
                                            <div class="control is-expanded">
                                                <input v-model="image.alt" class="input is-small" type="text" placeholder="عنوان تصویر" :disabled="updatingAlts === image.id" @keydown.enter="changeAlt(image)">
                                            </div>
                                            <div class="control">
                                                <a class="button is-light is-small is-clickable" v-if="updatingAlts != image.id" @click="changeAlt(image)">
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
                                <div class="column is-6 pb-2">
                                    <div class="file is-boxed is-flex-direction-column">
                                        <label class="file-label is-block">
                                            <input class="file-input" type="file" @change="handleFileSelect" :multiple="false" accept=".zip, .rar, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .mp3">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fal fa-upload"></i>
                                                </span>
                                                <span class="file-label">انتخاب کنید...</span>
                                            </span>
                                        </label>
                                        <span class="small is-block">
                                            <span class="mr-2">فرمت‌های مورد قبول:</span><bdi>zip, rar, pdf, doc, docx, xls, xlsx, ppt, pptx, mp3</bdi>
                                        </span>
                                    </div>
                                </div>
                                <div class="column is-6 pb-2">
                                    <button class="button is-info is-light">
                                        <i class="fal fa-upload" v-if="!saving"></i>
                                        <i class="fal fa-spinner-third fa-spin" v-else></i>
                                        <span class="ml-2">افزودن فایل به پروژه</span>
                                    </button>
                                </div>
                            </div>
                            <div class="columns is-multiline" v-for="file in form.files">
                                <div class="column is-6 pb-2">
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
                <button class="button" @click="form={}; newRec=false; editing=false">انصراف</button>
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
                <img :src="imageToPreview.filename" :alt="imageToPreview.alt" class="is-rounded rounded-3 mb-3">
                <span v-text="imageToPreview.alt"/>
            </div>
            <template #footer-left>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="دانلود تصویر"><i @click="downloadImage()" class="fas fa-download has-text-success"></i></span>
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="بستن پنجره"><i @click="imageToPreview: null; $refs.previewImage.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>

</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import 'vue3-openlayers/styles.css';

export default {
    name: "AdminProjectComponent",
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
            gettingLocationInfo: false,
            form: {
                title: '',
                description: '',
                project_type: { id: 0, title: '' },
                project_status: { id: 0, title: '' },
                project_result: { id: 0, title: '' },
                user_percentage: 0,
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
            },
            default_status: null,
            default_result: null,
            uploadProgress: 0,
            files: [],
            fileTypes: {
                zip:  'fa-file-archive',
                rar:  'fa-file-archive',
                doc:  'fa-file-word',
                docx: 'fa-file-word',
                xls:  'fa-file-excel',
                xlsx: 'fa-file-excel',
                ppt:  'fa-file-powerpoint',
                pptx: 'fa-file-powerpoint',
                pdf:  'fa-file-pdf',
                mp3:  'fa-file-music',
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
            active_tab: 'general',
            tabs: [
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
                    id: 'notes',
                    text: 'پیگیری‌ها',
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
                                return (val > 0)
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
            }
        }
    },
    created() {
        this.refreshTable(false)
    },
    mounted() {
        this.getCurrentPosition(false)
    },
    methods: {
        refreshTable(filters = false) {
            if (!this.loading) {
                if (filters !== false) {

                }
                this.loading = true
                Requests.getProjectsList().then(res => {
                    if (res?.status) {
                        this.projects = res?.result ?? [];
                    } else {
                        this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام دریافت فهرست پروژه‌ها', { type: 'error' })
                    }
                    if (filters === false) {
                        Requests.getProjectsExtData().then(response => {
                            this.types     = []
                            this.statuses  = []
                            this.results   = []
                            this.contacts  = []
                            this.positions = []
                            this.cities    = []
                            this.users     = []
                            this.original_contacts = response?.result?.contacts || []
                            this.original_cities   = response?.result?.cities || []
                            this.original_users    = response?.result?.users || []
                            this.default_status    = null
                            this.default_result    = null
                            for (let i = 0; i < response?.result?.statuses?.length; i++) {
                                if (response?.result?.statuses[i].is_default) {
                                    this.default_status = response?.result?.statuses[i]?.id || null
                                }
                            }
                            for (let i = 0; i < response?.result?.results?.length; i++) {
                                if (response?.result?.results[i].is_default) {
                                    this.default_result = response?.result?.results[i]?.id || null
                                }
                            }
                            if (response?.status) {
                                this.types = (response?.result?.types || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.title,
                                        description: item.description
                                    }
                                })
                                this.statuses = (response?.result?.statuses || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.title,
                                        description: item.description
                                    }
                                })
                                this.results = (response?.result?.results || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.title,
                                        description: item.description
                                    }
                                })
                                this.contacts = (response?.result?.contacts || []).map(item => {
                                    let name = item.name
                                    if (item.nickName && item.nickName.length) {
                                        name += '('+item.nickName+')'
                                    }
                                    if (item.title && item.title.length) {
                                        name += ' - '+item.title
                                    }
                                    if (item.organization && item.organization.length) {
                                        name += '('+item.organization+')'
                                    }
                                    return {
                                        value: item.id,
                                        text: name,
                                    }
                                })
                                this.positions = (response?.result?.positions || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.title,
                                        description: item.description
                                    }
                                })
                                this.cities = (response?.result?.cities || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.province.title + ' - ' + item.title,
                                    }
                                })
                                this.users = (response?.result?.users || []).map(item => {
                                    return {
                                        value: item.id,
                                        text: item.display_name+' '+(item?.mobile || ''),
                                    }
                                })
                            } else {
                                this.$helpers.notify('خطا', response?.message || 'بروز خطا هنگام دریافت اطلاعات تکمیلی پروژه‌ها', { type: 'error' })
                            }
                        }).catch(err => {
                            console.error(err)
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت اطلاعات تکمیلی پروژه‌ها', { type: 'error' })
                        }).finally(res => {
                            this.loading = false
                        })
                    }
                }).catch(err => {
                    console.error(err)
                    this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام دریافت فهرست پروژه‌ها', { type: 'error' })
                }).finally(() => {
                    if (!filters) {
                        this.loading = false
                    }
                })
            }
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
                if (!this.default_status) {
                    message  = 'جدول وضعیت‌های پروژه خالی است یا وضعیتی به عنوان پیش‌فرض ثبت نشده است'
                } else if (!this.default_result) {
                    message  = 'جدول نتایج پروژه خالی است یا نتیجه‌ای به عنوان پیش‌فرض ثبت نشده است'
                }
            }
            if (message.length) {
                this.$helpers.notify('خطای دسترسی', message, {type: 'error'})
                return
            }
            this.form = {
                title: '',
                description: '',
                project_type: { id: 0, title: '' },
                project_status: { id: this.default_status, title: '' },
                project_result: { id: this.default_result, title: '' },
                user_percentage: 0,
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
            }
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
            ]
            this.active_tab  = 'general'
            this.newRec      = true
            this.editing     = false
            this.editingItem = []
            this.$nextTick(res => {
                this.getCurrentPosition(true)
            })
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
                this.validator.$touch();
                if (!this.validator.$invalid) {
                    this.saving = true
                    let record = new FormData()
                    const contacts = JSON.stringify(this.form.contacts)
                    const members = JSON.stringify(this.form.members)
                    record.append('title', this.form.title)
                    record.append('description', this.form.description)
                    record.append('project_type_id', this.form.project_type.id)
                    record.append('project_status_id', this.form.project_status.id)
                    record.append('project_result_id', this.form.project_result.id)
                    record.append('user_percentage', this.form.user_percentage || 0)
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
                    }
                    this.files.forEach((file, index) => {
                        record.append("file-"+index+1, file);
                    });
                    Requests.saveProject(record, (progressEvent) => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                    }).then(res => {
                        if (res?.status) {
                            if (this.editing) {
                                for (let i = 0; i < this.projects.length; i++) {
                                    if (this.projects[i].id === this.editingItem.id) {
                                        this.projects[i] = res.result
                                        break;
                                    }
                                }
                            } else {
                                this.projects.push(res.result)
                            }
                            this.$helpers.notify(res?.message || 'پروژه مورد نظر با موفقیت ذخیره شد')
                            this.newItem()
                            this.newRec = false
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ذخیره پروژه')
                        }
                    }).catch(err => {
                        console.error(err)
                        this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای هنگام ذخیره پروژه')
                    }).finally(res => {
                        this.uploadProgress = 0
                        this.files = []
                        this.saving = false
                    })
                } else {
                    console.log(this.validator.$errors)
                    this.$helpers.notify('خطای کاربر', 'خطاهی فرم را برطرف کنید')
                }
            }
        },
        editItem(item) {
            if (!this.permission.edit) {
                this.$helpers.notify('خطای دسترسی', 'شما مجوز ویرایش در این قسمت را ندارید!', {type: 'error'})
                return false
            }

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
                    id: 'notes',
                    text: 'پیگیری‌ها',
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
                user_percentage: item.user_percentage || 0,
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
                if (result.value) {
                    this.saving = true
                    Requests.deleteProjectResult(item_id).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.projects.length; i++) {
                                if (this.projects[i].id == item_id) {
                                    this.projects.splice(i, 1)
                                    break;
                                }
                            }
                            this.$helpers.notify('پروژه مورد نظر با موفقیت حذف گردید.')
                        } else {
                            this.$helpers.notify('خطا', 'خطای غیرمنتظره! حذف پروژه مورد نظر با مشکل مواجه شد...', {
                                type: "error",
                            })
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
                    console.log(err)
                    this.$helpers.notify('خطا', 'خطا هنگام دریافت اطلاعات تکمیلی موقعیت مورد نظر.', { type: 'error' })
                }).finally(res => {
                    this.gettingLocationInfo = false
                })
            }
        },
        getCurrentPosition(centerMap = false) {
            if (!navigator.geolocation) {
                this.$helpers.notify('خطا', 'این مرورگر از موقعیت مکانی پشتیبانی نمی‌کند.', { type: 'error' })
                return
            }
            navigator.geolocation.getCurrentPosition((position) => {
                this.currentPosition.lat = position.coords.latitude;
                this.currentPosition.long = position.coords.longitude;
            }, (err) => {
                this.$helpers.notify('خطا', 'خطا در دریافت موقعیت مکانی کاربر', { type: 'error' })
            }, {
                enableHighAccuracy: true,
            })

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
            console.log(this.form.members)
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
        removeContact(contact_id, position_id) {
            console.log(this.form.contacts)
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
        changeAlt(image) {
            if (this.updatingAlts !== image.id) {
                if (image.alt?.trim()?.length > 1) {
                    this.updatingAlts = image.id
                    const formData = {
                        image: image.id,
                        alt: image.alt,
                        type: image.type,
                    }
                    Requests.renameAlt(formData).then(res => {
                        if (res?.status) {
                            for (let i = 0; i < this.form.images.length; i++) {
                                if (this.form.images[i].id === image.id) {
                                    this.form.images[i].alt = image.alt
                                    this.form.images[i].type = image.type
                                    for (let k = 0; k < this.projects.length; k++) {
                                        if (this.projects[k].id === this.editingItem.id) {
                                            this.projects[k].images = res.result.images
                                            this.projects[k].covers = res.result.covers
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        } else {
                            this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام ویرایش عنوان/وضعیت تصویر', { type: 'error' })
                        }
                    }).catch(err => {
                        console.error(err)
                        this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام ویرایش عنوان/وضعیت تصویر', { type: 'error' })
                    }).finally(() => {
                        this.updatingAlts = null
                        this.loading = false
                    })
                } else {
                    this.$helpers.notify('خطا', 'عنوان تصویر نمی‌تواند خالی باشد', { type: 'error' })
                }
            }
        },
        deleteImage(image) {
            if (!this.updatingAlts.includes(image.id)) {
                this.$swal.fire({
                    title: "حذف تصویر",
                    text: "تصویر مورد نظر حذف گردد؟",
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
                                for (let i = 0; i < this.form?.images?.length; i++) {
                                    if (this.form.images[i].id === image) {
                                        this.form.images.splice(i, 1);
                                        for (let k = 0; k < this.projects.length; k++) {
                                            if (this.projects[k].id === this.editingItem.id) {
                                                this.projects[k].images = res.result.images
                                                this.projects[k].covers = res.result.covers
                                                break;
                                            }
                                        }
                                        this.$helpers.notify('تصویر مورد نظر با موفقیت حذف گردید.')
                                        break
                                    }
                                }
                            } else {
                                this.$helpers.notify('خطا', res?.message || 'بروز خطا هنگام حذف تصویر مورد نظر', {type: "error"})
                            }
                        }).catch(err => {
                            this.$helpers.notify('خطای ناشناخته', err?.response?.data?.message || 'بروز خطای ناشناخته هنگام حذف تصویر مورد نظر', {type: 'error'})
                            console.log(err);
                        }).finally(res => {
                            for (let i = 0; i < this.updatingAlts.length; i++) {
                                if (this.updatingAlts[i] === image.id) {
                                    this.updatingAlts.splice(i, 1)
                                }
                            }
                        })
                    }
                });
            }
        },
        previewImage(image) {
            this.imageToPreview = image
            this.$refs.previewImage.modalOpen()
        },
        downloadImage() {
            if (this.imageToPreview && this.imageToPreview?.filename) {
                const a = document.createElement("a")
                a.href = this.imageToPreview.filename
                a.download = this.editingItem.title+' - '+this.imageToPreview.alt
                document.body.appendChild(a)
                a.click();
                document.body.removeChild(a)
            }
        }
    }
};
</script>

<style scoped>
.card-content .inner-content {
    height: 115px;
    max-height: 115px;
    overflow: hidden;
    margin-bottom: 15px;
    padding-top: 5px;
}
.card-content .inner-content p {
    height: 110px;
    max-height: 110px;
    overflow: scroll;
    padding-bottom: 25px;
}
.inner-content .content-gradient {
    position: absolute;
    width: 100%;
    height: 30px;
    bottom: 0;
    background-image: linear-gradient(to top, #FFF, rgba(255, 255, 255, 0.35));;
}
.is-4by3 {
    aspect-ratio: 4/3 !important;
    width:100%;
}
.is-4by3 .gallery-item img {
    aspect-ratio: 4/3 !important;
    width: 100% !important;
    height: auto !important;
    object-fit: cover !important;
}
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
