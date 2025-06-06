<?php

declare(strict_types=1);

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Genre::class);
            $table->foreignIdFor(Movie::class);
        });
    }
};
