<?php

declare(strict_types=1);

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Services\Typesense\Documents\PopularMovieDocument;
use App\Services\Typesense\Collections\PopularMoviesCollection;

Artisan::command('typesense:import', function () {
    $collection = new PopularMoviesCollection;

    try {
        $collection->ensureCollectionExists();
    } catch (Throwable) {
        $collection->create();
    }

    $documents = Movie::query()
        ->lazy()
        ->mapInto(PopularMovieDocument::class);

    $collection->import($documents);
});

Artisan::command('typesense:drop', function () {
    $collection = new PopularMoviesCollection;
    $collection->drop();
});

Artisan::command('typesense:info', function () {
    $collection = new PopularMoviesCollection;
    dd($collection->details());
});

Artisan::command('import:tmdb', function () {
    $movies = File::json(base_path('tmdb_movies.json'));
    $genres = File::json(base_path('tmdb_genres.json'));

    collect($genres)
        ->map(fn (array $data) => [
            'name' => $data['name'],
            'slug' => str($data['name'])->slug(),
            '_temp_tmdb_id' => $data['id'],
        ])
        ->tap(fn (Collection $data) => Genre::insert($data->toArray())
        );

    $genres = Genre::all();

    collect($movies)
        ->each(function (array $data) {
            if (Movie::where('_temp_tmdb_id', $data['id'])->exists()) {
                return null;
            }

            Movie::query()->create([
                'title' => $data['title'],
                'description' => $data['overview'],
                'release_date' => Carbon::parse($data['release_date']),
                'rating' => $data['vote_average'],
                'poster_path' => $data['poster_path'],
                'backdrop_path' => $data['backdrop_path'],
                '_temp_tmdb_id' => $data['id'],
            ]);
        });

    collect($movies)
        ->each(function (array $data) use ($genres) {
            $movie = Movie::where('_temp_tmdb_id', $data['id'])->first();

            if ($movie) {
                $movie->genres()->sync(
                    $genres->whereIn('_temp_tmdb_id', $data['genre_ids'])
                );
            }
        });
});
