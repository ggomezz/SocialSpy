<?php

namespace SocialSpy;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Main extends PluginBase{
  
  public function onEnable(){
       
    }
    
  public function onDisable(){
        
    }  
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{				
		if(strtolower($cmd->getName()) == "snoop") {
		 	if($sender instanceof Player) {
				if($sender->hasPermission("snoop.command")) {
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          
          
          
          //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
			}
		}
               
	}
  
}
