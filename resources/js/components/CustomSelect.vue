<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { SelectItem as SelectItemType } from '@/types';
import { computed } from 'vue';
import { WithClassAsProps } from './ui/carousel/interface';

const props = defineProps<
    {
        selectItems: SelectItemType[];
        placeholder: string;
        modelValue?: string | number | null;
    } & WithClassAsProps
>();

const selectedLabel = computed(() => {
    const selectedItem = props.selectItems.find((item) => item.value === props.modelValue);
    return selectedItem?.label ?? null;
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number | null): void;
}>();

function onUpdateModelValue(value: string | number | null) {
    emit('update:modelValue', value === props.modelValue ? null : value);
}
</script>

<template>
    <Select :modelValue="modelValue" @update:modelValue="onUpdateModelValue">
        <SelectTrigger :class="cn('', props.class)">
            <slot name="icon" />
            <SelectValue :placeholder="selectedLabel ?? placeholder" class="capitalize" />
        </SelectTrigger>
        <SelectContent align="center">
            <SelectGroup>
                <SelectItem v-for="item in selectItems" :value="item.value" :key="item.label" class="capitalize">
                    {{ item.label }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
