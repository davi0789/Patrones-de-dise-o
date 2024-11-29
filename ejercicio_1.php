<?php
interface Character {
    public function attack();
    public function getSpeed();
    public function getDefense();
    public function getDescription();
    public function getAttackPower();
}

class Skeleton implements Character {
    public function attack() {
        return "El Esqueleto ataca con su espada.";
    }

    public function getSpeed() {
        return "Lenta";
    }

    public function getDefense() {
        return "Baja";
    }

    public function getDescription() {
        return "Un esqueleto reanimado que busca venganza.";
    }

    public function getAttackPower() {
        return 40; 
    }
}

class Zombie implements Character {
    public function attack() {
        return "El Zombi muerde a su oponente.";
    }

    public function getSpeed() {
        return "Media";
    }

    public function getDefense() {
        return "Media";
    }

    public function getDescription() {
        return "Un zombi hambriento de carne.";
    }

    public function getAttackPower() {
        return 60; 
    }
}

class CharacterFactory {
    public static function createCharacter($level) {
        if ($level === 'easy') {
            return new Skeleton();
        } elseif ($level === 'hard') {
            return new Zombie();
        }
        throw new Exception("Nivel no válido.");
    }
}

// Uso
$attackMessage = '';
$speedMessage = '';
$defenseMessage = '';
$descriptionMessage = '';
$speedValue = 0; // Valor inicial
$attackValue = 0; // Valor inicial
$defenseValue = 0; // Valor inicial

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $level = $_POST['level'];

    try {
        $character = CharacterFactory::createCharacter($level);
        $attackMessage = $character->attack();
        $speedMessage = $character->getSpeed();
        $defenseMessage = $character->getDefense();
        $descriptionMessage = $character->getDescription();
        
        $speedValue = ($speedMessage === "Lenta") ? 30 : 70; 
        $attackValue = $character->getAttackPower(); 
        $defenseValue = ($defenseMessage === "Baja") ? 20 : 50; 
    } catch (Exception $e) {
        $attackMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1: Patrón Factory</title>
    <style>
        .bar {
            width: 100%;
            background-color: #f3f3f3;
            margin-left: 10px;
            display: none; /* Ocultar las barras por defecto */
        }
        .bar div {
            height: 20px;
            transition: width 0.2s; /* Suaviza la animación */
        }
        .list {
            list-style-type: none;
            padding: 0;
        }
        .list-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .label {
            width: 80px;
        }
    </style>
    <script>
        function loadBars(speedValue, attackValue, defenseValue) {
            const speedBar = document.getElementById('speedBar');
            const attackBar = document.getElementById('attackBar');
            const defenseBar = document.getElementById('defenseBar');

            speedBar.style.width = '0%';
            attackBar.style.width = '0%';
            defenseBar.style.width = '0%';

            // Mostrar las barras
            speedBar.parentElement.style.display = 'block';
            attackBar.parentElement.style.display = 'block';
            defenseBar.parentElement.style.display = 'block';

            let speedWidth = 0;
            let attackWidth = 0;
            let defenseWidth = 0;

            const interval = setInterval(() => {
                if (speedWidth < speedValue) {
                    speedWidth++;
                    speedBar.style.width = speedWidth + '%';
                }
                if (attackWidth < attackValue) {
                    attackWidth++;
                    attackBar.style.width = attackWidth + '%';
                }
                if (defenseWidth < defenseValue) {
                    defenseWidth++;
                    defenseBar.style.width = defenseWidth + '%';
                }
                if (speedWidth >= speedValue && attackWidth >= attackValue && defenseWidth >= defenseValue) {
                    clearInterval(interval);
                }
            }, 20);
        }
    </script>
</head>
<body>

<form method="post">
    <label for="level">Seleccione el nivel del juego:</label>
    <select name="level" required>
        <option value="">--Seleccione--</option>
        <option value="easy">Fácil</option>
        <option value="hard">Difícil</option>
    </select>
    <input type="submit" value="Crear Personaje">
</form>

<div>
    <h3>Resultados:</h3>
    <p><?php echo $attackMessage; ?></p>
    <p><?php echo $descriptionMessage; ?></p> <!-- Mostrar la descripción del personaje -->
    
    <ul class="list">
        <li class="list-item">
            <span class="label">Velocidad: <?php echo $speedValue; ?>%</span>
            <div class="bar">
                <div id="speedBar" style="background-color: #4caf50;"></div>
            </div>
        </li>
        <li class="list-item">
            <span class="label">Ataque: <?php echo $attackValue; ?>%</span>
            <div class="bar">
                <div id="attackBar" style="background-color: #2196F3;"></div>
            </div>
        </li>
        <li class="list-item">
            <span class="label">Defensa: <?php echo $defenseValue; ?>%</span>
            <div class="bar">
                <div id="defenseBar" style="background-color: #FF9800;"></div>
            </div>
        </li>
    </ul>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <script>
        loadBars(<?php echo $speedValue; ?>, <?php echo $attackValue; ?>, <?php echo $defenseValue; ?>);
    </script>
<?php endif; ?>

</body>
</html>
