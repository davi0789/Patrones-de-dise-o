<?php
// Interfaz para los personajes
interface Character {
    public function getDescription(): string;
    public function getAttackPower(): int;
    public function attack(): string; 
    public function getSpeed(): int; 
    public function getDefense(): int; 
}


class Warrior implements Character {
    public function getDescription(): string {
        return "Guerrero";
    }

    public function getAttackPower(): int {
        return 10; 
    }

    public function attack(): string {
        return "El Guerrero ataca con su espada.";
    }

    public function getSpeed(): int {
        return 5; 
    }

    public function getDefense(): int {
        return 8; 
    }
}

class Mage implements Character {
    public function getDescription(): string {
        return "Mago";
    }

    public function getAttackPower(): int {
        return 8; 
    }

    public function attack(): string {
        return "El Mago lanza un hechizo.";
    }

    public function getSpeed(): int {
        return 6; 
    }

    public function getDefense(): int {
        return 4; 
    }
}

abstract class WeaponDecorator implements Character {
    protected $character;

    public function __construct(Character $character) {
        $this->character = $character;
    }

    public function getDescription(): string {
        return $this->character->getDescription();
    }

    public function getAttackPower(): int {
        return $this->character->getAttackPower();
    }

    public function attack(): string {
        return $this->character->attack();
    }

    public function getSpeed(): int {
        return $this->character->getSpeed();
    }

    public function getDefense(): int {
        return $this->character->getDefense();
    }
}

// Clases de armas
class Sword extends WeaponDecorator {
    public function getDescription(): string {
        return parent::getDescription() . " con espada"; 
    }

    public function getAttackPower(): int {
        return parent::getAttackPower() + 5; 
    }
}

class Staff extends WeaponDecorator {
    public function getDescription(): string {
        return parent::getDescription() . " con bastón"; 
    }

    public function getAttackPower(): int {
        return parent::getAttackPower() + 3; 
    }
}

$message = '';
$attackPower = 0;
$characterType = '';
$weaponName = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $characterType = $_POST['character'];
    $weaponType = $_POST['weapon'];

    switch ($characterType) {
        case 'warrior':
            $character = new Warrior();
            break;
        case 'mage':
            $character = new Mage();
            break;
        default:
            $message = "Personaje no válido.";
            break;
    }

    switch ($weaponType) {
        case 'sword':
            $character = new Sword($character);
            $weaponName = "Espada";
            break;
        case 'staff':
            $character = new Staff($character);
            $weaponName = "Bastón";
            break;
        default:
            $message = "Arma no válida.";
            break;
    }

    if (isset($character)) {
        $attackPower = $character->getAttackPower();
        $message = "Has creado un " . $character->getDescription() . " con poder de ataque " . $attackPower . ".";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Personajes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .result {
            display: flex;
            align-items: center; 
            margin-top: 10px;
        }
        .character-icon {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .attack-bar {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-left: 10px; 
        }
        .attack-fill {
            height: 20px;
            background-color: #4caf50;
            text-align: center;
            color: white;
            line-height: 20px; 
        }
    </style>
</head>
<body>

<h2>Crear Personajes y Añadir Armas</h2>

<form method="post">
    <label for="character">Selecciona un personaje:</label>
    <select name="character" id="character" required>
        <option value="">--Seleccione--</option>
        <option value="warrior">Guerrero</option>
        <option value="mage">Mago</option>
    </select>

    <label for="weapon">Selecciona un arma:</label>
    <select name="weapon" id="weapon" required>
        <option value="">--Seleccione--</option>
        <option value="sword">Espada</option>
        <option value="staff">Bastón</option>
    </select>

    <input type="submit" value="Crear Personaje">
</form>

<?php if ($message): ?>
    <h3>Resultado:</h3>
    <p><?php echo $message; ?></p>

    <div class="result">

        <?php if ($characterType == 'warrior'): ?>
            <img src="https://img.freepik.com/premium-vector/warrior-icon-logo-knight-symbol-shield_260216-640.jpg" alt="Guerrero" class="character-icon">
        <?php elseif ($characterType == 'mage'): ?>
            <img src="https://imgcdn.stablediffusionweb.com/2024/5/3/59cc2d3e-c1c9-40b3-8846-f57908d4afdd.jpg" alt="Mago" class="character-icon">
        <?php endif; ?>


        <div class="attack-bar" style="width: 70%;">
            <div class="attack-fill" style="width: <?php echo ($attackPower * 10); ?>%;">
                <?php echo $attackPower; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>