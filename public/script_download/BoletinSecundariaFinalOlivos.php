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
        font-family: system-ui;
        box-sizing: border-box;
    }

    h1 {
        font-size: 24px;
    }

    .encabezado {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }

    .asigColumn {
        text-align: left;
    }

    .datos_personales {
        display: flex;
        flex-direction: column;
        justify-content: center;

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


    .tbl_firmas {
        width: 100%;
        margin-top: 45px;
        display: flex;
        justify-content: space-around;
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

    .linea1 {
        width: 100%;
        height: 1px;
        background-color: green;
    }

    .linea2 {
        width: 100%;
        height: 0.2px;
        background-color: yellow;
    }

    .subtotal {
        display: flex;
        justify-content: space-around;
    }
    </style>
</head>


<?php 
?>


<body>
    <div class="encabezado">
        <div class="logo">
            <img height="75" src="<?php echo ASSETS_DIR; ?>/images/colegios/<?=$this->colegio->id; ?>.png"
                alt="<?=$this->colegio->nombre; ?>">
        </div>
        <div class="datos_personales">
            <h1 style="text-align: center; color:blue;"> Informe Final de desempeño del alumno</h1>
        </div>
        <div>
            <img height="75" src="<?php echo ASSETS_DIR; ?>/images/colegios/2336_apdes.png"
                alt="<?=$this->colegio->nombre; ?>">
        </div>
    </div>
    <div class="linea1"></div>
    <div class="linea2"></div>
    <div class="datos_personales">
        <span><span style="color:blue"><b>Alumno :</b></span>
            <?php echo $alumno->apellido.", ".$alumno->nombre; ?>
        </span>
        <span>
            <span style="text-align: left;color:blue;"> <b>Curso:</b></span>
            <span> <?php echo $curso->ano.'º'; ?><?php echo $curso->division; ?></span>
            <span style="text-align: left;color:blue;margin-left:25% "> <b>Año:</b></span>
            <span style="text-align: center;"><?php echo $inscripto->anio ?></span>
        </span>
    </div>
    <div class="tabla_notas">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Materia</th>
                    <th colspan="2">CALIFICACIONES</th>
                    <th rowspan="2" style="width:80px">EXAMEN <br> DIC/FEB</th>
                    <th rowspan="2" style="width:80px;color:blue">N.F:P <br> (Nota Final Ponderada)</th>
                </tr>
                <tr>
                    <th style="width:80px">ANUAL</th>
                    <th style="width:80px">PROMEDIO</th>
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
                    <td><?php echo isset($finalCiclo[$materia->id]->nota) ? $finalCiclo[$materia->id]->nota :''; ?></td>
                    <td><?php echo isset($finalCiclo[$materia->id]->nota) ? $finalCiclo[$materia->id]->nota :''; ?></td>
                    <td><?php echo isset($complementarios[$materia->id]['dic']->nota) ? $complementarios[$materia->id]['dic']->nota :''; ?>
                    </td>
                    <td><?php echo isset($finalCiclo[$materia->id]->nota) ? $finalCiclo[$materia->id]->nota :''; ?></td>

                </tr>


                <?php }} ?>
            </tbody>

        </table>

    </div>


    <div class="subtotal">
        <div class="ina">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Inasistencias</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Res. 558 - DGE-19</td>
                        <td>Anual</td>
                    </tr>
                    <tr>
                        <td>Justificadas</td>
                        <td>
                            <?php 
							$total=0;
								for ($p=1; $p <= (sizeof($ciclo)-1) ; $p++) { 
									$total+=isset($inasistencias[$p]["justificadas"]) ? $inasistencias[$p]["justificadas"]:0;
								}
							echo $total;
							?>
                        </td>
                    </tr>
                    <tr>
                        <td>Injustificadas</td>
                        <td>
                            <?php 
							$totalI=0;
								for ($p=1; $p <= (sizeof($ciclo)-1) ; $p++) { 
									$totalI+=isset($inasistencias[$p]["injustificadas"]) ? $inasistencias[$p]["injustificadas"]:0;
								}
							echo $totalI;
							?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="san">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Sanciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">Res. 558 - DGE-19</td>
                    </tr>
                    <tr>
                        <td style="font-size: 7px;" colspan="2">Puntaje inicial por ciclo lectivo 25 puntos</td>
                    </tr>
                    <tr>
                        <td rowspan="2">ICE</td>
                        <td>Anual</td>
                    </tr>
                    <tr>
                        <td>
                            <?php 
								$totalS=0;
							for ($p=1; $p <= sizeof($ciclo)-1; $p++) { 
								$totalS+=isset($sanciones[$p]["amonestaciones"]) ? $inasistencias[$p]["amonestaciones"]:0;
								$totalS+=isset($sanciones[$p]["suspensiones"]) ? $inasistencias[$p]["suspensiones"]:0;
								$totalS+=isset($sanciones[$p]["apercibimientos"]) ? $inasistencias[$p]["apercibimientos"]:0;
							}
								echo $totalS;
							?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="tbl_firmas">
        <div class="firmas">
            <span>_______________________</span>
            <span style="font-size:10px;">Firma del Director</span>
        </div>

        <div class="firmas">
            <span>_________________________</span>
            <span style="font-size:10px;">
                Firma Padre/Madre/Tutor
            </span>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>