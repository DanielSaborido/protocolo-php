<?php
// Posibles colores para combinaciones
$colores = array(
    "White" => "#ffffff",
    "Black" => "#000000",
    "Blue" => "#0000ff",
    "Green" => "#00ff00",
    "Red" => "#ff0000",
    "Yellow" => "#ffff00",
    "Cyan" => "#00ffff",
    "Light green" => "#90ee90",
    "Dark turquoise" => "#00ced1",
    "Purple" => "#ff00ff"
);

// Sortea una nueva combinación
function nuevoSecreto($colores) {
    $secreto = array();
    for($i=0;$i<4;$i++)
        $secreto[$i] = $colores[array_rand($colores)];
    return $secreto;
}

// Calcula resultados de un intento
function calculaResultado($intento, $secreto) {
    $resultado = array();
    
    for($i = 0; $i < 4; $i++) {
        if($intento[$i] == $secreto[$i]) {
            $resultado[] = "<input style='background-color:green' disabled type='color' value='$intento[$i]'>";
            continue;
        }
        if(in_array($intento[$i], $secreto)){
            $resultado[] = "<input style='background-color:yellow' disabled type='color' value='$intento[$i]'>";
        }
        else{
            $resultado[] = "<input disabled type='color' value='$intento[$i]'>";
        }
    }
    
    return $resultado;
}

function intentos(){
    if (!isset($_SESSION['intentos'])){
        $_SESSION['intentos']=1;
    }
    else{
        $_SESSION['intentos']++;
    }
    echo "<i>Intento numero ".$_SESSION['intentos']."</i>";
}

// Inicializa la sesión
session_start();
// Si existen datos en la sesión continúa el juego, si no crea nueva combinación
$secreto = isset($_SESSION["secreto"])? $_SESSION["secreto"] : nuevoSecreto($colores);
// Guarda el secreto en la sesión (solo tiene efecto con nueva combinación)
$_SESSION["secreto"] = $secreto;

if (!isset($_SESSION['guardadointen'])) {
    $_SESSION['guardadointen'] = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MasterMind</title>
</head>
<body>
<datalist id="presets">
    <?php
    // Crea lista de colores para desplegable de colores del formulario
    foreach ($colores as $name => $color) {
        print("<option value='$color'>$name</option>");
    }
    ?>
</datalist>
<?php
if(!empty($_POST)) {
    $intento = $_POST['intento'];
    echo "
    <br>
    <div>";
    intentos();
    echo "
    <form>
    ";
    $_SESSION['guardadointen'][] = calculaResultado($intento, $secreto);
    foreach ($_SESSION['guardadointen'] as $muestrainten) {
        print_r($muestrainten);
        echo "
        <br>";
    }
    echo "
    </form>
    <br>";
}
?>
<br>
<div>
  <i>Jugada</i>
  <br>
  <form action="" method="post">
      <?php
      for($i=0; $i<4; $i++) {
          print("<input type='color' list='presets' name='intento[$i]' value='");
          if(isset($intento)) print($intento[$i]);
          print("'>");
      }
      ?>
      <br>
      <br>
      <input type="submit" value="Intentar">
  </form>
  <form action="reiniciar.php">
      <input type="submit" value="Reiniciar">
  </form>
</div>
</body>
</html>
