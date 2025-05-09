<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $_temp_tmdb_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Genre extends Model {}
