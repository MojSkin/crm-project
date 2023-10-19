<template>
    <!--Activity panel-->
    <div class="right-panel-wrapper is-activity" :class="{ 'is-active': active }">
        <div class="panel-overlay"></div>

        <div class="right-panel">
            <div class="right-panel-head">
                <h3>فعالیت ها</h3>
                <a class="close-panel" @click="closePanel">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>

            <b-tabs :headers="[
                {id: 'tab-1', text: 'تیم'},
                {id: 'tab-2', text: 'پروژه‌ها'},
                {id: 'tab-3', text: 'برنامه‌ها'},
            ]">
                <template #tab-tab-1>
                    <!--Team Member-->
                    <div class="team-card">
                        <div class="h-avatar">
                            <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/12.jpg" alt="">
                            <img class="badge" src="https://via.placeholder.com/150x150" alt="">
                        </div>
                        <div class="meta">
                            <span>پاشا</span>
                            <span>
                                  <i data-feather="map-pin"></i>
                                 ایران , تهران
                              </span>
                        </div>
                        <a class="link">
                            <i data-feather="arrow-left"></i>
                        </a>
                    </div>

                    <!--Team Member-->
                    <div class="team-card">
                        <div class="h-avatar">
                            <img class="avatar" src="/resources/assets/img/avatars/photos/25.jpg" alt="">
                        </div>
                        <div class="meta">
                            <span>سارا</span>
                            <span>
                                  <i data-feather="map-pin"></i>
                                  لاس وگاس
                              </span>
                        </div>
                        <a class="link">
                            <i data-feather="arrow-left"></i>
                        </a>
                    </div>

                    <!--Team Member-->
                    <div class="team-card">
                        <div class="h-avatar">
                            <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/18.jpg" alt="">
                        </div>
                        <div class="meta">
                            <span>Esteban C.</span>
                            <span>
                                  <i data-feather="map-pin"></i>
                                  Miami, FL
                              </span>
                        </div>
                        <a class="link">
                            <i data-feather="arrow-left"></i>
                        </a>
                    </div>

                    <!--Team Member-->
                    <div class="team-card">
                        <div class="h-avatar">
                            <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/13.jpg" alt="">
                        </div>
                        <div class="meta">
                            <span>رویا</span>
                            <span>
                                  <i data-feather="map-pin"></i>
                                 نیویورک
                              </span>
                        </div>
                        <a class="link">
                            <i data-feather="arrow-left"></i>
                        </a>
                    </div>

                </template>
                <template #tab-tab-2>
                    <!--Project-->
                    <div class="project-card">
                        <div class="project-inner">
                            <img class="project-avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/icons/logos/slicer.svg" alt="">
                            <div class="meta">
                                <span>پروژه اسلایسر</span>
                                <span>vahid.xyz</span>
                            </div>
                            <a class="link">
                                <i data-feather="arrow-left"></i>
                            </a>
                        </div>
                        <div class="project-foot">
                            <progress class="progress is-primary is-tiny" value="31" max="100">31%</progress>
                            <div class="foot-stats">
                                <span>5 / 24</span>

                                <div class="avatar-stack">
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" alt="">
                                    </div>
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/5.jpg" alt="">
                                    </div>
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/8.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Project-->
                    <div class="project-card">
                        <div class="project-inner">
                            <img class="project-avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/icons/logos/metamovies.svg" alt="">
                            <div class="meta">
                                <span>بازسازی فیلم های متا</span>
                                <span>metamovies.co</span>
                            </div>
                            <a class="link">
                                <i data-feather="arrow-left"></i>
                            </a>
                        </div>
                        <div class="project-foot">
                            <progress class="progress is-primary is-tiny" value="84" max="100">84%</progress>
                            <div class="foot-stats">
                                <span>28 / 31</span>

                                <div class="avatar-stack">
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/13.jpg" alt="">
                                    </div>
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/18.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Project-->
                    <div class="project-card">
                        <div class="project-inner">
                            <img class="project-avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/icons/logos/fastpizza.svg" alt="">
                            <div class="meta">
                                <span> طراحی مجدد پیتزای سریع</span>
                                <span>fastpizza.com</span>
                            </div>
                            <a class="link">
                                <i data-feather="arrow-left"></i>
                            </a>
                        </div>
                        <div class="project-foot">
                            <progress class="progress is-primary is-tiny" value="60" max="100">60%</progress>
                            <div class="foot-stats">
                                <span>25 / 39</span>

                                <div class="avatar-stack">
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" alt="">
                                    </div>
                                    <div class="h-avatar is-small">
                                        <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/25.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-tab-3>
                    <div class="icon-timeline">
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="phone-call"></i>
                            </div>
                            <div class="timeline-content">
                                <p>تماس با دنی</p>
                                <span>امروز - 11:30am</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <img class="avatar" src="https://via.placeholder.com/150x150" data-demo-src="/resources/assets/img/avatars/photos/7.jpg" alt="">
                            </div>
                            <div class="timeline-content">
                                <p>جلسه با آلیس</p>
                                <span>امروز - 01:00pm</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="message-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <p>جواب دادن به پیغام علی</p>
                                <span>امروز - 01:45pm</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="mail"></i>
                            </div>
                            <div class="timeline-content">
                                <p>ارسال کمپین جدید</p>
                                <span>امروز - 02:30pm</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="smile"></i>
                            </div>
                            <div class="timeline-content">
                                <p>بررسی پروژه</p>
                                <span>امروز - 03:30pm</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="phone-call"></i>
                            </div>
                            <div class="timeline-content">
                                <p>تماس با بهراد</p>
                                <span>امروز - 05:00pm</span>
                            </div>
                        </div>
                        <!--Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i data-feather="feather"></i>
                            </div>
                            <div class="timeline-content">
                                <p>نوشتن طرح برای دن</p>
                                <span>امروز - 06:00pm</span>
                            </div>
                        </div>
                    </div>
                </template>
            </b-tabs>


        </div>

    </div>

</template>

<script>
export default {
    name: "AdminActivityPanel",
    props: {
        modelValue: {
            default: false
        },
    },
    data() {
        return {
            active: this.modelValue
        }
    },
    watch: {
        modelValue() {
            console.log(this.modelValue);
            this.active = this.modelValue
        }
    },
    methods: {
        closePanel() {
            this.$emit('update:modelValue', false)
        }
    }
};
</script>

<style scoped>

</style>
