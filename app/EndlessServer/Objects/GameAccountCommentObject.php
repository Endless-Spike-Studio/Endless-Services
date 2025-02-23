<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\AccountComment;
use App\GeometryDash\Enums\Objects\GeometryDashCommentObjectDefinitions;
use App\GeometryDash\Objects\GameObject;
use Base64Url\Base64Url;

readonly class GameAccountCommentObject extends GameObject
{
	public function __construct(
		protected AccountComment $model
	)
	{
		parent::__construct(GeometryDashCommentObjectDefinitions::class, GeometryDashCommentObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashCommentObjectDefinitions::LEVEL_ID->value => function () {
				return 0; // TODO
			},
			GeometryDashCommentObjectDefinitions::CONTENT->value => function () {
				return Base64Url::encode($this->model->content, true);
			},
			GeometryDashCommentObjectDefinitions::PLAYER_ID->value => function () {
				return $this->model->account->player->id;
			},
			GeometryDashCommentObjectDefinitions::LIKES->value => function () {
				return 0; // TODO
			},
			GeometryDashCommentObjectDefinitions::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashCommentObjectDefinitions::IS_SPAM->value => function () {
				return $this->model->spam;
			},
			GeometryDashCommentObjectDefinitions::AGE->value => function () {
				return $this->model->created_at->diffForHumans(syntax: true);
			},
			GeometryDashCommentObjectDefinitions::PERCENT->value => function () {
				return 0; // TODO
			},
			GeometryDashCommentObjectDefinitions::MOD_BADGE->value => function () {
				return $this->model->account->mod_level;
			},
			GeometryDashCommentObjectDefinitions::COLOR->value => function () {
				return $this->model->account->comment_color;
			}
		];
	}
}