<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        placeholder?: string;
        closeOnBlur?: boolean;
    }>(),
    {
        closeOnBlur: false,
    },
);
const emit = defineEmits<{
    (e: 'search', value: string): void;
    (e: 'blur'): void;
}>();

const modelValue = defineModel<string>({ default: '' });

function submit() {
    emit('search', modelValue.value.trim());
}
function handleBlur() {
    if (props.closeOnBlur) {
        emit('blur');
    }
}
</script>

<template>
    <div class="relative w-full items-center">
        <Input id="search" type="text" :placeholder="placeholder" class="pl-10" v-model="modelValue" @keyup.enter="submit" @blur="handleBlur" />
        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
            <Search class="text-muted-foreground size-6" />
        </span>
    </div>
</template>
