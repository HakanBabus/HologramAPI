<?php

namespace HakanBabus\HologramAPI;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\world\particle\BlockParticle;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;

class Hologram
{
    private static bool $isRegistered = false;
    /**
     * @var array<string, Hologram>
     */
    private static array $holograms = [];

    private string $holoTag;
    private string $text;
    private FloatingTextParticle $particle;
    private Position $position;

    public static function register(Plugin $plugin): void
    {
        if(self::$isRegistered) return;
        Server::getInstance()->getPluginManager()->registerEvents(
            new HologramListener(),
            $plugin
        );
        self::$isRegistered = true;
    }

    public static function getHologram(string $holoTag): ?self
    {
        if(array_key_exists($holoTag, self::$holograms)){
            return self::$holograms[$holoTag];
        }
        return null;
    }

    public static function getHolograms(): array
    {
        return self::$holograms;
    }

    public function __construct(string $holoTag, string $text, Position $position)
    {
        if(!self::$isRegistered){
            throw new \RuntimeException('please before register ( Hologram::register(Plugin $plugin)) ');
        }
        $this->holoTag = $holoTag;
        $this->text = $text;
        $this->position = $position;
        $this->particle = new FloatingTextParticle($text);
        self::$holograms[$holoTag] = $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        $this->getParticle()->setText($text);
        return $this;
    }

    public function setInvisibleTo(Player $player): self
    {
        $this->getParticle()->setInvisible(true);
        $this->send($player);
        $this->getParticle()->setInvisible(false);
        return $this;
    }

    public function setVisibleTo(Player $player): self
    {
        $this->getParticle()->setInvisible(false);
        $this->send($player);
        return $this;
    }

    public function setTitleTo(Player $player, string $title): self
    {
        $this->getParticle()->setText($title);
        $this->getParticle()->setInvisible(false);
        $this->send($player);
        $this->getParticle()->setText($this->text);
        return $this;
    }

    public function getParticle(): FloatingTextParticle
    {
        return $this->particle;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    public function getHoloTag(): string
    {
        return $this->holoTag;
    }

    /**
     * @param array<Player>|Player|null $players
     * @return self
     */
    public function send(array|Player|null $players = null): self
    {
        if($players instanceof Player) $players = [$players];
        $this->position->getWorld()->addParticle($this->position, $this->getParticle(), $players);
        return $this;
    }

    /**
     * @param array<Player>|Player|null $players
     * @return self
     */
    public function remove(array|Player|null $players = null): self
    {
        $this->getParticle()->setInvisible(true);
        if($players instanceof Player) $players = [$players];
        $this->position->getWorld()->addParticle($this->position, $this->getParticle(), $players);
        return $this;
    }

    public function die(): void
    {
        $this->remove(Server::getInstance()->getOnlinePlayers());
        unset(self::$holograms[$this->getHoloTag()]);
    }


}