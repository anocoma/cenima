<?php

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\MovieVersion;
use App\Models\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cinema::class);
            $table->foreignIdFor(Room::class)->constrained();
            $table->string('slug');
            $table->string('format');       //Format = type_room + movie_version; Ví dụ: Format = 2D + Lồng tiếng = 2D Lồng tiếng
            $table->foreignIdFor(MovieVersion::class)->constrained();
            $table->foreignIdFor(Movie::class);
            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
