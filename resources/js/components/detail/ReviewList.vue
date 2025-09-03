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
        <Heading :title="t('detail.reviewCount', { count: reviews?.length })" class="ml-5 lg:ml-0 mb-5" />

        <div class="flex flex-col gap-y-5 px-5 lg:px-0">
            <div v-for="review in reviews" :key="review.id" class="flex gap-x-3">
                <!-- Avatar -->
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage v-if="review.user.avatar" :src="review.user.avatar" :alt="review.user.name" />
                    <AvatarFallback class="rounded-lg bg-gradient-to-tr from-neutral-700 to-neutral-800 text-neutral-50 font-semibold">
                        {{ getInitials(review.user?.name) }}
                    </AvatarFallback>
                </Avatar>

                <div class="flex w-full flex-col gap-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-y-1 sm:gap-y-0 sm:gap-x-2">
                        <p class="text-sm font-semibold truncate">@{{ review.user.name }}</p>
                        <p class="text-muted-foreground text-xs">{{ dayjs(review.created_at).fromNow() }}</p>
                    </div>
                    <div class="flex w-full items-start justify-between">
                        <p class="text-pretty">{{ review.review_text }}</p>
                        <Link :href="route('reviews.destroy', review.id)" method="delete" class="ml-3" v-if="review.user.id === user.id">
                            <Trash2 class="aspect-square size-5 cursor-pointer hover:text-red-600 transition-colors duration-300" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
