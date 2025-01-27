<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerData;
use App\EndlessServer\Requests\GamePlayerInfoFetchRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;

readonly class GamePlayerController
{
	public function __construct(
		protected GeometryDashObjectService $objectService
	)
	{

	}

	public function info(GamePlayerInfoFetchRequest $request): int|string
	{
		$data = $request->validated();

		$player = Player::query()
			->where('uuid', $data['targetAccountID'])
			->first();

		if (empty($player)) {
			return GeometryDashResponses::PLAYER_INFO_FETCH_FAILED_NOT_FOUND->value;
		}

		return $this->objectService->merge([
			GeometryDashPlayerInfoObjectDefinitions::NAME->value => $player->name,
			GeometryDashPlayerInfoObjectDefinitions::ID->value => $player->id,
			GeometryDashPlayerInfoObjectDefinitions::STARS->value => $player->data->stars,
			GeometryDashPlayerInfoObjectDefinitions::DEMONS->value => $player->data->demons,
			GeometryDashPlayerInfoObjectDefinitions::CREATOR_POINTS->value => $player->statistic->creator_points,
			GeometryDashPlayerInfoObjectDefinitions::ICON_ID->value => $player->data->icon_id,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_1->value => $player->data->color1,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_2->value => $player->data->color2,
			GeometryDashPlayerInfoObjectDefinitions::COINS->value => $player->data->coins,
			GeometryDashPlayerInfoObjectDefinitions::ICON_TYPE->value => $player->data->icon_type,
			GeometryDashPlayerInfoObjectDefinitions::SPECIAL->value => $player->data->special,
			GeometryDashPlayerInfoObjectDefinitions::UUID->value => $player->uuid,
			GeometryDashPlayerInfoObjectDefinitions::USER_COINS->value => $player->data->user_coins,
			GeometryDashPlayerInfoObjectDefinitions::MESSAGE_STATE->value => $player->account->setting->message_state,
			GeometryDashPlayerInfoObjectDefinitions::FRIEND_REQUEST_STATE->value => $player->account->setting->friend_request_state,
			GeometryDashPlayerInfoObjectDefinitions::YOUTUBE->value => $player->account->setting->youtube,
			GeometryDashPlayerInfoObjectDefinitions::CUBE_ID->value => $player->data->cube_id,
			GeometryDashPlayerInfoObjectDefinitions::SHIP_IP->value => $player->data->ship_id,
			GeometryDashPlayerInfoObjectDefinitions::BALL_ID->value => $player->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::BIRD_ID->value => $player->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::WAVE_ID->value => $player->data->dart_id,
			GeometryDashPlayerInfoObjectDefinitions::ROBOT_ID->value => $player->data->robot_id,
			GeometryDashPlayerInfoObjectDefinitions::GLOW_ID->value => $player->data->glow_id,
			GeometryDashPlayerInfoObjectDefinitions::IS_REGISTERED->value => true,
			GeometryDashPlayerInfoObjectDefinitions::GLOBAL_RANK->value => PlayerData::query()
				->where('stars', '<=', $player->data->stars)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::FRIEND_STATE->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_ID->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_COMMENT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_AGE->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::NEW_MESSAGE_COUNT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::NEW_FRIEND_REQUEST_COUNT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::NEW_FRIEND_COUNT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::HAS_NEW_FRIEND_REQUEST->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::SPIDER_ID->value => $player->data->spider_id,
			GeometryDashPlayerInfoObjectDefinitions::TWITTER->value => $player->account->setting->twitter,
			GeometryDashPlayerInfoObjectDefinitions::TWITCH->value => $player->account->setting->twitch,
			GeometryDashPlayerInfoObjectDefinitions::DIAMONDS->value => $player->data->diamonds,
			GeometryDashPlayerInfoObjectDefinitions::EXPLOSION_ID->value => $player->data->explosion_id,
			GeometryDashPlayerInfoObjectDefinitions::MOD_LEVEL->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::COMMENT_HISTORY_STATE->value => $player->account->setting->comment_history_state,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_3->value => $player->data->color3,
			GeometryDashPlayerInfoObjectDefinitions::MOONS->value => $player->data->moons,
			GeometryDashPlayerInfoObjectDefinitions::SWING_ID->value => $player->data->swing_id,
			GeometryDashPlayerInfoObjectDefinitions::JETPACK_ID->value => $player->data->jetpack_id,
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_DEMONS_INFO->value => collect([
				$player->statistic->completed_classic_easy_demons_count,
				$player->statistic->completed_classic_medium_demons_count,
				$player->statistic->completed_classic_hard_demons_count,
				$player->statistic->completed_classic_insane_demons_count,
				$player->statistic->completed_classic_extreme_demons_count,
				$player->statistic->completed_platformer_easy_demons_count,
				$player->statistic->completed_platformer_medium_demons_count,
				$player->statistic->completed_platformer_hard_demons_count,
				$player->statistic->completed_platformer_insane_demons_count,
				$player->statistic->completed_platformer_extreme_demons_count,
				$player->statistic->completed_weeklies_count,
				$player->statistic->completed_gauntlet_demon_levels_count
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_CLASSIC_LEVELS_INFO->value => collect([
				$player->statistic->completed_classic_auto_count,
				$player->statistic->completed_classic_easy_count,
				$player->statistic->completed_classic_normal_count,
				$player->statistic->completed_classic_hard_count,
				$player->statistic->completed_classic_harder_count,
				$player->statistic->completed_classic_insane_count,
				$player->statistic->completed_dailies_count,
				$player->statistic->completed_gauntlet_levels_count
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_PLATFORMER_LEVELS_INFO->value => collect([
				$player->statistic->completed_platformer_auto_count,
				$player->statistic->completed_platformer_easy_count,
				$player->statistic->completed_platformer_normal_count,
				$player->statistic->completed_platformer_hard_count,
				$player->statistic->completed_platformer_harder_count,
				$player->statistic->completed_platformer_insane_count
			])->join(',')
		], GeometryDashPlayerInfoObjectDefinitions::GLUE);
	}
}