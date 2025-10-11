# EasyForm

The simplest form API for PocketMine-MP. Create beautiful forms with just a few lines of code.

## Features

- Super simple and intuitive API
- Fluent method chaining
- No complex setup required
- Works with PocketMine-MP 5.0.0+
- Clean and readable code

## Installation

1. Download the plugin
2. Place it in your server's plugins folder
3. Restart your server
4. Start using it in your plugins

## Quick Start

### Simple Form (Menu with Buttons)

```php
use easyform\EasyForm;

$form = EasyForm::simple("Main Menu", "Choose an option:")
    ->button("Shop", null, function($player) {
        $player->sendMessage("Opening shop...");
    })
    ->button("Stats", null, function($player) {
        $player->sendMessage("Your stats...");
    })
    ->button("Exit");

$form->send($player);
```

### Modal Form (Yes/No Dialog)

```php
use easyform\EasyForm;

$form = EasyForm::modal(
    "Confirm Action",
    "Are you sure you want to do this?",
    "Yes",
    "No"
)
->onYes(function($player) {
    $player->sendMessage("Action confirmed!");
})
->onNo(function($player) {
    $player->sendMessage("Action cancelled.");
});

$form->send($player);
```

### Custom Form (Input Fields)

```php
use easyform\EasyForm;

$form = EasyForm::custom("Player Settings")
    ->input("Username", "Enter your name", "Steve")
    ->toggle("Enable notifications", true)
    ->slider("Volume", 0, 100, 10, 50)
    ->dropdown("Language", ["English", "Spanish", "French"], 0)
    ->onSubmit(function($player, $data) {
        $username = $data[0];
        $notifications = $data[1];
        $volume = $data[2];
        $language = $data[3];
        
        $player->sendMessage("Settings saved!");
    });

$form->send($player);
```

## API Reference

### Creating Forms

#### Simple Form
```php
EasyForm::simple(string $title, string $content = ""): SimpleForm
```

#### Modal Form
```php
EasyForm::modal(
    string $title,
    string $content,
    string $button1 = "Yes",
    string $button2 = "No"
): ModalForm
```

#### Custom Form
```php
EasyForm::custom(string $title): CustomForm
```

### Simple Form Methods

#### Add Button
```php
->button(string $text, ?string $icon = null, ?Closure $onClick = null): self
```
- `$text`: Button text
- `$icon`: Optional icon URL or path
- `$onClick`: Optional callback when button is clicked

#### Handle Submit
```php
->onSubmit(Closure $callback): self
```
Callback receives: `($player, $buttonIndex)`

#### Handle Close
```php
->onClose(Closure $callback): self
```
Callback receives: `($player)`

### Modal Form Methods

#### Handle Yes Button
```php
->onYes(Closure $callback): self
```
Callback receives: `($player)`

#### Handle No Button
```php
->onNo(Closure $callback): self
```
Callback receives: `($player)`

#### Handle Close
```php
->onClose(Closure $callback): self
```
Callback receives: `($player)`

### Custom Form Methods

#### Add Label
```php
->label(string $text): self
```

#### Add Input Field
```php
->input(string $text, string $placeholder = "", string $default = ""): self
```

#### Add Toggle Switch
```php
->toggle(string $text, bool $default = false): self
```

#### Add Slider
```php
->slider(string $text, int $min, int $max, int $step = 1, int $default = 0): self
```

#### Add Dropdown
```php
->dropdown(string $text, array $options, int $default = 0): self
```

#### Add Step Slider
```php
->stepSlider(string $text, array $steps, int $default = 0): self
```

#### Handle Submit
```php
->onSubmit(Closure $callback): self
```
Callback receives: `($player, $data)` where `$data` is an array of form values

#### Handle Close
```php
->onClose(Closure $callback): self
```
Callback receives: `($player)`

### Sending Forms

```php
EasyForm::send(Player $player, SimpleForm|ModalForm|CustomForm $form): void
```

Or use the form object directly:
```php
$player->sendForm($form);
```

## Examples

### Example 1: Shop Menu

```php
$form = EasyForm::simple("Shop", "What would you like to buy?")
    ->button("Sword - $100", null, function($player) {
        $player->sendMessage("You bought a sword!");
    })
    ->button("Armor - $200", null, function($player) {
        $player->sendMessage("You bought armor!");
    })
    ->button("Food - $50", null, function($player) {
        $player->sendMessage("You bought food!");
    });

$player->sendForm($form);
```

### Example 2: Teleport Confirmation

```php
$form = EasyForm::modal(
    "Teleport",
    "Do you want to teleport to spawn?",
    "Yes",
    "No"
)
->onYes(function($player) {
    $player->teleport($player->getWorld()->getSpawnLocation());
    $player->sendMessage("Teleported to spawn!");
})
->onNo(function($player) {
    $player->sendMessage("Teleport cancelled.");
});

$player->sendForm($form);
```

### Example 3: Registration Form

```php
$form = EasyForm::custom("Register")
    ->label("Create your account")
    ->input("Username", "Enter username")
    ->input("Email", "Enter email")
    ->toggle("Accept terms and conditions", false)
    ->dropdown("Country", ["USA", "UK", "Canada", "Other"], 0)
    ->onSubmit(function($player, $data) {
        $username = $data[0];
        $email = $data[1];
        $acceptTerms = $data[2];
        $country = $data[3];
        
        if (!$acceptTerms) {
            $player->sendMessage("You must accept the terms!");
            return;
        }
        
        $player->sendMessage("Account created successfully!");
    });

$player->sendForm($form);
```

### Example 4: Settings Menu

```php
$form = EasyForm::custom("Settings")
    ->label("Adjust your preferences")
    ->toggle("Enable chat", true)
    ->toggle("Enable sounds", true)
    ->slider("Music volume", 0, 100, 5, 50)
    ->slider("Effects volume", 0, 100, 5, 75)
    ->dropdown("Language", ["English", "Spanish", "French", "German"], 0)
    ->stepSlider("Difficulty", ["Easy", "Normal", "Hard"], 1)
    ->onSubmit(function($player, $data) {
        $enableChat = $data[0];
        $enableSounds = $data[1];
        $musicVolume = $data[2];
        $effectsVolume = $data[3];
        $language = $data[4];
        $difficulty = $data[5];
        
        $player->sendMessage("Settings saved!");
    });

$player->sendForm($form);
```

## Using in Your Plugin

### Method 1: Direct Usage

```php
use easyform\EasyForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class MyPlugin extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player) {
            return false;
        }

        if ($command->getName() === "menu") {
            $form = EasyForm::simple("Main Menu")
                ->button("Option 1", null, function($player) {
                    $player->sendMessage("You selected option 1");
                })
                ->button("Option 2", null, function($player) {
                    $player->sendMessage("You selected option 2");
                });
            
            $player->sendForm($form);
            return true;
        }

        return false;
    }
}
```

### Method 2: Using as API

Add EasyForm as a dependency in your plugin.yml:

```yaml
depend: [EasyForm]
```

Then use it in your code as shown above.

## Tips

1. Use method chaining for cleaner code
2. Keep form titles short and descriptive
3. Use icons to make buttons more attractive
4. Always handle the close event if needed
5. Validate user input in the onSubmit callback

## Support

For issues, questions, or suggestions, please visit the GitHub repository.

## License

This plugin is open source and available under the LGPL-3.0 license.