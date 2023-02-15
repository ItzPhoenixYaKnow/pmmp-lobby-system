<?php

namespace LobbyCore;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

class LobbyCore extends PluginBase {
  
  public function onEnable() : void{
	$this->getLogger()->info("The Lobby Core plugin has been enabled. Plugin made by Anonymous Dev1");
	$this->saveDefaultConfig();
	}
  
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case "setlobby":
                if ($sender instanceof Player) {
                    $this->setLobby($sender->getPosition());
                    $sender->sendMessage("Lobby set!");
                } else {
                    $sender->sendMessage("This command can only be run in-game.");
                }
                return true;
            case "hub":
                if ($sender instanceof Player) {
                    $config = $this->getConfig();
                    $x = $config->get("lobby-x");
                    $y = $config->get("lobby-y");
                    $z = $config->get("lobby-z");
                    $world = $config->get("lobby-world");
                    $level = $this->getServer()->getLevelByName($world);
                    if ($level !== null) {
                        $position = new Position($x, $y, $z, $level);
                        $sender->teleport($position);
                        $sender->sendMessage("Teleported to the lobby.");
                    } else {
                        $sender->sendMessage("The lobby world is not loaded.");
                    }
                } else {
                    $sender->sendMessage("This command can only be run in-game.");
                }
                return true;
            default:
                return false;
        }
    }

    private function setLobby(Position $position) {
        $config = $this->getConfig();
        $config->set("lobby-x", $position->getX());
        $config->set("lobby-y", $position->getY());
        $config->set("lobby-z", $position->getZ());
        $config->set("lobby-world", $position->getLevel()->getName());
        $config->save();
    }

}
