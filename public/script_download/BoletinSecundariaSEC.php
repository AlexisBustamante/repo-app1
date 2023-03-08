<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boletin</title>
    <style>
    * {
        padding: 5px;
    }

    h1 {
        font-size: 24px;
    }

    .encabezado {
        width: 100%;
        display: flex;
        border: solid 1px black;
    }

    .asigColumn{
        text-align:left;
    }

    .datos_personales {
        display: flex;
        flex-direction: column;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 10px;
        text-align: center;
    }

    table {
        width: 100%;
    }

    tr {
        height: 15px;
    }

    .tbl_comportamiento {
        margin: 0px 20% 0px 20%;
    }

    .tbl_inasistencia {
        margin: 0px 30% 0px 30%;
    }

    .tbl_sansiones {
        margin: 0px 25% 0px 25%;
    }

    .tbl_pendientes {
        margin: 0px 15% 0px 15%;
    }

    .tbl_firmas {
        margin-top: 35px;
        display: flex;
        justify-content: space-between;
    }

    .firmas {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    span {
        padding: 2px;
        margin: 0px;
    }
    </style>
</head>


<?php 

var_dump($curso)

?>


<body>
    <div class="encabezado">
        <div class="logo">
            <img src="<?php echo ASSETS_DIR; ?>/images/colegios/unc.png" alt="<?=$this->colegio->nombre; ?>"
                style="float:right" width="75">
        </div>
        <div class="datos_personales">
            <h1> <?php echo $this->colegio->nombre; ?></h1>
            <span>Boletín de calificaciones de:
            <strong><?php echo $alumno->apellido.", ".$alumno->nombre; ?></strong> </span>
            <span>Curso: <strong><?php echo $curso->ano."º ".strtoupper($curso->division); ?></strong></span>
            <span><b>Curso:</b><?php echo $curso->ano; ?>   <b>Sección:</b><?php echo $curso->division; ?>  </span>
        </div>
    </div>

    <div class="tabla_notas">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">ASIGNATURAS</th>
                    <th colspan="9">PERIODOS DE EVALUACION</th>
                </tr>
                <tr>
                    <th>1ro</th>
                    <th>2do</th>
                    <th>3ero</th>
                    <th>FIN</th>
                    <th>DIC</th>
                    <th>MAR</th>
                    <th>Def</th>
                    <th>CONDICIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materias as $key => $materia) {
				if (! in_array($materia->id, $ocultar)) {
				    $uni = array(); # Materias unificadas
				    $acreditacion = 0;
	              ?>

                <tr>
                    <td class="asigColumn"><?php echo $materia->nombre; ?></td>
                    <td><?php echo (isset($final[$materia->id][1]) && $periodo >= 1) ? $final[$materia->id][1] : ''; ?>
                    </td>
                    <td><?php echo (isset($final[$materia->id][2]) && $periodo >= 1) ? $final[$materia->id][2] : ''; ?>
                    </td>
                    <td><?php echo (isset($final[$materia->id][3]) && $periodo >= 1) ? $final[$materia->id][3] : ''; ?>
                    </td>
                    <td>
                        <?php
                        #La nota Final promediada
		if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
			echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
			}
		?>
                    </td>

                    <td>
                        <?php echo (isset($complementarios[$materia->id]['dic']->nota)&& $periodo >= 1) ?  $complementarios[$materia->id]['dic']->nota : '';?>
                    </td>
                    <td>
                        <?php echo (isset($complementarios[$materia->id]['feb_mar']->nota)&& $periodo >= 1) ?  $complementarios[$materia->id]['dic']->nota : '';?>
                    </td>
                    <td>
                        <?php
                            if(isset($global[$materia->id]) && $global[$materia->id]->nota){
                                echo $global[$materia->id]->nota;
                            }
                    ?>
                    </td>
                    <td></td>



                </tr>


                <?php }} ?>
            </tbody>

        </table>

    </div>
    <div class="tbl_comportamiento">
        <table>
            <thead>
                <tr>
                    <th>COMPORTAMIENTO INTEGRAL</th>
                    <th>1ro</th>
                    <th>2do.</th>
                    <th>3ro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Respeto Por las Celebraciones Patrias y Religiosas</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Respeto y Colaboración Con Sus Pares</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Respeto y Colaboración Con Los Adultos</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Responsabilidad</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Uniforme y Presentación Personal</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tbl_inasistencia">
        <table>
            <thead>
                <tr>
                    <th>INASISTENCIAS</th>
                    <th>T1</th>
                    <th>T2</th>
                    <th>T3</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>JUSTIFICADAS</td>
                    <td><?php echo isset($inasistencias[1]['justificadas']) && $periodo>=1 ? $inasistencias[1]['justificadas']:'' ;?>
                    </td>
                    <td><?php echo isset($inasistencias[2]['justificadas']) && $periodo>=1 ? $inasistencias[2]['justificadas']:'' ;?>
                    </td>
                    <td><?php echo isset($inasistencias[3]['justificadas']) && $periodo>=1 ? $inasistencias[3]['justificadas']:'' ;?>
                    </td>
                    <td>
                        <?php 
                        $jus=0;
                        for($i=1;$i <= 3;$i++){

                            if (isset($inasistencias[$i]['justificadas'])) {
                                $jus+=$inasistencias[$i]['justificadas'];
                            }
                        }
                        echo $jus ;
                        ?>
                    </td>
                </tr>
                <tr>
                <td>INJUSTIFICADAS</td>
                <td><?php echo isset($inasistencias[1]['injustificadas']) && $periodo>=1 ? $inasistencias[1]['injustificadas']:'' ;?></td>
                <td><?php echo isset($inasistencias[2]['injustificadas']) && $periodo>=1 ? $inasistencias[2]['injustificadas']:'' ;?></td>
                <td><?php echo isset($inasistencias[3]['injustificadas']) && $periodo>=1 ? $inasistencias[3]['injustificadas']:'' ;?></td>

                <td>
                <?php 
                        $jus=0;
                        for($i=1;$i <= 3;$i++){

                            if (isset($inasistencias[$i]['injustificadas'])) {
                                $jus+=$inasistencias[$i]['injustificadas'];
                            }
                        }
                        echo $jus ;
                        ?>
                </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tbl_sansiones">
        <table>
            <thead>
                <tr>
                    <th>SANCIONES</th>
                    <th>CANTIDAD</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    $array_sanciones=array("Firma", "Apercibimiento", "Apercibimiento Grave");
                    for ($i=0; $i < sizeof($array_sanciones) ; $i++) {
                        $cant=0;

                        for($c=0; $c < sizeof($ciclo) ; $c++) {
                            if (isset($sanciones[$c][$array_sanciones[$i]])) {
                                $cant += $sanciones[$c][$array_sanciones[$i]];
                            }
                        }
                       echo '<tr><td>'.$array_sanciones[$i].'</td>'.'<td>'.$cant.'</td>'.'</tr>';
                  }?>
    
            </tbody>
        </table>
    </div>

    <div class="tbl_pendientes">
        <table>
            <thead>
                <tr>
                    <th>Asignaturas Pendientes</th>
                    <th>Año</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($previas as $key => $previa) {
                    echo '<tr>'.'<td>'.$previa->materia->nombre.'</td>'.'<td>'.$previa->materia->ano.'º</td>'.'</tr>';
                    }
                ?>
      
            </tbody>
        </table>
    </div>

    <div class="tbl_firmas">
        <div class="firmas">
            <span>_______________________________________</span>
            <span>Nombre rector</span>
            <span>
                <p><b>RECTOR</b></p>
            </span>
        </div>
        <div class="firmas">
            <span>_______________________________________</span>
            <span>
                <p><b>FIRMA DE PADRE / MADRE / TUTOR</b></p>
            </span>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>