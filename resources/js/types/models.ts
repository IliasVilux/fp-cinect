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
}

export interface Category {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
  content: Content | null;
}
