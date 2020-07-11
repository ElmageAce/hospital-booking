<template>
    <a v-if="type" href="/" class="text-center db logo-font pt-3 pb-4 text-white">
        <i class="fa fa-user-md logo-icon"></i>
    </a>
    <a v-else href="/" class="text-center db logo-font my-5 text-info">
        <i class="fa fa-user-md logo-icon"></i> <br> <strong>meet</strong>up
    </a>
</template>

<script>
    export default {
        name: "AppLogo",
        props: {
            type: String
        }
    }
</script>

<style scoped>
    .logo-font {
        font-size: 20px;
    }
    .logo-icon {
        font-size: 35px;
    }
</style>
