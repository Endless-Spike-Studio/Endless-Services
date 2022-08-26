<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountSettingUpdateRequest;
use App\Http\Traits\GameLog;

class AccountSettingController extends Controller
{
    use GameLog;

    public function update(AccountSettingUpdateRequest $request): int
    {
        $data = $request->validated();

        $setting = $request->account->setting;
        $setting->message_state = $data['mS'];
        $setting->friend_request_state = $data['frS'];
        $setting->comment_history_state = $data['cS'];
        $setting->youtube_channel = $data['yt'];
        $setting->twitter = $data['twitter'];
        $setting->twitch = $data['twitch'];
        $setting->save();

        $this->logGame(__('gdcn.game.action.account_setting_update_success'));
        return Response::GAME_ACCOUNT_SETTING_UPDATE_SUCCESS->value;
    }
}
