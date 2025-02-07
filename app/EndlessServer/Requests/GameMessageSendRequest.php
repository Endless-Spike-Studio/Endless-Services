<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameMessageSendRequest extends FormRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'toAccountIDD' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
				'different:accountID'
			],
			'subject' => [
				'required',
				'string'
			],
			'body' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}
