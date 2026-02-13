<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Registros</title>
</head>
<body>
<h1>Tabla Log de Registros Fallidos Para Cargue De Acuses</h1>
    <table border="1">
        <?php
            require_once("./logsAcuses.php");
            $reg = new logsAcuses();
            $registros = $reg->dataFailed();
            if(!empty($registros)):
        ?>
        <thead>
            <tr>
                <th>Radicado</th>
                <th>Estado</th>
                <th>Email</th>
                <th>UUID</th>
                <th>Tipo de Fallo</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $registro):?>
                        <tr>
                            <td><?=$registro['RADICADO']?></td>
                            <td><?=$registro['ESTADO']?></td>
                            <td><?=$registro['EMAIL']?></td>
                            <td><?=$registro['UUID']?></td>
                            <td><?=$registro['TIPO_FALLO']?></td>
                            <td><?=$registro['FECHA_REGISTRO']?></td>
                        </tr>
                <?php endforeach;?>            
            <?php else:?>
                <h2>No se reportar registros Fallidos</h2>            
            <?php endif;?>            
        </tbody>
    </table>
</body>
</html>