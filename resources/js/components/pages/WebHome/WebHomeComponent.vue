<template>

    <MainLayoutComponent is-fluid>
        <img class="image-cover" :src="url+'/assets/img/bg/tebyan.jpg'" alt="تبیان">
        <div class="row-home">
            <div class="home-cover-widget esb shadow-lg has-text-centered">
                <div class="title">ابتدایی</div>
                <div class="subtitle">پسرانه</div>
                <button class="button is-weight-900 is-primary is-light is-outlined d-flex align-items-center more">
                    <span class="py-3">بیشتر</span>
                    <i class="fas fa-chevron-left ml-3"></i>
                </button>
            </div>
            <div class="home-cover-widget esg shadow-lg has-text-centered">
                <div class="title">ابتدایی</div>
                <div class="subtitle">دخترانه</div>
                <button class="button is-weight-900 is-primary is-light is-outlined d-flex align-items-center more">
                    <span class="py-3">بیشتر</span>
                    <i class="fas fa-chevron-left ml-3"></i>
                </button>
            </div>
            <div class="home-cover-widget hsb shadow-lg has-text-centered">
                <div class="title">متوسطه</div>
                <div class="subtitle">پسرانه</div>
                <button class="button is-weight-900 is-primary is-light is-outlined d-flex align-items-center more">
                    <span class="py-3">بیشتر</span>
                    <i class="fas fa-chevron-left ml-3"></i>
                </button>
            </div>
            <div class="home-cover-widget hsg shadow-lg has-text-centered">
                <div class="title">متوسطه</div>
                <div class="subtitle">دخترانه</div>
                <button class="button is-weight-900 is-primary is-light is-outlined d-flex align-items-center more">
                    <span class="py-3">بیشتر</span>
                    <i class="fas fa-chevron-left ml-3"></i>
                </button>
            </div>
        </div>
    </MainLayoutComponent>
</template>

<script>

export default {
    name: "WebHomeComponent",
    props: {},
    data() {
        return {
            url: import.meta.env.VITE_APP_URL_FULL
        }
    },
    created() {
    },
    mounted() {
    },
    computed: {},
    watch: {},
    methods: {}
}
</script>

<style scoped>
.image-cover {
    width: 100%;
    min-height: 320px;
    object-fit: cover;
    aspect-ratio: 16/9;
}
.row-home {
    position: absolute;
    flex-wrap: wrap;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    top: 75%;
    width: 100%;
    z-index: 99;
}
.home-cover-widget {
    flex: 0 0 calc(25% - 50px);
    position: relative;
    aspect-ratio: 1/1;
    margin: 5px;
    display: inline-block;
    background-color: var(--bs-gray-200);
    border: 7px solid #FFF;
    border-radius: 27px;
    padding: 1rem;
    overflow: hidden;
    filter: grayscale(0.7);
    transition: all 500ms ease-in-out;
}
.home-cover-widget:hover {
    transition: all 300ms ease-in-out;
    filter: none;
    transform: scale(1.2);
}
@media (min-width: 1200px) {
    .image-cover {
        aspect-ratio: 16/9;
        max-height: 600px;
        object-position: 0 5%;
        margin-bottom: 10%;
    }
    .row-home {
        max-width: 1200px;
        width: 1200px;
        top: 550px;
        margin: 0 calc((100% - 1200px) / 2);
    }
}
@media (max-width: 1024px) {
    .row-home {
        margin-top: 60px;
        flex: 1 0 21%;
        position: static;
        top: 50%;
        flex-wrap: wrap;
    }
    .home-cover-widget {
        flex:0 0 calc(50% - 100px);
        aspect-ratio: 3/2;
        margin-bottom: 20px;
    }
}

@media (max-width: 767px) {
    .row-home {
        flex: 1 1 21%; /* explanation below */
    }
}

@media (max-width: 767px) {
    .row-home {
        flex-direction: column;
    }
    .home-cover-widget {
        flex:1 1 300px;
        aspect-ratio: 1/1;
        margin: 20px auto
    }
}

.home-cover-widget button.more {
    position: absolute;
    bottom: 30px;
}

.home-cover-widget button.more span {
    margin-right: 10px;
}

.home-cover-widget button.more i {
    opacity: 0;
    width: 0;
    transition: all 500ms ease-in-out;
}

.home-cover-widget button.more:hover i {
    display: inline-block;
    width: 16px;
    opacity: 1;
}

.esb {
    background-image: url('/resources/assets/img/bg/esb.jpg');
}

.esg {
    background-image: url('/resources/assets/img/bg/esg.jpg');
}

.hsg {
    background-image: url('/resources/assets/img/bg/hsg.jpg');
}

.hsb {
    background-image: url('/resources/assets/img/bg/hsb.jpg');
}

.esb, .esg, .hsg, .hsb {
    background-repeat: no-repeat;
    background-size: contain;
    /*background-position: -4.5rem 3.5rem;*/
    background-position: center center;
}
</style>
