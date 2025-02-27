<?php

namespace App\EndlessServer\Guards;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PlayerGuard implements Guard
{
	protected ?Player $player = null;

	public function id(): ?int
	{
		if ($this->guest()) {
			return null;
		}

		return $this->player->id;
	}

	public function guest(): bool
	{
		return $this->user() === null;
	}

	public function user(): ?Player
	{
		if ($this->player === null) {
			if ($this->tryAccount()) {
				return $this->user();
			}

			if (Request::filled(['uuid', 'udid'])) {
				if ($this->tryUuidAndUdid()) {
					return $this->user();
				}

				if ($this->tryCreate()) {
					return $this->user();
				}
			}

			return null;
		}

		return $this->player;

	}

	protected function tryAccount(): bool
	{
		/** @var ?Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		if ($account === null) {
			return false;
		}

		$this->player = $account->player;

		return true;
	}

	protected function tryUuidAndUdid(): bool
	{
		$uuid = Request::string('uuid');
		$udid = Request::string('udid');

		if (is_numeric($uuid)) {
			$player = Player::query()
				->where('id', $uuid)
				->where('udid', $udid)
				->first();
		} else {
			$player = Player::query()
				->where('uuid', $uuid)
				->where('udid', $udid)
				->first();
		}

		if ($player === null) {
			return false;
		}

		$this->player = $player;

		return true;
	}

	protected function tryCreate(): bool
	{
		$uuid = Request::string('uuid');
		$udid = Request::string('udid');
		$name = Request::string('userName', 'Player');

		$player = Player::query()
			->create([
				'uuid' => $uuid,
				'udid' => $udid,
				'name' => $name
			]);

		if (!$player->wasRecentlyCreated) {
			return false;
		}

		$this->player = $player;
		return true;
	}

	public function check(): bool
	{
		return !$this->guest();
	}

	public function validate(array $credentials = []): false
	{
		return false;
	}

	public function hasUser(): bool
	{
		return !$this->guest();
	}

	public function setUser(Authenticatable $user): PlayerGuard
	{
		return $this;
	}
}