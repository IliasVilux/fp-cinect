const TMDB_BASE_URL = 'https://image.tmdb.org/t/p/';

type ImageType = 'poster' | 'backdrop';
type ImageSize =
  | 'w92'
  | 'w154'
  | 'w185'
  | 'w342'
  | 'w500'
  | 'w780'
  | 'w1280'
  | 'w1920'
  | 'original';

const DEFAULT_SIZES: Record<ImageType, ImageSize> = {
    poster: 'w500',
    backdrop: 'w1920',
};

export function getTMDBImage(
  path?: string | null,
  type: ImageType = 'poster',
  size?: ImageSize
): string {
  if (!path) {
    return '/images/welcome/hero-background.webp';
  }

  const finalSize = size ?? DEFAULT_SIZES[type];
  const normalizedPath = path.startsWith('/') ? path : `/${path}`;

  return `${TMDB_BASE_URL}${finalSize}${normalizedPath}`;
}

export function useTMDBImage() {
    return { getTMDBImage };
}
