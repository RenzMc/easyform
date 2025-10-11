<?php

declare(strict_types=1);

namespace easyform;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
	private static ?self $instance = null;

	public function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
		$this->getLogger()->info("EasyForm has been enabled!");
	}

	public static function getInstance(): self
	{
		return self::$instance;
	}
}