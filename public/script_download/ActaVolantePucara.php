<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acta Volante</title>
    <style type="text/css">
    * {
        font-family: system-ui;
        padding: 0px;
        box-sizing: border-box;
    }


    .encabezado {
        display: flex;
        justify-content: space-between;
        padding: 10px;
    }


    .logo {
        width: 75px;
        height: 75px;
    }


    .recuadro {
        border: 1px solid black;
        height: 25px;
        width: 45px;
        margin-left: 5px;
    }


    .folio {
        display: flex;
        align-items: center;
        margin: 3px;
        justify-content: end;
    }


    .recuadro_2 {
        border: 1px solid black;
        padding: 5px;
        margin-bottom: 5px;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid black;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }


    td,
    th {
        border: 1px solid black;
        padding: 4px 8px;
    }


    .ctn-enc {
        display: flex;
        flex-direction: column;
        border: 1px solid black;
        margin-bottom: 5px;
    }


    .datosGeneral {
        display: flex;
        flex-direction: column;
        padding: 5px;
    }


    .datosAsig {
        display: flex;
        justify-content: space-between;
        padding: 3px;
    }


    .rec_fila {
        border-bottom: 1px solid black;
        text-align: center;
    }


    .sub_firmas {
        margin-top: 15px;
        border: 1px solid black;
        display: flex;
        flex-direction: column;
    }


    .firma {
        display: flex;
        flex-direction: column;
        align-items: center;
    }


    .firmas {
        margin-top: 25px;
        display: flex;
        justify-content: space-around;
    }


    .totales {
        padding: 10px;
        margin-top: 25px;
        margin-right: 10px;
        display: flex;
        justify-content: end;
    }


    .texto {
        font-size: 12px;
    }
    </style>
</head>


<body>
    <?php 
    //var_dump($mesaPrevia);
    $hasta=20;
    ?>
    <div class="ctn-enc">
        <div class="encabezado">
            <div>
                <div class="logo">
                    <img height="70" src="<?php echo ASSETS_DIR; ?>/images/colegios/<?=$this->colegio->id; ?>.png"
                        alt="<?=$this->colegio->nombre; ?>">
                </div>
            </div>
            <div>
                <h2><?php echo $this->colegio->nombre; ?></h2>
            </div>
            <div class="datos_der">
                <div class="folio">
                    <span>Folio</span>
                    <div class="recuadro"></div>
                </div>
                <div class="folio_bottom">
                    <div class="recuadro_2">
                        <span>ACTA VOLANTE DE EXÁMENES</span>
                    </div>
                </div>
                <div class="tbl_simple">
                    <table>
                        <tr>
                            <td>DIA</td>
                            <td>MES</td>
                            <td>AÑO</td>
                        </tr>


                        <tr>
                            <?php $fecha = explode('/', date('d/m/Y',$mesaPrevia->fecha));?>
                            <td><?php echo $fecha[0]; ?></td>
                            <td><?php echo $fecha[1]; ?></td>
                            <td><?php echo $fecha[2]; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="datosGeneral">
            <div style="padding:5px;">
                <span>Exámenes de alumnos &nbsp</span>
                <span>______________________________________</span>
            </div>
            <div class="datosAsig">
                <div class="datosAsig">
                    <span>ASIGNATURA: &nbsp</span>
                    <div class="rec_fila">
                        <span><b>&nbsp <?php echo $mesaPrevia->materia->nombre; ?> &nbsp</b></span>
                    </div>
                </div>
                <div class="datosAsig">
                    <span>AÑO: &nbsp</span>
                    <div class="rec_fila">
                        <span>&nbsp <?php echo $mesaPrevia->materia->ano; ?> º &nbsp</span>
                    </div>
                </div>
                <div class="datosAsig">
                    <span>DIV: &nbsp</span>
                    <div class="rec_fila">
                        <span>&nbsp <?php echo $mesaPrevia->_inscriptos[0]->curso->division; ?> &nbsp</span>
                    </div>
                </div>
                <div class="datosAsig">
                    <span>TURNO: &nbsp</span>
                    <div class="rec_fila">
                        <span>&nbsp <?php echo strtoupper($mesaPrevia->curso->turno); ?> &nbsp</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="tbl_acta">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">
                        Nº de <br />
                        ORD
                    </th>
                    <th rowspan="2">
                        Documento de <br />
                        Identidad
                    </th>
                    <th rowspan="2">Apellidos y Nombres</th>
                    <th rowspan="2">Curso - Año</th>
                    <th colspan="3">Calificaciones</th>
                </tr>
                <tr>
                    <th>Esc.</th>
                    <th>Oral</th>
                    <th>Prom</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = $desde; $i < $hasta; $i++): ?>
                <?php if(isset($mesaPrevia->_inscriptos[$i])): ?>
                <?php
                                $obs = explode(' ',$mesaPrevia->_inscriptos[$i]->materiaPreviaObservacion);
                                $l = 0; $f = 0;
                                if (sizeof($obs)) {
                                    foreach ($obs as $key => $observacion) {
                                        $parts = explode(':', $observacion);
                                        if (strrpos(mb_strtolower($observacion), "libro:") !== false || strrpos(mb_strtolower($observacion), "l:") !== false) {
                                            foreach ($parts as $key => $part) {
                                                if (is_numeric($part)) {
                                                    $l = $part;
                                                }
                                            }
                                        }


                                        if (strrpos(mb_strtolower($observacion), "folio:") !== false || strrpos(mb_strtolower($observacion), "f:") !== false) {
                                            foreach ($parts as $key => $part) {
                                                if (is_numeric($part)) {
                                                    $f = $part;
                                                }
                                            }
                                        }
                                    }
                                }
                            ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $mesaPrevia->_inscriptos[$i]->dni; ?></td>
                    <td><?php echo mb_strtoupper($mesaPrevia->_inscriptos[$i]->apellido, 'UTF-8').' '.$mesaPrevia->_inscriptos[$i]->nombre; ?>
                    </td>
                    <td>
                        <?php echo $mesaPrevia->_inscriptos[$i]->curso->ano.'º '.$mesaPrevia->_inscriptos[$i]->curso->division.' - '.$mesaPrevia->_inscriptos[$i]->anio; ?>
                    </td>
                    <td>&nbsp</td>
                    <td>
                        <?php
                                            if (isset($mesaPrevia->_inscriptos[$i]->materiaPreviaNota) && $mesaPrevia->_inscriptos[$i]->materiaPreviaNota) {
                                                echo $mesaPrevia->_inscriptos[$i]->materiaPreviaNota;
                                            }
                                        ?>
                    </td>
                    <td>
                        <?php
                                            if (isset($mesaPrevia->_inscriptos[$i]->materiaPreviaNota) && $mesaPrevia->_inscriptos[$i]->materiaPreviaNota) {
                                                if($mesaPrevia->_inscriptos[$i]->materiaPreviaNota == 'Aus.' ){
                                                    echo 'Ausente';
                                                }
                                                else{
                                                    $nota = floor($mesaPrevia->_inscriptos[$i]->materiaPreviaNota);
                                                    if($nota == 1){
                                                        echo 'Uno';
                                                    }
                                                    elseif($nota == 2){
                                                        echo 'Dos';
                                                    }
                                                    elseif($nota == 3){
                                                        echo 'Tres';
                                                    }
                                                    elseif($nota == 4){
                                                        echo 'Cuatro';
                                                    }
                                                    elseif($nota == 5){
                                                        echo 'Cinco';
                                                    }
                                                    elseif($nota == 6){
                                                        echo 'Seis';
                                                    }
                                                    elseif($nota == 7){
                                                        echo 'Siete';
                                                    }
                                                    elseif($nota == 8){
                                                        echo 'Ocho';
                                                    }
                                                    elseif($nota == 9){
                                                        echo 'Nueve';
                                                    }
                                                    elseif($nota == 10){
                                                        echo 'Diez';
                                                    }
                                                    elseif($nota == 'Aus.'){
                                                        echo 'Ausente';
                                                    }


                                                    if($mesaPrevia->_inscriptos[$i]->materiaPreviaNota - $nota)
                                                    {
                                                        echo ' con 50/100';
                                                    }
                                                }
                                            }
                                        ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr class="cuerpo">
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                </tr>
                <?php endif; ?>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>


    <div class="sub_firmas">
        <div class="firmas">
            <div class="firma">
                <span>_________________________</span>
                <span>Presidente</span>
            </div>
            <div class="firma">
                <span>_________________________</span>
                <span>Vocal</span>
            </div>
            <div class="firma">
                <span>_________________________</span>
                <span>Vocal</span>
            </div>
        </div>


        <div class="totales">
            <div class="tot">
                <div style="display: flex;">
                    <span class="texto">Total de Alumno: &nbsp;</span>
                    <div style="border-bottom:solid 1px black; width:60%; text-align:center;">
                        <span><?php echo sizeof($mesaPrevia->_inscriptos)?></span>
                    </div>
                </div>
                <div>
                    <span class="texto">Total Ausentes:</span>
                    <span>_____________________</span>
                </div>
                <div>
                    <span class="texto">Total No Aprobados:</span>
                    <span>_____________________</span>
                </div>
                <div>
                    <span class="texto">Total Aprobados:</span>
                    <span>________________________</span>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>


</html>