<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { selectItem } from '@/types';

const props = defineProps<{
    selectItems: selectItem[];
    placeholder: string;
    modelValue?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | null): void;
}>();

function onUpdateModelValue(value: string) {
    emit('update:modelValue', value === props.modelValue ? null : value);
}
</script>

<template>
    <Select :modelValue="modelValue" @update:modelValue="onUpdateModelValue">
        <SelectTrigger>
            <SelectValue :placeholder="modelValue ?? placeholder" class="capitalize" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="item in selectItems" :value="item.value" :key="item.label" class="capitalize">
                    {{ item.label }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
