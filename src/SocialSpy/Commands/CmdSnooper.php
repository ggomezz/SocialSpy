<?php

namespace cmdsnooper;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class CmdSnooper extends PluginBase {
	public $snoopers = [];
	
	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->getLogger()->info("Enabled! Ready to snoop >:D");
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
	  	"Console.Logger" => "true",
  		));
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{				
		if(strtolower($cmd->getName()) == "snoop") {
		 	if($sender instanceof Player) {
				if($sender->hasPermission("snoop.command")) {
					if(!isset($this->snoopers[$sender->getName()])) {
						$sender->sendMessage("§8Snoop> §eYou have entered snoop mode");
						$this->snoopers[$sender->getName()] = $sender;
						return true;
					} else {
						$sender->sendMessage("§8Snoop> §eYou have left snoop mode");
						unset($this->snoopers[$sender->getName()]);
						return true;
					}
				}
			}
		}
		$this->getLogger()->info("Command '/snoop' must be run as a player");
		return false;
	}
 }
