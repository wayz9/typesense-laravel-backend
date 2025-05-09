<?php

declare(strict_types=1);

namespace App\Services\Typesense\Documents;

use App\Models\Movie;
use App\Services\Typesense\Enums\FieldType;
use App\Services\Typesense\Data\TypesenseField;

final class PopularMovieDocument extends TypesenseDocument
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected Movie $movie,
    ) {}

    /**
     * {@inheritDoc}
     */
    public static function schema(): array
    {
        return [
            new TypesenseField(
                name: 'id',
                type: FieldType::STRING,
            ),
            new TypesenseField(
                name: 'title',
                type: FieldType::STRING,
            ),
            new TypesenseField(
                name: 'description',
                type: FieldType::STRING,
                optional: true,
            ),
            new TypesenseField(
                name: 'release_date',
                type: FieldType::INT32,
                optional: true,
                sort: true,
            ),
            new TypesenseField(
                name: 'release_status',
                type: FieldType::STRING,
                index: true,
                facet: true,
            ),
            new TypesenseField(
                name: 'poster_url',
                type: FieldType::STRING,
                optional: true,
                index: false,
            ),
            new TypesenseField(
                name: 'backdrop_url',
                type: FieldType::STRING,
                optional: true,
                index: false,
            ),
            new TypesenseField(
                name: 'genres',
                type: FieldType::STRING_ARRAY,
                optional: true,
                facet: true,
            ),
            new TypesenseField(
                name: 'is_rated',
                type: FieldType::BOOL,
                index: true,
            ),
            new TypesenseField(
                name: 'rating',
                type: FieldType::FLOAT,
                optional: true,
                sort: true,
            ),
            new TypesenseField(
                name: 'created_at',
                type: FieldType::INT32,
                sort: true,
            ),
            // Soft-deleted field -> TypesenseField::softDeleted(),
        ];
    }

    /**
     * Convert the data to a typesense document following the schema.
     *
     * @return array<string,mixed>
     */
    public function toDocument(): array
    {
        return [
            'id' => (string) $this->movie->id,
            'title' => $this->movie->title,
            'description' => $this->movie->description,
            'release_date' => $this->movie->release_date?->timestamp,
            'rating' => $this->movie->rating,
            'is_rated' => (bool) (null !== $this->movie->rating),
            'release_status' => (bool) ($this->movie->release_date?->isPast() ?? false)
                ? 'Released'
                : 'Upcoming',
            'poster_url' => $this->movie->poster_path
                ? "https://image.tmdb.org/t/p/w500/{$this->movie->poster_path}"
                : null,
            'backdrop_url' => $this->movie->backdrop_path
                ? "https://image.tmdb.org/t/p/w1280/{$this->movie->backdrop_path}"
                : null,
            'genres' => $this->movie->genres->pluck('name')->toArray(),
            'created_at' => $this->movie->created_at?->timestamp,
        ];
    }
}
