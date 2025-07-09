<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { SelectItem as SelectItemType } from '@/types';
import { WithClassAsProps } from './ui/carousel/interface';
import { computed } from 'vue';

const props = defineProps<
    {
        selectItems: SelectItemType[];
        placeholder: string;
    } & WithClassAsProps
>();

const modelValue = defineModel<string | number | null>({ default: null });

const selectPlaceholder = computed(() => {
  const itemSelected = props.selectItems.find(item => item.value === modelValue.value);
  return itemSelected?.label || props.placeholder;
});

function handleSelect(value: any) {
  if (modelValue.value === value) {
    modelValue.value = null;
  } else {
    modelValue.value = value;
  }
}
</script>

<template>
    <Select v-model="modelValue" @update:modelValue="handleSelect">
        <SelectTrigger :class="cn('', props.class)">
            <slot name="icon" />
            <SelectValue :placeholder="selectPlaceholder" class="capitalize" />
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
