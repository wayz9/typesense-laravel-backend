<?php

declare(strict_types=1);

namespace App\Services\Typesense\Collections;

use App\Services\Typesense\Documents\PopularMovieDocument;

/**
 * @extends TypesenseCollection<PopularMovieDocument>
 */
final class PopularMoviesCollection extends TypesenseCollection
{
    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'popular_movies';
    }

    /**
     * {@inheritDoc}
     */
    public function create(): void
    {
        $this->client()->collections->create([
            'name' => $this->name(),
            'fields' => PopularMovieDocument::schema(),
            // 'enable_nested_fields' => true,
        ]);
    }
}
