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
  
	public $database;
	
	public $authorized = array();
	
  	public function onEnable(){
       		$this->getLogger()->info('§ePlugin initialization...');
		
		@mkdir($this->getDataFolder());
		
		if(!is_file($this->getDataFolder(). 'config.yml')){
			$this->saveResource('config.yml'); 
		}
		
		$this->config = new Config($this->getDataFolder(). 'config.yml', Config::YAML);
		
		$databaseType = strtolower($this->config->get('type'));
		switch($databaseType){
			case 'mysql':
			case 'mysqli':
				$this->database = new Database\MySQLDatabase($this);
				break;
				
			case 'yaml':
			case 'yml':
				$this->database = new Database\YAMLDatabase($this);
				break;
			
			case 'json':
				$this->database = new Database\JSONDatabase($this);
				break;
				
			case 'sqlite':
			case 'sqlite3':
				$this->database = new Database\SQLiteDatabase($this);
				break;
				
			default:
				$this->getLogger()->warning($this->lang->getMessage('db_wrong', ['{type}'], [$databaseType]));
				$this->database = new Database\SQLiteDatabase($this);
		}
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		$this->getCommand("snoop")->setExecutor(new Commands\CmdSnooper($this));
        }
    
  	public function onDisable(){
        	$this->database->close();
        }  
	
	public function getDatabase(){
		return $this->database;
	}
	
	public function isAuthorized(Player $player){
		return isset($this->authorized[strtolower($player->getName())]);
	}
	
	public function authorize(Player $player){
		$nick = strtolower($player->getName());
		$this->setVisible($player);
		
		$this->authorized[$nick] = true;
		$this->database->authorizePlayer($player);
		return;
	}
	
	public function deauthorize(Player $player){
		$this->setInvisible($player);
		unset($this->authorized[strtolower($player->getName())]);
		return;
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
