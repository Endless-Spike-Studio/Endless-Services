<?php

namespace App\EndlessProxy\Models;

use App\EndlessProxy\Controllers\NewgroundsAudioProxyController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class NewgroundsSong extends Model
{
	protected $table = 'endless_proxy.newgrounds_songs';

	protected $fillable = ['song_id', 'name', 'artist_id', 'artist_name', 'size', 'disabled', 'original_download_url'];

	protected $appends = ['download_url'];

	public function downloadUrl(): Attribute
	{
		return new Attribute(
			get: fn() => URL::action([NewgroundsAudioProxyController::class, 'download'], [
				'id' => $this->song_id
			])
		);
	}
}