# What is this?
This repo (HologramAPI) It makes it easy to use floating texts in pocketmine, as the name suggests.

# Requirements
- PocketMine-MP Server (API5)
- Virions plugin

# Install
Put this project to virions folder.
Start server.

# Usage (API)
## Starting
Don't forget to write the following in the plugin's ```onEnable()``` function to get started. This is required to register Virion's events.
```php
use HakanBabus\HologramAPI;

HologramAPI::register(Plugin $plugin); //use $this
```
You don't need an isRegistered-style functiosn. The API controls this.

## Usage
To easly create hologram. Use this:
```php
use HakanBabus\HologramAPI;

$tag = "MyTag";
$text = "This is a hologram text";
$position = new Position(0, 60, 0, $world);
$hologram = new HologramAPI($tag, $text, $position);
```

Send to players/player
```php
$hologram->send(Player|array|null);  #if is null, send to world players
```

Set the properties
```php
$hologram->setTitle(string $text);
$hologram->setPosition(string $text);
```

Other functions
```php
Hologram::getHologram(string $tag): Hologram  #Get other holograms
$hologram->remove(Player|array|null)  #if is null, remove hologram to world players
$hologram->die(); #removing all players and destroy to hologram
```

# Example
```php
use HakanBabus\HologramAPI;
use pocketmine\Server;

$tag = "MoneyTag";
$moneyString = "XXXX";
$text = "TOP MONEY\n$moneyString";
$position = new Position(0, 60, 0, Server::getInstance()->getWorldManager()->getWorldbyName("world"));
$hologram = new HologramAPI($tag, $text, $position);

$hologram->send(); #send to only 'world' players

function regenerateTopList(Hologram $hologram): void
{
    $newMoneyString = "XXXXY";
    $hologram->setText($newMoneyString)->send();
}
