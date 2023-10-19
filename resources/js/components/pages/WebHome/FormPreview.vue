<template>
    <MainLayoutComponent class="mt-6">
        <form-builder :form-id="form_id"/>
    </MainLayoutComponent>

</template>

<script>

export default {
    name: "FormPreview",
    props: ['form_id']
};
</script>

<style scoped>

</style>
