<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $_temp_tmdb_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Genre extends Model {}
