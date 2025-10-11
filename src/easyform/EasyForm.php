<?php

declare(strict_types=1);

namespace easyform;

use pocketmine\player\Player;
use easyform\form\SimpleForm;
use easyform\form\ModalForm;
use easyform\form\CustomForm;

class EasyForm
{
	public static function simple(string $title, string $content = ""): SimpleForm
	{
		return new SimpleForm($title, $content);
	}

	public static function modal(string $title, string $content, string $button1 = "Yes", string $button2 = "No"): ModalForm
	{
		return new ModalForm($title, $content, $button1, $button2);
	}

	public static function custom(string $title): CustomForm
	{
		return new CustomForm($title);
	}

	public static function send(Player $player, SimpleForm|ModalForm|CustomForm $form): void
	{
		$player->sendForm($form);
	}
}