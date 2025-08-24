<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import type { SharedData, User } from '@/types';
import type { Review } from '@/types/models';
import { Link, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/en';
import 'dayjs/locale/es';
import relativeTime from 'dayjs/plugin/relativeTime';
import { Trash2 } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';
import Heading from '@/components/common/Heading.vue';

const { t, locale } = useI18n();

dayjs.extend(relativeTime);
dayjs.locale(locale.value);

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

defineProps<{
    reviews: Review[] | null;
}>();
</script>

<template>
    <section>
        <Heading :title="t('detail.reviewCount', { count: reviews?.length })" />

        <div class="flex flex-col gap-y-0">
            <div v-for="review in reviews" :key="review.id" class="flex gap-x-0">
                <!-- Avatar -->
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage v-if="review.user.avatar" :src="review.user.avatar" :alt="review.user.name" />
                    <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                        {{ getInitials(review.user?.name) }}
                    </AvatarFallback>
                </Avatar>

                <div class="flex w-full flex-col gap-y-0">
                    <div class="flex items-baseline gap-x-2">
                        <p class="text-sm font-semibold">@{{ review.user.name }}</p>
                        <p class="text-muted-foreground text-xs">{{ dayjs(review.created_at).fromNow() }}</p>
                    </div>
                    <div class="flex w-full items-start justify-between">
                        <p class="text-pretty">{{ review.review_text }}</p>
                        <Link :href="route('reviews.destroy', review.id)" method="delete" v-if="review.user.id === user.id">
                            <Trash2 class="aspect-square size-full cursor-pointer" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
