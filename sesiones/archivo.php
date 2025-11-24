<?php

function generarXLS() {

    $tabla='<html><body>';
    $tabla.='<table>';
    $tabla.='<tr><td>id</td><td>producto</td><td>existencia</td></tr>';
    $tabla.='<tr><td>1</td><td>Televisor</td><td>12</td></tr>';
    $tabla.='<tr><td>2</td><td>Reproducto DVD</td><td>45</td></tr>';
    $tabla.='<tr><td>3</td><td>Radiograbadora</td><td>30</td></tr>';
    $tabla.='</table>';
    $tabla.='</body></html>';

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="existencia.xls"');
    header('Content-Transfer-Encoding: binary');
    print $tabla;

}

function generarDOC() {

    $texto='<html><body>';
    $texto.='<p>Un texto en <b>negritas</b> o bien en <em>it&aacute;licas</em></p>';
    $texto.='<p>Otro p&aacute;rrafo</p>';
    $texto.='</body></html>';

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="texto.doc"');
    header('Content-Transfer-Encoding: binary');
    print $texto;

}

if ($_REQUEST['extension'] == 'doc')
    generarDOC();
elseif ($_REQUEST['extension'] == 'xls')
    generarXLS();
else
    echo 'Generar documento en <a href="' . $_SERVER['PHP_SELF'] . '?extension=doc">Word</a>' .
    ' o en <a href="' . $_SERVER['PHP_SELF'] . '?extension=xls">Excel</a>';
?>