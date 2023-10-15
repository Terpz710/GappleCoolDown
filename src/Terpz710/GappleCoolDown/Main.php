<?php

namespace Terpz710\GappleCooldown;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
    private $cooldowns = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerItemConsume(PlayerItemConsumeEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        
        if ($item instanceof GoldenAppleEnchanted) {
            $playerName = $player->getName();
            if (!isset($this->cooldowns[$playerName]) || $this->cooldowns[$playerName] <= microtime(true)) {
                $this->cooldowns[$playerName] = microtime(true) + 10;
            } else {
                $remainingTime = ceil($this->cooldowns[$playerName] - microtime(true));
                $player->sendMessage("§eYou must wait §c{$remainingTime}§e seconds before consuming another enchanted golden apple.");
                $event->cancel();
            }
        }
    }
}
