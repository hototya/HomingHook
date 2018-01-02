<?php
namespace hototya\monst;

use pocketmine\entity\projectile\Throwable;

use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\particle\Particle;

class EntityHomingHook extends Throwable
{
    public const NETWORK_ID = self::FISHING_HOOK;

    private $target;

    public function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->closed){
            return false;
        }
        $hasUpdate = parent::entityBaseTick($tickDiff);
        if ($this->age > 1200 or $this->isCollided) {
            $this->flagForDespawn();
            $hasUpdate = true;
        }
        if ($this->target !== null) {
            $this->gravity = 0.0;
            $this->pitch = rad2deg(atan2($this->y - $this->target->y - 2, sqrt(($this->x  - $this->target->x) ** 2 + ($this->z - $this->target->z) ** 2)));
            $this->yaw = rad2deg(atan2($this->z - $this->target->z, $this->x - $this->target->x)) + 90;
            $this->setMotion($this->getDirectionVector());
        }
        return $hasUpdate;
    }

    public function setTarget(Player $player)
    {
        $this->target = $player;
    }
}