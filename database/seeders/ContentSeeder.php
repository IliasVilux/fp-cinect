<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Genre;
use App\Models\Season;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ContentSeeder extends Seeder
{
    private $apiKey;

    private $baseUrlTMDB;

    private $baseUrlJikan;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
        $this->baseUrlTMDB = config('services.tmdb.base_url');
        $this->baseUrlJikan = config('services.jikan.base_url');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCategoriesFromTMDB();
        $this->seedMoviesFromTMDB();
        $this->seedTvShowsFromTMDB();
        $this->seedAnimeFromJikan();
    }

    private function seedCategoriesFromTMDB()
    {
        $genres = collect();

        $endpoints = [
            'movie' => "$this->baseUrlTMDB/genre/movie/list",
            'tvShows' => "$this->baseUrlTMDB/genre/tv/list",
        ];

        $responseMovie = Http::withToken($this->apiKey)->accept('application/json')->get($endpoints['movie']);
        if ($responseMovie->successful()) {
            $movieGenres = collect($responseMovie->json()['genres'])
                ->pluck('name')
                ->values();

            $genres = $genres->merge($movieGenres);
        }

        $responseTvShows = Http::withToken($this->apiKey)->accept('application/json')->get($endpoints['tvShows']);
        if ($responseTvShows->successful()) {
            $tvGenres = collect($responseTvShows->json()['genres'])
                ->pluck('name')
                ->reject(fn ($name) => $genres->contains($name))
                ->values();

            $genres = $genres->merge($tvGenres);
        }

        $genres->each(callback: fn ($genre) => Genre::Create(['name' => $genre]));

        dump('Generos creados correctamente!');
    }

    public function seedMoviesFromTMDB()
    {
        $page = 1;
        do {
            $discoverResponse = Http::withToken($this->apiKey)->accept('application/json')->get("$this->baseUrlTMDB/discover/movie?page=$page");
            $page++;

            if (! $discoverResponse->successful()) {
                continue;
            }

            $moviesData = [];
            foreach ($discoverResponse->json()['results'] as $movie) {
                $movieDetailResponse = Http::withToken($this->apiKey)->accept('application/json')->get("{$this->baseUrlTMDB}/movie/{$movie['id']}");

                if (! $movieDetailResponse->successful() || ! isset($movieDetailResponse->json()['id'])) {
                    continue;
                }
                if (Content::where('title', $movieDetailResponse->json()['title'])->exists()) {
                    continue;
                }

                $genre = null;
                if (! empty($movieDetailResponse->json()['genres'])) {
                    $randomGenre = collect($movieDetailResponse->json()['genres'])->random();
                    $genre = Genre::where('name', explode(' ', $randomGenre['name'])[0])->first();
                }

                $moviesData[] = [
                    'title' => $movieDetailResponse->json()['title'],
                    'description' => $movieDetailResponse->json()['overview'] ?? null,
                    'type' => 'movie',
                    'release_year' => substr($movieDetailResponse->json()['release_date'], 0, 4) ?? null,
                    'duration' => $movieDetailResponse->json()['runtime'] ?? null,
                    'genre_id' => $genre->id ?? null,
                    'poster_image' => $movieDetailResponse->json()['poster_path'] ?? null,
                    'backdrop_image' => $movieDetailResponse->json()['backdrop_path'] ?? null,
                ];

                dump('Agregando película: '.$movieDetailResponse->json()['title']);
            }

            if (! empty($moviesData)) {
                Content::insert($moviesData);

                dump(count($moviesData).' películas insertadas en la base de datos.');
            }
        } while ($page <= 2);
    }

    public function seedTvShowsFromTMDB()
    {
        $page = 1;
        do {
            $discoverResponse = Http::withToken($this->apiKey)->accept('application/json')->get("$this->baseUrlTMDB/discover/tv?page=$page");
            $page++;

            if (! $discoverResponse->successful()) {
                continue;
            }

            foreach ($discoverResponse->json()['results'] as $tvShow) {
                $tvShowDetailResponse = Http::withToken($this->apiKey)->accept('application/json')->get("{$this->baseUrlTMDB}/tv/{$tvShow['id']}");

                if (! $tvShowDetailResponse->successful() || ! isset($tvShowDetailResponse->json()['id'])) {
                    continue;
                }
                if (Content::where('title', $tvShowDetailResponse->json()['name'])->exists()) {
                    continue;
                }

                $genre = null;
                if (! empty($tvShowDetailResponse->json()['genres'])) {
                    $randomGenre = collect($tvShowDetailResponse->json()['genres'])->random();
                    $genre = Genre::where('name', explode(' ', $randomGenre['name'])[0])->first();
                }

                $content = Content::create([
                    'title' => $tvShowDetailResponse->json()['name'],
                    'description' => $tvShowDetailResponse->json()['overview'] ?? null,
                    'type' => 'series',
                    'release_year' => substr($tvShowDetailResponse->json()['first_air_date'], 0, 4) ?? null,
                    'duration' => null,
                    'genre_id' => $genre->id ?? null,
                    'poster_image' => $tvShowDetailResponse->json()['poster_path'] ?? null,
                    'backdrop_image' => $tvShowDetailResponse->json()['backdrop_path'] ?? null,
                ]);
                dump('Agregando serie: '.$content->title);

                if (isset($tvShowDetailResponse->json()['seasons']) && is_array($tvShowDetailResponse->json()['seasons'])) {
                    $limitedSeasons = array_slice($tvShowDetailResponse->json()['seasons'], 0, 5);
                    foreach ($limitedSeasons as $seasonData) {
                        if (! isset($seasonData['season_number']) || $seasonData['season_number'] === 0) {
                            continue;
                        }

                        $season = Season::create([
                            'title' => $seasonData['name'],
                            'season_number' => $seasonData['season_number'],
                            'content_id' => $content->id,
                        ]);

                        dump('  - Creando temporada: '.$season->title);

                        $seasonResponse = Http::withToken($this->apiKey)
                            ->accept('application/json')
                            ->get("{$this->baseUrlTMDB}/tv/{$tvShow['id']}/season/{$seasonData['season_number']}");

                        if (! $seasonResponse->successful()) {
                            continue;
                        }

                        $episodesData = array_map(function ($episode) use ($season) {
                            return [
                                'title' => $episode['name'],
                                'episode_number' => $episode['episode_number'],
                                'duration' => $episode['runtime'] ?? 0,
                                'season_id' => $season->id,
                            ];
                        }, $seasonResponse->json()['episodes'] ?? []);

                        if (! empty($episodesData)) {
                            $season->episodes()->createMany($episodesData);

                            dump('    - '.count($episodesData).' episodios insertados en '.$season->title);
                        }
                    }
                }
            }
        } while ($page <= 2);
    }

    public function seedAnimeFromJikan()
    {
        $page = 1;
        do {
            $discoverResponse = Http::accept('application/json')->get("$this->baseUrlJikan/anime", [
                'limit' => 25,
                'page' => $page,
            ]);
            $page++;

            dump('Respuesta Jikan:', $discoverResponse->status(), $discoverResponse->json());

            if ($discoverResponse->successful()) {
                foreach ($discoverResponse->json()['data'] as $anime) {
                    $genre = null;
                    if (! empty($anime['genres'])) {
                        $randomGenre = collect($anime['genres'])->random();
                        $genre = Genre::firstOrCreate(['name' => $randomGenre['name']]);
                    }

                    $duration = intval($anime['duration']) ?? null;

                    $content = Content::create([
                        'title' => $anime['title'],
                        'description' => $anime['synopsis'] ?? null,
                        'type' => 'anime',
                        'release_year' => $anime['year'] ?? null,
                        'duration' => $duration ?? null,
                        'genre_id' => $genre->id ?? null,
                        'poster_image' => $anime['images']['webp']['large_image_url'] ?? null,
                        'backdrop_image' => $anime['images']['jpg']['maximum_image_url'] ?? null,
                    ]);

                    dump('Agregando anime: '.$content->title);

                    if (! $anime['episodes'] || $anime['episodes'] === 0) {
                        continue;
                    }

                    $episodesResponse = Http::accept('application/json')->get("$this->baseUrlJikan/anime/{$anime['mal_id']}/episodes");

                    $season = $content->seasons()->create([
                        'title' => 'Season 1',
                        'season_number' => 1,
                    ]);

                    $episodesData = array_map(function ($episode, $index) use ($duration) {
                        return [
                            'title' => $episode['title'] ?? null,
                            'episode_number' => $index + 1,
                            'duration' => $duration,
                        ];
                    }, $episodesResponse->json()['data'] ?? [], array_keys($episodesResponse->json()['data'] ?? []));

                    if (! empty($episodesData)) {
                        $season->episodes()->createMany($episodesData);

                        dump('    - '.count($episodesData).' episodios insertados en '.$season->title);
                    }
                }
            }
        } while ($page <= 2);
    }
}
