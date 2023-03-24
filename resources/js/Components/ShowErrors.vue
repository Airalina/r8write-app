<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object|Array,
});

</script>

<template>
    <div class="w-25">
        <span v-for="error in errors"  class="px-1 py-1 mb-2 text-red-600 text-sm rounded shadow bg-red-200">
            {{ error }}
        </span>
    </div>
</template>
