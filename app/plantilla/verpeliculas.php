<?php
include_once 'app/Pelicula.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>

<form action="index.php?orden=Buscar" method="post">
    <label>Nombre:</label>
    <input type="text" name="nombre">
    &nbsp;
    <label>Director:</label>
    <input type="text" name="director">
    &nbsp;
    <label>Género:</label>
    <input type="text" name="genero">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Buscar" name="submit">
</form>
</br>
<table>
    <th>Código</th>
    <th>Nombre</th>
    <th>Director</th>
    <th>Genero</th>
    <?php foreach ($peliculas as $peli) : ?>
        <tr>
            <td><?= $peli->codigo_pelicula ?></td>
            <td><?= $peli->nombre ?></td>
            <td><?= $peli->director ?></td>
            <td><?= $peli->genero ?></td>
            <td><a href="#" onclick="confirmarBorrar('<?= $peli->nombre . "','" . $peli->codigo_pelicula . "'" ?>);">Borrar</a></td>
            <td><a href="<?= $auto ?>?orden=Modificar&codigo=<?= $peli->codigo_pelicula ?>">Modificar</a></td>
            <td><a href="<?= $auto ?>?orden=Detalles&codigo=<?= $peli->codigo_pelicula ?>">Detalles</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</br>
<form name='f2' action='index.php'>
    <input type='hidden' name='orden' value='Alta'>
    <input type='submit' value='Nueva Película'>
</form>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>