<?php
namespace BoxOfDevs\StaffNotifier ; 
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
 use pocketmine\Player;


class Main extends PluginBase  implements Listener{
public function onEnable(){
$this->reloadConfig();
$this->getServer()->getPluginManager()->registerEvents($this, $this);
 }
public function onLoad(){
$this->reloadConfig();
$this->saveDefaultConfig();
}
public function onJoin(PlayerJoinEvent $event) {
	$player = $event->getPlayer();
	$owner = [];
	$admin = [];
	$op = [];
	$mod = [];
	$builder = [];
	$yt = [];
	foreach($this->getServer()->getOnlinePlayers() as $players) {
		if($players->hasPermission("is.owner")) {
			array_push($owner, $players->getName());
		} elseif($players->hasPermission("is.admin")) {
			array_push($admin, $players->getName());
		} elseif($players->hasPermission("is.op")) {
			array_push($op, $players->getName());
		} elseif($players->hasPermission("is.mod")) {
			array_push($mod, $players->getName());
		} elseif($players->hasPermission("is.builder")) {
			array_push($builder, $players->getName());
		} elseif($players->hasPermission("is.youtuber")) {
			array_push($yt, $players->getName());
		}
	}
	$message = $this->getConfig()->get("WelcomeMessage");
	$message = str_ireplace("{player}",  $player->getName(), $message);
	$message = str_ireplace("{owners}",  implode(", ", $owner), $message);
	$message = str_ireplace("{admins}",  implode(", ", $admin), $message);
	$message = str_ireplace("{ops}",  implode(", ", $op), $message);
	$message = str_ireplace("{mods}",  implode(", ", $mod), $message);
	$message = str_ireplace("{builders}",  implode(", ", $builder), $message);
	$message = str_ireplace("{youtubers}",  implode(", ", $yt), $message);
	$message = str_ireplace("{motd}",  $this->getServer()->getMotd(), $message);
	$player->sendMessage("§a".$message);
}
public function onPreCommand(PlayerCommandPreprocessEvent $event) {
	$message = $event->getMessage();
	$sender = $event->getPlayer();
	$cmd = explode(" ", $message);
	$owner = [];
	$admin = [];
	$op = [];
	$mod = [];
	$builder = [];
	$yt = [];
	$players = [];
	if($cmd[0] ==="/list") {
		foreach($this->getServer()->getOnlinePlayers() as $players) {
			if($players->hasPermission("is.owner")) {
			array_push($owner, $players->getName());
		} elseif($players->hasPermission("is.admin")) {
			array_push($admin, $players->getName());
		} elseif($players->hasPermission("is.op")) {
			array_push($op, $players->getName());
		} elseif($players->hasPermission("is.mod")) {
			array_push($mod, $players->getName());
		} elseif($players->hasPermission("is.builder")) {
			array_push($builder, $players->getName());
		} elseif($players->hasPermission("is.youtuber")) {
			array_push($yt, $players->getName());
		} else {
				array_push($players, $sender->getName());
			}
		}
		$sender->sendMessage("There is " . count($this->getServer()->getOnlinePlayers()) . "/".$this->getServer()->getMaxPlayers(). " players online.\n§4Owner: " . implode(", §4", $owner) . "\n§aAdmins: " . implode(", §a", $admin) . "\n§eOPs: " . implode(", §e", $op) . "\n§bMods: " . implode(", §b", $mod) . "\n§6Builders: " . implode(", §6", $builder) ."\n§4You§fTubers: " . implode(", §f", $yt) .  "\n§7Players: " .  implode(", §7", $players));
		$event->setCancelled(true);
	}
	}
 public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
switch($cmd->getName()){
	case "setwelcomemessage":
	if(!isset($args[0])) {
		return false;
	} else {
		$this->getConfig()->set("WelcomeMessage", implode(" ", $args));
		$sender->sendMessage("§aWelcome message is now : ".implode(" ", $args));
	}
}
return false;
 }
}