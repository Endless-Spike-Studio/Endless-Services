<?php

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashLevelCoinCounts;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingStars;
use App\GeometryDash\Enums\GeometryDashLevelUnlistedTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.levels';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class);
			$table->string('name');
			$table->string('description')->nullable();
			$table->integer('version');
			$table->forEnum('length', GeometryDashLevelLengths::class);
			$table->integer('audio_track_id')->nullable();
			$table->integer('password')->nullable();
			$table->foreignIdFor(Level::class, 'original_level_id');
			$table->boolean('2p_mode');
			$table->integer('objects');
			$table->forEnum('coins', GeometryDashLevelCoinCounts::class);
			$table->forEnum('requested_stars', GeometryDashLevelRatingStars::class);
			$table->forEnum('unlisted_type', GeometryDashLevelUnlistedTypes::class);
			$table->boolean('ldm_mode');
			$table->integer('editor_time')->nullable();
			$table->integer('previous_editor_time')->nullable();
			$table->string('extra')->nullable();
			$table->string('replay')->nullable();
			$table->integer('verification_time')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};