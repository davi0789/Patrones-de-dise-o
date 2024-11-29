<?php
interface OutputStrategy {
    public function output($message);
}

class ConsoleOutput implements OutputStrategy {
    public function output($message) {
        echo "Consola: " . $message . PHP_EOL;
    }
}

class JsonOutput implements OutputStrategy {
    public function output($message) {
        echo json_encode(["message" => $message]) . PHP_EOL;
    }
}

class FileOutput implements OutputStrategy {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function output($message) {
        file_put_contents($this->filename, $message . PHP_EOL, FILE_APPEND);
        return "Mensaje guardado en archivo: " . $this->filename;
    }
}

class MessageContext {
    private $strategy;

    public function setStrategy(OutputStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function showMessage($message) {
        return $this->strategy->output($message);
    }
}

$outputResult = '';
$fileContent = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $outputType = $_POST['outputType'];

    $context = new MessageContext();

    switch ($outputType) {
        case 'console':
            ob_start(); 
            $context->setStrategy(new ConsoleOutput());
            $outputResult = $context->showMessage($message);
            $outputResult = ob_get_clean(); 
            break;
        case 'json':
            ob_start();
            $context->setStrategy(new JsonOutput());
            $outputResult = $context->showMessage($message);
            $outputResult = ob_get_clean();
            break;
        case 'file':
            $context->setStrategy(new FileOutput('output.txt'));
            $outputResult = $context->showMessage($message);
            $fileContent = file_get_contents('output.txt'); 
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salida de Mensajes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .output {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .console {
            background-color: #f8f9fa;
            color: #333;
            font-family: monospace;
            padding: 10px;
            border-left: 5px solid #007bff;
        }
        .json {
            background-color: #e9ecef;
            color: #333;
            padding: 10px;
            border-left: 5px solid #28a745;
            white-space: pre-wrap;
        }
        .file {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-left: 5px solid #dc3545;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: monospace;
            resize: none;
        }
    </style>
</head>
<body>

<h2>Mostrar Mensajes en Diferentes Formatos</h2>

<form method="post">
    <label for="message">Ingrese su mensaje:</label>
    <input type="text" name="message" id="message" required>

    <label for="outputType">Seleccione el tipo de salida:</label>
    <select name="outputType" id="outputType" required>
        <option value="console">Consola</option>
        <option value="json">JSON</option>
        <option value="file">Archivo TXT</option>
    </select>

    <input type="submit" value="Enviar">
</form>

<?php if (!empty($outputResult)): ?>
    <div class="output">
        <h3>Resultados:</h3>
        <?php
            if (strpos($outputResult, 'Consola:') !== false) {
                echo '<div class="console">' . nl2br(htmlspecialchars($outputResult)) . '</div>';
            } elseif (strpos($outputResult, '{') === 0) {
                echo '<div class="json">' . nl2br(htmlspecialchars($outputResult)) . '</div>';
            } else {
                echo '<div class="file">' . nl2br(htmlspecialchars($outputResult)) . '</div>';
                echo '<h4>Contenido del archivo:</h4>';
                echo '<textarea readonly>' . htmlspecialchars($fileContent) . '</textarea>';
            }
        ?>
    </div>
<?php endif; ?>

</body>
</html>