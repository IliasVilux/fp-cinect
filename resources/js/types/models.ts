export interface Content {
    id: number;
    title: string;
    description: string | null;
    type: 'movie' | 'series' | 'anime';
    release_year: number | null;
    duration: number | null;
    category_id: number;
    cover_image: string | null;
    created_at: string;
    updated_at: string;
    seasons?: Season[];
    category: Category | null;
}

export interface Category {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    content: Content | null;
}

export type Episode = {
    id: number;
    title: string;
    episode_number: number;
    duration: number | null;
    season_id: number;
    created_at: string;
    updated_at: string;
};

export interface Season {
    id: number;
    title: string | null;
    season_number: number;
    content_id: number;
    created_at: string;
    updated_at: string;
    episodes?: Episode[];
}

export interface Review {
    id: number;
    review_text: string;
    user_id: number;
    content_id: number;
    created_at: string;
    updated_at: string;
    user: {
        name: string;
        avatar?: string;
    };
}

export interface FavoriteList {
    id: number;
    name: string;
    description: string | null;
    is_public: boolean;
    user_id: number;
    created_at: string;
    updated_at: string;
    contents?: Content[];
}
