<?php

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\SoundEffect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_sound_effect_mappings';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Level::class);
			$table->timestamps(SoundEffect::class);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};