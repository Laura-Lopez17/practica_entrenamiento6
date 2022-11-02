<!DOCTYPE html>

<!--practica_entrenamiento6-->

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>

<body>

    <?php
    function validarString($dato)
    {
        $datos = explode(' ', $dato);

        if (mb_strlen($dato, 'UTF-8') <= 0){ //si no hay caracteres devuelve falso
            return false;
        }

        if (count($datos) == 1 || count($datos) == 2) { // puede contener una o dos palabras
            return $dato;
        } else {
            return false;
        }
    }

    $imprimirFormulario = true;
    $datos = []; 

    if ($_POST) {

        $nombreSaneado = htmlentities(trim($_POST['nombre'])); //quitar los espacios y simbolos 
        $apellidosSaneado = htmlentities(trim($_POST['apellidos']));
        $edadSaneado = htmlentities(trim($_POST['edad']));
        $alturaSaneado = htmlentities(trim($_POST['altura']));

        $datos = [
            'nombre' => $nombreSaneado,
            'apellidos' => $apellidosSaneado,
            'edad' => $edadSaneado,
            'altura' => $alturaSaneado
        ];

        $argumentos = array(
            'nombre' => array(
                'filter' => FILTER_CALLBACK,
                'options' => 'validarString'
            ),
            'apellidos' => array(
                'filter' => FILTER_CALLBACK,
                'options' => 'validarString'
            ),
            'edad' => array(
                'filter' => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0)
            ),
            'altura' => array(
                'filter' => FILTER_VALIDATE_FLOAT,
                'options' => array('min_range' => 0.5, 'max_range' => 2.5)
            )
        );

        $validaciones = filter_var_array($datos, $argumentos); //PRIMERO DATOS Y LUEGO ARGUMENTOS 

        if ($validaciones['nombre'] && $validaciones['apellidos'] && $validaciones['edad'] && $validaciones['altura']) { //imprime el formulario si están bien todos los datos
            $imprimirFormulario = false;
            echo "<p><b>Nombre:</b> {$_POST['nombre']}\n</p>";

            echo "<p><b>Apellido:</b> {$_POST['apellidos']}\n</p>";

            echo "<p><b>Edad:</b> {$_POST['edad']}\n</p>";

            echo "<p><b>Altura:</b> {$_POST['altura']}\n</p>";
        }
    }

    if ($imprimirFormulario) {
    ?>


        <form action="#" method="post" >
            <p>
                Nombre: <input type="text" name="nombre" id="nombre" value="<?= array_key_exists('nombre', $datos) ? $datos['nombre'] : "" ?>"> <!-- ternaria para mostrar el dato si está bien -->
                <?php
                if ($_POST) {
                    if ($validaciones['nombre'] === false) {
                        echo "<p>Error: el nombre no es valido</p>";
                    }
                }
                ?>
            </p>

            <p>
                Apellidos: <input type="text" name="apellidos" id="apellidos" value="<?= array_key_exists('apellidos', $datos) ? $datos['apellidos'] : "" ?>">
                <?php
                if ($_POST) {
                    if ($validaciones['apellidos'] === false) {
                        echo "<p>Error: el nombre no es valido</p>";
                    }
                }
                ?>
            </p>
            <p>
                Edad: <input type="text" name="edad" id="edad" value="<?= array_key_exists('edad', $datos) ? $datos['edad'] : "" ?>">
                <?php
                if ($_POST) {
                    if ($validaciones['edad'] === false) {
                        echo "<p>Error: la edad debe ser un número entero.</p>";
                    }
                }
                ?>
            </p>
            <p>
                Altura: <input type="text" name="altura" id="altura" value="<?= array_key_exists('altura', $datos) ? $datos['altura'] : "" ?>">
                <?php
                if ($_POST) {
                    if ($validaciones['altura'] === false) {
                        echo "<p>Error: la altura debe ser mayor de 0.5 y menos de 2.5.</p>";
                    }
                }
                ?>
            </p>
            <p>
                <input type="submit" value="Enviar">
            </p>
        </form>
    <?php } ?>

</body>

</html>