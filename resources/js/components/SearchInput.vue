<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';
import { ref } from 'vue';

const emit = defineEmits<{
    (e: 'search', value: string): void;
}>();

const searchQuery = ref('');
function submit() {
    emit('search', searchQuery.value.trim());
}
</script>

<template>
    <div class="relative w-full min-w-xs items-center">
        <Input id="search" type="text" placeholder="Search..." class="pl-10" v-model="searchQuery" @keyup.enter="submit" />
        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
            <Search class="text-muted-foreground size-6" />
        </span>
    </div>
</template>
