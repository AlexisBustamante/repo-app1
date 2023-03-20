<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
    * {
        font-family: system-ui;
        padding: 0px;
        box-sizing: border-box;
    }

    .encabezado {
        display: flex;
        justify-content: space-between;
    }

    .col1 {
        width: 100%;
        display: flex;
        justify-content: space-evenly;
    }

    .datos_alu {
        border: solid 1px black;
        display: flex;
        width: 55%;
        justify-content: space-around;
        font-size: 12px;
        height: 30%;
    }

    .datos2 {
        display: flex;
        justify-content: space-between;
        width: 45%;
        margin: 5px;
    }

    p {
        font-size: 12px;
    }

    .verticalText {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        font-size: 10px;
        padding: 10px;
    }

    .tabla {
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 1.5px solid black;
    }

    .tabla td {
        font-size: 12px;
        text-align: center;
    }

    td,
    th {
        border: 1px solid black;
        padding: 2px;
    }

    .subtotal {
        font-size: 12px;
    }

    .subtotal td {
        height: 25px;
    }

    .promueve {
        margin-top: 10px;
        border: 1px solid black;
    }

    img {
        height: 85px;
        width: 85px;
    }
    </style>
</head>

<body>
    <div class="encabezado">
        <div class="col1">
            <p>
            <h4><?=$this->colegio->nombre; ?></h4>
            </p>
            <p>
                <span><b>DIEGEP 8534</b></span>
            </p>
        </div>
        <div class="logo">
            <img src="<?php echo ASSETS_DIR; ?>/images/colegios/unc.png" alt="<?=$this->colegio->nombre; ?>">
        </div>
    </div>
    <div class="encabezado">
        <div class="datos_alu">
            <p>Alumno:<b><?php echo $alumno->apellido.", ".$alumno->nombre; ?></b></p>
            <p>DNI : <b><?php echo $alumno->dni ?></b></p>
        </div>
        <div class="datos2">
            <span>
                <p><?php echo $curso->ano."º año"?> de Secundaria</p>
            </span>
            <span>
                <p>Ciclo Lectivo <?=$inscripto->anio; ?></p>
            </span>
        </div>
    </div>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">ESPACIOS <br />CURRICULARES</th>

                    <?php 
                foreach($materias as $key => $materia){
                    if (! in_array($materia->id, $ocultar)) {
                        echo '<th rowspan="2" class="verticalText">'.$materia->nombre.'</th>';
                    }
                }
            ?>
                    <th rowspan="2" class="verticalText" style="background-color: grey; border: 1px black;">
                        Inasistencias</th>
                    <th colspan="2">Firmas</th>
                </tr>
                <tr>
                    <th>Autoridad</th>
                    <th>Padre/Madre</th>
                </tr>
            </thead>
            <tbody>


                <?php
           $inasT=0;
            for ($i=0; $i < $periodo; $i++) { 
               
           
                echo '<tr><td>' .($i+1).'ª Periodo</td>';
                
                #Calificaciones por Materia
                foreach($materias as $key => $materia){

                    if (! in_array($materia->id, $ocultar)) {
                        $valor = isset($final[$materia->id][$i+1]) ? $final[$materia->id][$i+1] :'';
                        echo '<td>'. $valor.'</td>';
                    }
                }

                #INASISTENCIAS
                $inas= isset( $inasistencias[$i+1]) ?  array_sum($inasistencias[$i+1]) : 0 ;
                $inasT+= $inas;
                echo '<td>'. $inas.'</td>';
                echo '<td></td>';
                echo '<td></td>';

            echo '</tr>';
            }

        ?>

                <?php 
        #LAS FILAS DE EXAMENES Y NOTAS FINALES
        if ($periodo==2) {?>
                <tr>
                    <td>Nota Final</td>
                    <?php 
                        foreach($materias as $key => $materia){
                            if (! in_array($materia->id, $ocultar)) {
                                $valor = isset($finalCiclo[$materia->id]) ? $finalCiclo[$materia->id]->nota :'';
                                echo '<td>'. $valor.'</td>';
                            }
                        }

                        #SUMA DE LAS INASISTENCIAS
                    ?>

                    <td><?php echo $inasT?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Diciembre</td>
                    <?php 
                        foreach($materias as $key => $materia){
                            if (! in_array($materia->id, $ocultar)) {
                                $valor = isset($complementarios[$materia->id]['dic']) ? $complementarios[$materia->id]['dic']->nota :'';
                                echo '<td>'. $valor.'</td>';
                            }
                        }
                    ?>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr>
                    <td>Feb/marzo</td>
                    <?php 
                        foreach($materias as $key => $materia){
                            if (! in_array($materia->id, $ocultar)) {
                                $valor = isset($complementarios[$materia->id]['feb_mar']) ? $complementarios[$materia->id]['feb_mar']->nota :'';
                                echo '<td>'. $valor.'</td>';
                            }
                        }
                    ?>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr>
                    <td>Nota Definitiva</td>
                    <?php 
                        foreach($materias as $key => $materia){
                            if (! in_array($materia->id, $ocultar)) {
                                $valor = isset($finalCiclo[$materia->id]) ? $finalCiclo[$materia->id]->nota :'';
                                echo '<td>'. $valor.'</td>';
                            }
                        }
                    ?>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>

                <?php }?>

            </tbody>
        </table>
    </div>

    <div class="subtotal">
        <table>
            <thead>
                <tr>
                    <th>MATERIA PENDIENTE</th>
                    <th>Año</th>
                    <th>Fecha</th>
                    <th>Nota</th>
                    <th>Libro/Folio</th>
                    <th>EQUIVALENCIA</th>
                    <th>Año</th>
                    <th>Fecha</th>
                    <th>Nota</th>
                    <th>Libro/folio</th>
                    <th>Firma del presidente <br> Comisión</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($previas as $key => $previa) { ?>
                <tr>
                    <td><?php $previa->materia->nombre ?></td>
                    <td><?php $previa->materia->ano?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <?php   } ?>



                <td colspan="12">
                    <p>
                        Trayectoria Educativa Avanzada (TEA): corresponde a las y los estudiantes que alcanzaron los
                        aprendizajes correspondientes y sostuvieron una buena vinculación pedagógica.
                        Trayectoria Educativa en Proceso (TEP): corresponde a las y los estudiantes que no han
                        alcanzado de forma suficiente los aprendizajes correspondientes, pero que mantienen una
                        buena vinculación pedagógica.
                        Trayectoria Educativa Discontinua (TED): corresponde a las y los estudiantes que no han
                        alcanzaron los aprendizajes correspondientes y que tuvieron una escasa vinculación
                        pedagógica, ya que los registros de los
                        avances respecto a los contenidos enseñados han tenido interrupciones o son de baja
                        intensidad.
                    </p>
                </td>
                </tr>
            </tbody>
        </table>
        <div class="promueve">
            <p><b> PROMUEVE A</b></p>
        </div>
        <p>
            Reglamento General, art. 450: Este boletín firmado, firmado por el Responsable Legal, será devuelto al
            establecimiento dentro de las 48 horas de recibido sin cuyo requisito el alumno no podrá volver a clase.
        </p>

    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>