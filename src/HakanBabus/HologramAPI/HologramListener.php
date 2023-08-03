<?php

namespace HakanBabus\HologramAPI;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class HologramListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        foreach (Hologram::getHolograms() as $hologram) {
            if($hologram->getPosition()->getWorld()->getFolderName() == $player->getWorld()->getFolderName()){
                $hologram->send($player);
            }
        }
    }

    public function onTeleport(EntityTeleportEvent $event): void
    {
        if($event->getFrom()->getWorld()->getFolderName() !== $event->getTo()->getWorld()->getFolderName()){
            $player = $event->getEntity();
            if($player instanceof Player){
                foreach (Hologram::getHolograms() as $hologram) {
                    if($hologram->getPosition()->getWorld()->getFolderName() == $event->getTo()->getWorld()->getFolderName()){
                        $hologram->send($player);
                    }elseif($hologram->getPosition()->getWorld()->getFolderName() == $event->getFrom()->getWorld()->getFolderName()){
                        $hologram->setInvisibleTo($player);
                    }
                }
            }
        }
    }

}