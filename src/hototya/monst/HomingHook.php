<?php
namespace hototya\monst;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\item\ItemIds;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class HomingHook extends PluginBase implements Listener
{

    public function onLoad()
    {
        Entity::registerEntity(EntityHomingHook::class, false, ['Hominghook', 'minecraft:hominghook']);
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerHold(PlayerInteractEvent $event)
    {
        $action = $event->getAction();
        $item = $event->getItem();
        if ($action === PlayerInteractEvent::RIGHT_CLICK_AIR || $action === PlayerInteractEvent::LEFT_CLICK_AIR) {
            if ($item->getId() === ItemIds::FISHING_ROD) {
                //$item->setDamage($item->getDamage() - 1);
                $player = $event->getPlayer();
                $entity = Entity::createEntity("Hominghook", $player->level, Entity::createBaseNBT(
                    $player->add($player->getDirectionVector())->add(0, $player->getEyeHeight()),
                    new Vector3(
                        -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI),
                        -sin($player->pitch / 180 * M_PI),
                        cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))));
                if ($entity instanceof EntityHomingHook) {
                    foreach ($player->getViewers() as $view) {
                        $entity->setTarget($view);
                        break;
                    }
                }
                $entity->spawnToAll();
            }
        }
    }
}