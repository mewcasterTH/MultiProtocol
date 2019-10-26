<?php

namespace ItzLightyHD;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;


class Main extends PluginBase implements Listener {

   public $acceptProtocol = [];

   public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

		@mkdir($this->getDataFolder());
		$this->acceptProtocol = (new Config($this->getDataFolder()."accept.yml", Config::YAML))->get("accept-protocol");

		if ($this->acceptProtocol === false || empty($this->acceptProtocol)) {
			$this->acceptProtocol[] = ProtocolInfo::CURRENT_PROTOCOL;
			$config = new Config($this->getDataFolder()."accept.yml", Config::YAML);
			$config->set("accept-protocol", [ProtocolInfo::CURRENT_PROTOCOL]);
			$config->save();
		}
    }

		public function checkDepends() {
			$this->oc = $this->getServer()->getPluginManager()->getPlugin("OrbitCore");
			if(is_null($this->oc)){
					$this->getLogger()->error("OrbitCore is absolutely needed to run this plugin.");
          $this->getLogger()->error("Download it here: https://poggit.pmmp.io/ci/ItzLightyHD/OrbitCore/OrbitCore");
					$this->getPluginLoader()->disablePlugin($this);
			}
		}

    public function onDataPacketRecieve (DataPacketReceiveEvent $ev) {
    	$pk = $ev->getPacket();
    	if ($pk instanceof LoginPacket) {
    		if (in_array($pk->protocol, $this->acceptProtocol)) {
    			$pk->protocol = ProtocolInfo::CURRENT_PROTOCOL;
    		}
    	}
    }
}
