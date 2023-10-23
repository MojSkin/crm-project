<template>
    <div class="card h-card is-rounded rounded-4">
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
            <span v-text="project?.title" class="is-font-alt has-text-grey is-rounded rounded-2 is-weight-900" :style="{position: 'absolute', top: '5px', right: '5px', width: 'calc(100% - 10px)', padding: '3px 5px', backgroundColor: 'rgba(250, 250, 250, 0.85)', boxShadow: '0 0 4px rgba(0, 0, 0, 0.95)'}" v-if="headerPosition=='TOP'"/>
            <span v-text="project?.project_type?.title" class="tag" :style="{position: 'absolute', bottom: '5px', right: '5px', backgroundColor: project?.project_type?.bgColor, color: project?.project_type?.textColor}" v-if="!noType"/>
            <span v-text="$helpers.jDate(project.created_at)" class="tag" :style="{position: 'absolute', bottom: '5px', left: '5px'}" v-if="!noDate"/>
        </div>
        <div class="card-content p-3">
            <h3 class="mb-2" v-text="project?.title" v-if="headerPosition != 'TOP'"/>
            <div class="media-flex-center no-margin">
                <div class="w-100 is-flex">
                    <span v-text="project?.last_note?.project_status?.title" class="tag" :style="{backgroundColor: project?.last_note?.project_status?.bgColor, color: project?.last_note?.project_status?.textColor}" v-if="!noStatus"/>
                    <span v-text="project?.last_note?.project_result?.title" class="tag ml-auto" :style="{backgroundColor: project?.last_note?.project_result?.bgColor, color: project?.last_note?.project_result?.textColor}" v-if="!noResult"/>
                </div>
            </div>
            <div class="inner-content mt-2 mb-1 is-relative" v-if="!noDescription">
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
<!--                    <a class="ml-auto action-link small" @click="$emit('edit')" v-if="!noDetails">جزییات</a>-->
                <RouterLink class="ml-auto action-link small" :to="{name: 'admin.project.single', params: {project_id: project.id}}" v-if="!noDetails">جزییات</RouterLink>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "ProjectSingleCardComponent",
    props: {
        project: {
            required: true
        },
        headerPosition: {
            type: String,
            default: 'TOP'
        },
        noDescription: {
            type: Boolean,
            default: false
        },
        noDate: {
            type: Boolean,
            default: false
        },
        noType: {
            type: Boolean,
            default: false
        },
        noStatus: {
            type: Boolean,
            default: false
        },
        noResult: {
            type: Boolean,
            default: false
        },
        noDetails: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {}
    },
    created() {
    },
    mounted() {
        console.log(this.project)
    },
    computed: {},
    watch: {},
    methods: {}
}
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
.content-gradient {
    position: absolute;
    width: 100%;
    height: 30px;
    bottom: 0;
    background-image: linear-gradient(to top, #FFF, rgba(255, 255, 255, 0.35));
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
</style>
