<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashSecrets;

class GameAccountLoginRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->auth_username_gjp2(),
			'udid' => [
				'required',
				'string'
			],
			'sID' => [
				'nullable',
				'string'
			],
			...$this->secret(GeometryDashSecrets::ACCOUNT)
		];
	}
}