<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelRating extends Model
{
	protected $table = 'endless_server.level_ratings';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}