<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $public_id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $release_date
 * @property float|null $rating
 * @property string|null $poster_path
 * @property string|null $backdrop_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int,Genre> $genres
 */
final class Movie extends Model
{
    /**
     * List of genres associated with the movie.
     *
     * @return BelongsToMany<Genre,$this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'release_date' => 'datetime',
        ];
    }
}
