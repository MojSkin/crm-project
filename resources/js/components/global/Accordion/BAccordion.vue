<template>
    <div class="single-accordion" :class="{ 'is-exclusive': isExclusive }">
        <template v-for="header in headers">
            <slot name="header-section">
                <div class="accordion-header" :ref="'header-'+header?.id" :class="headerClass" :data-refrence="header?.id" @click="handleAccordion(header.id)">
                    <slot name="header">
                        <slot :name="'header-'+header?.id">
                            <span v-html="header?.title" />
                        </slot>
                    </slot>
                </div>
            </slot>
            <slot name="content-section">
                <div class="accordion-content" :class="contentClass" :ref="'content-'+header?.id" :data-refrence="header?.id">
                    <slot name="content">
                        <slot :name="'content-'+header?.id">
                        </slot>
                    </slot>
                </div>
            </slot>
        </template>
    </div>
</template>

<script>
export default {
    name: "BAccordion",
    props: {
        headers: Object,
        isExclusive: Boolean,
        headerClass: [Boolean, String],
        contentClass: [Boolean, String],
        open: [Boolean, String],
    },
    data() {
        return {}
    },
    created() {
    },
    mounted() {
        if (typeof this.open === 'string') {
            const title = $('.accordion-header[data-refrence="'+this.open+'"]')
            const content = $('.accordion-content[data-refrence="'+this.open+'"]')
            if (title) { title.addClass('is-active') }
            if (content) { content.show() }
        }
    },
    computed: {},
    watch: {},
    methods: {
        handleAccordion(header_id) {
            const title    = $(this.$refs['header-'+header_id][0])
            const contents = this.$refs['content-'+header_id]
            const content  = contents[0]

            if (this.isExclusive) {
                $('.accordion-content:not([data-refrence="'+header_id+'"])').slideUp()
                $('.accordion-header:not([data-refrence="'+header_id+'"])').removeClass('is-active')
            }

            $('.accordion-content[data-refrence="'+header_id+'"]').slideToggle()

            if (title.hasClass('is-active')) {
                $(title).removeClass('is-active')
            } else {
                $(title).addClass('is-active')
            }
        },
    }
}
</script>

<style>
.accordion-header .accordion-header-content {
    display: inline-flex;
    width: calc(100% - 21px);
    align-items: center;
}
</style>
