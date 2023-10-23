<template>
    <b-overlay :show="saving || loading" loader-custom-class="has-text-info" :loader-text="getLoaderText">
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
                        <div class="column is-12-mobile is-12-tablet is-4-desktop is-4-widescreen is-flex pt-5">
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
                <span class="hint--light hint--rounded hint--top my-2 mr-5" data-hint="بستن پنجره"><i @click="imageToPreview: null; $refs.previewImage.modalClose()" class="fas fa-times has-text-grey-light"></i></span>
            </template>
        </b-card>
    </b-modal>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import 'vue3-openlayers/styles.css';
export default {
    name: "ProjectDetailsComponent",
    props: {
        project: {
            required: true
        },
        baseTypes: {
            type: [Array, null],
            default: null
        },
        baseStatuses: {
            type: [Array, null],
            default: null
        },
        baseResults: {
            type: [Array, null],
            default: null
        },
        baseContacts: {
            type: [Array, null],
            default: null
        },
        basePositions: {
            type: [Array, null],
            default: null
        },
        baseCities: {
            type: [Array, null],
            default: null
        },
        baseUsers: {
            type: [Array, null],
            default: null
        },
        userPermissions: {
            type: [Array, null],
            default: null
        },
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
                user_percentage: 0,
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
            },
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
                    id: 'comments',
                    text: 'دیدگاه‌ها',
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
            currentUser: null,
            prevUser: null,

        }
    },
    created() {
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {
        getLoaderText() {
            return 'در حال ذخیره پروژه، لطفا کمی صبور باشید... '+this.uploadProgress+'%'
        },
    }
}
</script>

<style scoped>

</style>
