<?php
interface Document {
    public function open();
}

// Clases de documentos antiguos
class Word2003 {
    public function open() {
        return "Abriendo documento de Word 2003.";
    }
}

class Excel2003 {
    public function open() {
        return "Abriendo documento de Excel 2003.";
    }
}

class PowerPoint2003 {
    public function open() {
        return "Abriendo presentación de PowerPoint 2003.";
    }
}

// Clases de documentos modernos
class Word2007 {
    public function open() {
        return "Abriendo documento de Word 2007.";
    }
}

class Excel2007 {
    public function open() {
        return "Abriendo documento de Excel 2007.";
    }
}

class PowerPoint2007 {
    public function open() {
        return "Abriendo presentación de PowerPoint 2007.";
    }
}

class WordAdapter implements Document {
    private $word;

    public function __construct($word) {
        $this->word = $word;
    }

    public function open() {
        return $this->word->open(); 
    }
}

class ExcelAdapter implements Document {
    private $excel;

    public function __construct($excel) {
        $this->excel = $excel;
    }

    public function open() {
        return $this->excel->open(); 
    }
}

class PowerPointAdapter implements Document {
    private $powerPoint;

    public function __construct($powerPoint) {
        $this->powerPoint = $powerPoint;
    }

    public function open() {
        return $this->powerPoint->open(); 
    }
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $file = $_FILES['document'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);

        switch ($fileType) {
            case 'doc':
                $word2003 = new Word2003();
                $adapter = new WordAdapter($word2003);
                $message = $adapter->open();
                break;
            case 'xls':
                $excel2003 = new Excel2003();
                $adapter = new ExcelAdapter($excel2003);
                $message = $adapter->open();
                break;
            case 'ppt':
                $powerPoint2003 = new PowerPoint2003();
                $adapter = new PowerPointAdapter($powerPoint2003);
                $message = $adapter->open();
                break;
            case 'docx':
                $word2007 = new Word2007();
                $adapter = new WordAdapter($word2007);
                $message = $adapter->open();
                break;
            case 'xlsx':
                $excel2007 = new Excel2007();
                $adapter = new ExcelAdapter($excel2007);
                $message = $adapter->open();
                break;
            case 'pptx':
                $powerPoint2007 = new PowerPoint2007();
                $adapter = new PowerPointAdapter($powerPoint2007);
                $message = $adapter->open();
                break;
            default:
                $message = "Tipo de archivo no válido. Solo se aceptan archivos .doc, .xls, .ppt, .docx, .xlsx, .pptx.";
                break;
        }
    } else {
        $message = "Error al subir el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Documentos en Windows 10</title>
</head>
<body>

<h2>Abrir Documentos de Office en Windows 10</h2>

<form method="post" enctype="multipart/form-data">
    <label for="document">Sube un archivo (Word, Excel, PowerPoint) en versiones antiguas o en windows 7:</label>
    <input type="file" name="document" id="document" accept=".doc, .xls, .ppt, .docx, .xlsx, .pptx" required>
    <input type="submit" value="Abrir Documento">
</form>

<?php if ($message): ?>
    <h3>Resultado:</h3>
    <p><?php echo $message; ?></p>
<?php endif; ?>

</body>
</html>