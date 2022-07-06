<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class ItemLikeRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && ! empty($this->user);
    }

    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'integer',
            ],
            'binaryVersion' => [
                'required',
                'integer',
            ],
            'gdw' => [
                'required',
                'boolean',
            ],
            'accountID' => [
                'sometimes',
                'exclude_if:accountID,0',
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable',
                'string',
            ],
            'uuid' => [
                'required_without:accountID',
                'integer',
            ],
            'udid' => [
                'required_with:uuid',
                'string',
            ],
            'itemID' => [
                'required',
                'integer',
            ],
            'like' => [
                'required',
                'boolean',
            ],
            'type' => [
                'required',
                'integer',
                'between:1,3',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
            'special' => [
                'required',
                'integer',
            ],
            'rs' => [
                'required',
                'string',
            ],
            'chk' => [
                'required',
                'string',
            ],
        ];
    }
}
