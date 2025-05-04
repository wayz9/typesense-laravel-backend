<?php

namespace App\Services\Typesense\Collections;

use App\Services\Typesense\Documents\PopularMovieDocument;

/**
 * @extends TypesenseCollection<PopularMovieDocument>
 */
class PopularMoviesCollection extends TypesenseCollection
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
    public function schema(): array
    {
        return PopularMovieDocument::schema();
    }

    /**
     * {@inheritDoc}
     */
    public function create(): void
    {
        $this->client()->collections->create([
            'name' => $this->name(),
            'fields' => $this->schema(),
            // 'enable_nested_fields' => true,
        ]);
    }
}
