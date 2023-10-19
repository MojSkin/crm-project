<template>
    <ul v-bind="$attrs">
        <li>
            <RouterLink :to="{ name: 'admin.dashboard' }" data-content="پیشخوان">
                <i class="sidebar-svg" data-feather="activity"></i>
            </RouterLink>
        </li> <!-- Layouts -->
        <li>
            <RouterLink :to="{ name: 'admin.formBuilder' }" data-content="فرم‌ساز">
                <i class="sidebar-svg" data-feather="grid"></i>
            </RouterLink>
        </li> <!-- Bounties -->
        <li>
            <a href="#" data-content="درخواست‌ها">
                <i class="sidebar-svg" data-feather="box"></i>
            </a>
        </li>
    </ul>

</template>

<script>
export default {
    name: "MainAsideIconMenu",
    props: {
        hideProfile: {
            type: Boolean,
            default: true
        },
    },
    data() {
        return {
            display: this.hideContent ? 'none' : 'block',
        }
    }
};
</script>

<style scoped>
li a::after {
    display: v-bind(display)
}
</style>
