# EasyForm API Documentation

Complete API reference for EasyForm plugin.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Core Classes](#core-classes)
3. [Form Types](#form-types)
4. [Method Reference](#method-reference)
5. [Best Practices](#best-practices)

## Getting Started

### Basic Usage

```php
use easyform\EasyForm;
use pocketmine\player\Player;

$form = EasyForm::simple("Title", "Content")
    ->button("Click me", null, function($player) {
        $player->sendMessage("Button clicked!");
    });

$player->sendForm($form);
```

## Core Classes

### EasyForm

Main entry point for creating forms.

**Static Methods:**

- `simple(string $title, string $content = ""): SimpleForm`
- `modal(string $title, string $content, string $button1 = "Yes", string $button2 = "No"): ModalForm`
- `custom(string $title): CustomForm`
- `send(Player $player, SimpleForm|ModalForm|CustomForm $form): void`

## Form Types

### SimpleForm

A form with buttons that players can click.

**Use Cases:**
- Main menus
- Navigation menus
- Shop categories
- Action selection

**Example:**
```php
$form = EasyForm::simple("Main Menu", "Choose an option:")
    ->button("Option 1")
    ->button("Option 2")
    ->button("Option 3");
```

### ModalForm

A yes/no dialog form.

**Use Cases:**
- Confirmations
- Yes/No questions
- Accept/Decline prompts

**Example:**
```php
$form = EasyForm::modal("Confirm", "Are you sure?", "Yes", "No")
    ->onYes(function($player) {
        // Handle yes
    })
    ->onNo(function($player) {
        // Handle no
    });
```

### CustomForm

A form with various input elements.

**Use Cases:**
- Settings forms
- Registration forms
- Configuration forms
- Data input forms

**Example:**
```php
$form = EasyForm::custom("Settings")
    ->input("Name", "Enter name")
    ->toggle("Enable feature", true)
    ->slider("Volume", 0, 100);
```

## Method Reference

### SimpleForm Methods

#### button()

Add a button to the form.

```php
public function button(string $text, ?string $icon = null, ?Closure $onClick = null): self
```

**Parameters:**
- `$text` (string): Button text
- `$icon` (string|null): Icon URL or path (optional)
- `$onClick` (Closure|null): Callback when clicked (optional)

**Callback Signature:**
```php
function(Player $player): void
```

**Example:**
```php
->button("Shop", "textures/items/diamond", function($player) {
    $player->sendMessage("Opening shop...");
})
```

#### onSubmit()

Handle form submission.

```php
public function onSubmit(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player, int $buttonIndex): void
```

**Example:**
```php
->onSubmit(function($player, $buttonIndex) {
    $player->sendMessage("You clicked button " . $buttonIndex);
})
```

#### onClose()

Handle form closure.

```php
public function onClose(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player): void
```

**Example:**
```php
->onClose(function($player) {
    $player->sendMessage("Form closed");
})
```

### ModalForm Methods

#### onYes()

Handle yes button click.

```php
public function onYes(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player): void
```

**Example:**
```php
->onYes(function($player) {
    $player->sendMessage("Confirmed!");
})
```

#### onNo()

Handle no button click.

```php
public function onNo(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player): void
```

**Example:**
```php
->onNo(function($player) {
    $player->sendMessage("Cancelled!");
})
```

#### onClose()

Handle form closure.

```php
public function onClose(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player): void
```

### CustomForm Methods

#### label()

Add a text label.

```php
public function label(string $text): self
```

**Parameters:**
- `$text` (string): Label text

**Example:**
```php
->label("Please fill in the form below:")
```

#### input()

Add a text input field.

```php
public function input(string $text, string $placeholder = "", string $default = ""): self
```

**Parameters:**
- `$text` (string): Field label
- `$placeholder` (string): Placeholder text
- `$default` (string): Default value

**Example:**
```php
->input("Username", "Enter your username", "Player123")
```

#### toggle()

Add a toggle switch.

```php
public function toggle(string $text, bool $default = false): self
```

**Parameters:**
- `$text` (string): Toggle label
- `$default` (bool): Default state

**Example:**
```php
->toggle("Enable notifications", true)
```

#### slider()

Add a numeric slider.

```php
public function slider(string $text, int $min, int $max, int $step = 1, int $default = 0): self
```

**Parameters:**
- `$text` (string): Slider label
- `$min` (int): Minimum value
- `$max` (int): Maximum value
- `$step` (int): Step increment
- `$default` (int): Default value

**Example:**
```php
->slider("Volume", 0, 100, 5, 50)
```

#### dropdown()

Add a dropdown menu.

```php
public function dropdown(string $text, array $options, int $default = 0): self
```

**Parameters:**
- `$text` (string): Dropdown label
- `$options` (array): Array of options
- `$default` (int): Default selected index

**Example:**
```php
->dropdown("Language", ["English", "Spanish", "French"], 0)
```

#### stepSlider()

Add a step slider.

```php
public function stepSlider(string $text, array $steps, int $default = 0): self
```

**Parameters:**
- `$text` (string): Slider label
- `$steps` (array): Array of step labels
- `$default` (int): Default selected index

**Example:**
```php
->stepSlider("Difficulty", ["Easy", "Normal", "Hard"], 1)
```

#### onSubmit()

Handle form submission.

```php
public function onSubmit(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player, array $data): void
```

**Data Array:**
The `$data` array contains form values in order:
- Input fields: string value
- Toggles: boolean value
- Sliders: int/float value
- Dropdowns: int (selected index)
- Step sliders: int (selected index)

**Example:**
```php
->onSubmit(function($player, $data) {
    $username = $data[0];  // First input
    $enabled = $data[1];   // First toggle
    $volume = $data[2];    // First slider
    
    $player->sendMessage("Username: " . $username);
})
```

#### onClose()

Handle form closure.

```php
public function onClose(Closure $callback): self
```

**Callback Signature:**
```php
function(Player $player): void
```

## Best Practices

### 1. Method Chaining

Use method chaining for cleaner code:

```php
$form = EasyForm::simple("Menu")
    ->button("Option 1")
    ->button("Option 2")
    ->button("Option 3")
    ->onClose(function($player) {
        // Handle close
    });
```

### 2. Input Validation

Always validate user input:

```php
->onSubmit(function($player, $data) {
    $username = $data[0];
    
    if (empty($username)) {
        $player->sendMessage("Username cannot be empty!");
        return;
    }
    
    if (strlen($username) < 3) {
        $player->sendMessage("Username must be at least 3 characters!");
        return;
    }
    
    // Process valid input
})
```

### 3. Error Handling

Handle errors gracefully:

```php
->onSubmit(function($player, $data) {
    try {
        // Process data
    } catch (\Exception $e) {
        $player->sendMessage("An error occurred: " . $e->getMessage());
    }
})
```

### 4. Form Navigation

Create navigation between forms:

```php
private function showMainMenu(Player $player): void
{
    $form = EasyForm::simple("Main Menu")
        ->button("Settings", null, function($player) {
            $this->showSettings($player);
        })
        ->button("Back", null, function($player) {
            $this->showPreviousMenu($player);
        });
    
    $player->sendForm($form);
}
```

### 5. Data Persistence

Store form data properly:

```php
->onSubmit(function($player, $data) {
    $config = $this->getConfig();
    $config->set($player->getName(), [
        "setting1" => $data[0],
        "setting2" => $data[1]
    ]);
    $config->save();
})
```

### 6. User Feedback

Always provide feedback:

```php
->onSubmit(function($player, $data) {
    // Process data
    $player->sendMessage("Settings saved successfully!");
})
->onClose(function($player) {
    $player->sendMessage("Settings not saved.");
})
```

### 7. Icon Usage

Use icons to enhance UI:

```php
->button("Shop", "textures/items/diamond", function($player) {
    // Handle click
})
->button("Stats", "https://example.com/icon.png", function($player) {
    // Handle click
})
```

### 8. Form Reusability

Create reusable form methods:

```php
private function createConfirmationForm(
    string $title,
    string $message,
    Closure $onConfirm
): ModalForm {
    return EasyForm::modal($title, $message, "Confirm", "Cancel")
        ->onYes($onConfirm)
        ->onNo(function($player) {
            $player->sendMessage("Action cancelled.");
        });
}
```

## Advanced Examples

### Dynamic Form Generation

```php
private function showPlayerList(Player $player): void
{
    $form = EasyForm::simple("Online Players", "Select a player:");
    
    foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
        $form->button($onlinePlayer->getName(), null, function($player) use ($onlinePlayer) {
            $this->showPlayerInfo($player, $onlinePlayer);
        });
    }
    
    $player->sendForm($form);
}
```

### Multi-Step Forms

```php
private function showStep1(Player $player): void
{
    $form = EasyForm::custom("Step 1")
        ->input("Name", "Enter your name")
        ->onSubmit(function($player, $data) {
            $this->showStep2($player, $data[0]);
        });
    
    $player->sendForm($form);
}

private function showStep2(Player $player, string $name): void
{
    $form = EasyForm::custom("Step 2")
        ->label("Welcome, " . $name)
        ->input("Email", "Enter your email")
        ->onSubmit(function($player, $data) use ($name) {
            $this->completeRegistration($player, $name, $data[0]);
        });
    
    $player->sendForm($form);
}
```

### Conditional Forms

```php
private function showAdminMenu(Player $player): void
{
    $form = EasyForm::simple("Admin Menu");
    
    if ($player->hasPermission("admin.ban")) {
        $form->button("Ban Player", null, function($player) {
            $this->showBanForm($player);
        });
    }
    
    if ($player->hasPermission("admin.kick")) {
        $form->button("Kick Player", null, function($player) {
            $this->showKickForm($player);
        });
    }
    
    $player->sendForm($form);
}
```

## Troubleshooting

### Form Not Showing

Ensure the player is online and the form is sent correctly:

```php
if ($player->isOnline()) {
    $player->sendForm($form);
}
```

### Callback Not Working

Check that closures are properly defined:

```php
->button("Test", null, function($player) {
    // This will work
})
```

### Data Array Index Issues

Remember that data array indices correspond to form element order:

```php
$form = EasyForm::custom("Form")
    ->input("Field 1")      // Index 0
    ->toggle("Toggle 1")    // Index 1
    ->slider("Slider 1")    // Index 2
    ->onSubmit(function($player, $data) {
        $field1 = $data[0];
        $toggle1 = $data[1];
        $slider1 = $data[2];
    });
```