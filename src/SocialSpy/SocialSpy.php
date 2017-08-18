<?php

namespace SocialSpy;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginCommand;

use SocialSpy\Command\ViewInv;
use SocialSpy\Session\ViewInv as ViewSession;


class SocialSpy extends PluginBase{
  
  	public function onEnable(){
       
        }
    
  	public function onDisable(){
        
        }  
	
	public function registerCommands() {
		$cmd = new PluginCommand("viewinv", $this);
                $cmd->setDescription("View a players inventory");
                $cmd->setExecutor(new ViewInv($this));
                $this->getServer()->getCommandMap()->register("ic", $cmd);
        }

  	public function viewInventory(Player $player, Player $target) {
          	$this->viewing[$player->getName()] = new ViewSession($player, $target, true);
        }
        
  	public function isViewing($name) {
          	return isset($this->viewing[$name]) and $this->viewing[$name] instanceof ViewSession;
        }
        
  	public function stopViewing($name) {
          	if(!$this->isViewing($name)) return;
          	$this->viewing[$name]->end();
          	unset($this->viewing[$name]);
    	}

}
