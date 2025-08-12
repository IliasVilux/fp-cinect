<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { Content } from '@/types/models';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle, Star } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const props = defineProps<{
    content: Content & {
        ratings_avg_rating?: string;
        user_rating?: { rating: number };
        user_review?: { review_text: string };
    };
}>();

const form = useForm({
    rating: props.content.user_rating?.rating || 0,
    review: props.content.user_review?.review_text || '',
});
watch(
    () => props.content.user_review?.review_text,
    (newReview) => {
        form.review = newReview || '';
        form.rating = props.content.user_rating?.rating || 0;
    },
);
const dialogOpen = ref(false);
const handleDialogOpenChange = (open: boolean) => {
    dialogOpen.value = open;
    if (!open) {
        form.reset();
    }
};
const handleRating = (rating: number) => {
    if (form.rating === rating) {
        form.rating = 0;
        return;
    }

    form.rating = rating;
};
const submit = () => {
    form.post(route('content.store-review', props.content.id), {
        onFinish: () => {
            dialogOpen.value = false;
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <Dialog :open="dialogOpen" @update:open="handleDialogOpenChange">
        <DialogTrigger as-child>
            <Button variant="outline">
                <h3 class="text-lg">
                    {{ content.ratings_avg_rating ? Number(content.ratings_avg_rating).toFixed(1) : '—' }}
                </h3>
                <component :is="Star" class="size-4 fill-yellow-300 text-yellow-300" />
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ t('detail.review.title') }}</DialogTitle>
                <DialogDescription>
                    {{ t('detail.review.description') }}
                </DialogDescription>
            </DialogHeader>

            <form id="form" @submit.prevent="submit">
                <div class="flex w-full space-x-0 md:space-x-0">
                    <Star
                        v-for="n in 5"
                        :key="n"
                        class="aspect-square size-full cursor-pointer"
                        :class="cn(n <= form.rating ? 'fill-yellow-300 text-yellow-300' : 'fill-neutral-700 text-neutral-700')"
                        @click="handleRating(n)"
                    />
                </div>
                <p v-show="form.rating !== 0" class="text-muted-foreground text-center text-xs">
                    {{ t('detail.review.ratingHint') }}
                </p>

                <div class="grid gap-0">
                    <Label for="review">{{ t('detail.review.reviewLabel') }}</Label>
                    <Textarea id="review" v-model="form.review" :placeholder="t('detail.review.reviewPlaceholder')" />
                    <p class="text-muted-foreground text-xs" v-show="form.review != ''">
                        {{ t('detail.review.reviewHint') }}
                    </p>
                    <InputError :message="form.errors.review" />
                </div>

                <DialogFooter>
                    <Button type="submit" class="sm:justify-start" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                        {{ t('detail.review.submit') }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
