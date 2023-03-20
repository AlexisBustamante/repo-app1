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
        text-align: center;
        width: 100%;
    }

    .subtitle {
        display: flex;
        margin-bottom: 10px;
        justify-content: space-between;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 1.5px solid black;
        font-size: 0.8rem;
    }

    td,
    th {
        border: 1px solid black;
        padding: 2px;
        text-align: center;
    }



    .enca {
        display: flex;
    }

    .verticalText {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        font-size: 10px;
        padding: 10px;
    }

    .verticalTextFinal {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        font-size: 10px;
        padding: 10px;
    }

    .tituloTabla {
        height: 25px;
    }

    .anchominimo {
        width: 5px;
    }

    .asigColumn {
        width: 175px;
    }

    .subtotal {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
    }

    .firma {
        margin-top: 25px;
    }
    .firma p {
        font-size: 10px;
        text-align: center;
    }

    img {
        width: 95px;
        height: 95px;
    }

    .inasistencias{
        display:flex;
        flex-direction: column;
    }
    
    </style>
</head>

<body>
    <div class="enca">
        <div class="logo">
            <img src="<?php echo ASSETS_DIR; ?>/images/colegios/unc.png" alt="<?=$this->colegio->nombre; ?>">
        </div>
        <div class="encabezado">
            <h3><?=$this->colegio->nombre; ?></h3>
            <h4>Año <?=$inscripto->anio; ?></h4>
            <div class="subtitle">
                <span> <b>Curso:</b> <?php echo $curso->ano."º ".strtoupper($curso->division); ?></span>
                <span><b>DNI:</b> <?php echo $alumno->dni ?></span>
                <span> <b>Alumno:</b> <?php echo $alumno->apellido.", ".$alumno->nombre; ?></span>
            </div>
        </div>
    </div>

    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th class="asigColumn" rowspan="2">Asignaturas</th>
                    <th class="tituloTabla" colspan="3">Primer Trimestre</th>
                    <th class="tituloTabla" colspan="3">Segundo Trimestre</th>
                    <th class="tituloTabla" colspan="3">Tercer Trimestre</th>
                    <th rowspan="2" class="verticalTextFinal">Calificacion Final</th>
                    <th rowspan="2" class="verticalTextFinal">Eval. Diciembre</th>
                    <th rowspan="2" class="verticalTextFinal">Eval. Febrero</th>
                    <th rowspan="2" class="verticalTextFinal">Calif. definitiva</th>
                </tr>
                <tr>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Actitudinal</th>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Actitudinal</th>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Actitudinal</th>
                </tr>
            </thead>
            <tbody>

                <?php

        #var_dump($ciclo);


        foreach ($materias as $key => $materia) {
          if (! in_array($materia->id, $ocultar)) {
						$uni = array(); # Materias unificadas
						$acreditacion = 0;
          ?>

                <tr>
                    <td><?php echo $materia->nombre; ?></td>
                    <td></td>
                    <td><?php echo (isset($final[$materia->id][1])&&$periodo>=1) ? $final[$materia->id][1] : '' ?></td>
                    <td></td>

                    <td></td>
                    <td><?php echo (isset($final[$materia->id][2])&&$periodo>=2) ? $final[$materia->id][2] : '' ?></td>
                    <td></td>

                    <td></td>
                    <td><?php echo (isset($final[$materia->id][3])&&$periodo>=3) ? $final[$materia->id][3] : '' ?></td>
                    <td></td>

                    <td>
                        <?php
                        #La nota Final Promedida
				            if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
				  						echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
				  			}
			  			?>
                    </td>

                    <td>
                        <?php
                        #La nota de Diciembre
									echo  isset($complementarios[$materia->id]['dic']->nota) ? $complementarios[$materia->id]['dic']->nota :'';
								?>
                    </td>
                    <td>
                        <?php
                        #La nota Final Promedida
				  					if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
				  						echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
				  					}
			  				  	?>
                    </td>
                    <td>
                        <?php
                        #La final ciclo
                        if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
                            echo isset($finalCiclo[$materia->id]->nota)  ? $finalCiclo[$materia->id]->nota :'';
                        }

			  				  	?>
                    </td>
                </tr>

                <?php }} ?>

            <tr>
                <td colspan="13" style="text-align:left"> <b>Promedio General</b> </td>
                <td> </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="subtotal">

        <div class="adhesion">
            <b>Adhesión a la normativa</b>
            <table>
                    <thead>
                        <tr>
                            <th>Trimestre</th>
                            <th>1º Trim</th>
                            <th>2ª Trim</th>
                            <th>3ª Trim</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>Presentación</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Respeto a las normas</td>
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
                    </tbody>
            </table>   
        </div>



        <div class="acuerdo">
       <b> Sanciones disciplinarias</b>
            <table>
                <thead>
                    <tr>
                        <th>Trimestre</th>
                        <th>1º Trim</th>
                        <th>2ª Trim</th>
                        <th>3ª Trim</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                #construir de forma dinamica las sansiones.
                $at_san = array("Llamados de atención","Amonestaciones");

                for ($i=0; $i < sizeof($at_san); $i++) { 

                  echo '<tr>';
                  echo '<td>'.$at_san[$i].'</td>';

                  for ($c=0; $c < 3; $c++) { 
                    
                    $valor = isset($sanciones[$c][$at_san[$i]]) ? $sanciones[$c][$at_san[$i]] : '0';
                    echo '<td>'.$valor.'</td>';
                  }
                  echo '</tr>';
                }

                ?>

                </tbody>
            </table>

            <div class="firma">
                <p>_________________________________________</p>
                <p>Prof. Lucía E. Cruz Prats Griet</p>
                <p>Directora</p>

            </div>

        </div>

        <div class="inasistencias">
        <b> Inasistencias</b>
            <table>
                <thead>
                    <tr>
                         <th>Trimestre</th>
                        <th>1º Trim</th>
                        <th>2ª Trim</th>
                        <th>3ª Trim</th>
                        <th>Total</th>

                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td>Inasistencias</td>
                <?php 

                    $inaT=0;
                    for ($i=0; $i <3 ; $i++) { 
            
                        $inas= isset( $inasistencias[$i+1]) ?  array_sum($inasistencias[$i+1]) : 0 ;
                        $inaT+=$inas;
                    echo '<td>'.$inas.'</td>';

                    }

                    echo '<td>'.$inaT.'</td>';

                    ?>
                </tr>
       
                </tbody>
            </table>
            <div class="firma">
                <p>_________________________________________</p>
                <p>Firma del Padre / Madre / Tutor</p>
                <p>Aclaración y fecha</p>

            </div>
        </div>
       
    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>