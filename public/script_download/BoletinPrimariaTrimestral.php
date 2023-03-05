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
        border-collapse: collapse;
        border: 1.5px solid black;
        font-size: 0.8rem;
    }

    td,
    th {
        border: 1px solid black;
        padding: 2px;
    }



    .enca {
        display: flex;
    }

    .verticalText {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        padding: 0px;
        margin: 0px;
        font-size: 10px;
    }

    .verticalTextFinal {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        height: 110px;
        font-size: 10px;
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

    .firm {
        width: 250px;
        height: 35px;
        border: 1px solid black;
    }

    img {
        width: 95px;
        height: 95px;
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
                    <th rowspan="2" class="verticalTextFinal">Eval. Marzo</th>
                    <th rowspan="2" class="verticalTextFinal">Calif. definitiva</th>
                </tr>
                <tr>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Disciplina</th>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Disciplina</th>
                    <th class="verticalText">Recuperación</th>
                    <th class="verticalText">Nota Final</th>
                    <th class="verticalText">Disciplina</th>
                </tr>
            </thead>
            <tbody>

                <?php
        foreach ($materias as $key => $materia) {
          if (! in_array($materia->id, $ocultar)) {
						$uni = array(); # Materias unificadas
						$acreditacion = 0;
          ?>

                <tr>
                    <td><?php echo $materia->nombre; ?></td>

                    <td></td>
                    <td><?php echo (isset($final[$materia->id[1]])&&$periodo>=1) ? $final[$materia->id[1]] : '' ?></td>
                    <td></td>

                    <td></td>
                    <td><?php echo (isset($final[$materia->id[2]])&&$periodo>=1) ? $final[$materia->id[2]] : '' ?></td>
                    <td></td>

                    <td></td>
                    <td><?php echo (isset($final[$materia->id[3]])&&$periodo>=1) ? $final[$materia->id[3]] : '' ?></td>
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
									if (isset($materia->unificada) && $materia->unificada && sizeof($uni) == 3 && $periodo >= 3) { # Diciembre
										$comp = array();
										foreach ($materia->materias as $key => $mat) {
											if (isset($complementarios[$mat]['dic']->nota)) {
												$comp['dic'][$key] = $complementarios[$mat]['dic']->nota;
											}
										}

										if (isset($comp['dic']) && sizeof($comp['dic'])) {
											if (sizeof($materia->materias) == sizeof($comp['dic'])) {
												$dic = $this->colegio->calculos->redondeo(array_sum($comp['dic'])/sizeof($comp['dic']),$curso);
												echo $acreditacion = $dic < 10 ? truncar_final($dic,2) : $dic;
											}
											else {
												if (isset($comp['dic'][0]) && $comp['dic'][0] >= $materia->apruebaCon) {
													echo $acreditacion = $comp['dic'][0];
												}
												else {
													echo $comp['dic'][0];
												}
											}
										}
									}
									elseif(isset($complementarios[$materia->id]['dic']->nota) && $periodo >= 3) {
										echo $acreditacion = $complementarios[$materia->id]['dic']->nota;
									}
								?>
                    </td>
                    <td>
                        <?php
                        #la nota Feb_Marzo de la Asignatura
                        if (isset($materia->unificada) && $materia->unificada && sizeof($uni) == 3 && $periodo >= 3) { # Diciembre
                          $comp = array();
                          foreach ($materia->materias as $key => $mat) {
                            if (isset($complementarios[$mat]['feb_mar']->nota)) {
                              $comp['feb_mar'][$key] = $complementarios[$mat]['feb_mar']->nota;
                            }
                          }

                          if (isset($comp['feb_mar']) && sizeof($comp['feb_mar'])) {
                            if (sizeof($materia->materias) == sizeof($comp['feb_mar'])) {
                              $feb = $this->colegio->calculos->redondeo(array_sum($comp['feb_mar'])/sizeof($comp['feb_mar']),$curso);
                              echo $feb < 10 ? truncar_final($feb,2) : $feb;
                            }
                            else {
                              if (isset($comp['feb_mar'][0]) && $comp['feb_mar'][0] >= $materia->apruebaCon) {
                                echo  $comp['feb_mar'][0];
                              }
                              else {
                                echo $comp['feb_mar'][0];
                              }
                            }
                          }
                        }
                        elseif(isset($complementarios[$materia->id]['feb_mar']->nota) && $periodo >= 3) {
                          echo  $complementarios[$materia->id]['feb_mar']->nota;
                        }
                      ?>
                    </td>
                    <td>
                        <?php
                        #la nota Feb_Marzo de la Asignatura
                        if (isset($materia->unificada) && $materia->unificada && sizeof($uni) == 3 && $periodo >= 3) { # Diciembre
                          $comp = array();
                          foreach ($materia->materias as $key => $mat) {
                            if (isset($complementarios[$mat]['feb_mar']->nota)) {
                              $comp['feb_mar'][$key] = $complementarios[$mat]['feb_mar']->nota;
                            }
                          }

                          if (isset($comp['feb_mar']) && sizeof($comp['feb_mar'])) {
                            if (sizeof($materia->materias) == sizeof($comp['feb_mar'])) {
                              $feb = $this->colegio->calculos->redondeo(array_sum($comp['feb_mar'])/sizeof($comp['feb_mar']),$curso);
                              echo $feb < 10 ? truncar_final($feb,2) : $feb;
                            }
                            else {
                              if (isset($comp['feb_mar'][0]) && $comp['feb_mar'][0] >= $materia->apruebaCon) {
                                echo  $comp['feb_mar'][0];
                              }
                              else {
                                echo $comp['feb_mar'][0];
                              }
                            }
                          }
                        }
                        elseif(isset($complementarios[$materia->id]['feb_mar']->nota) && $periodo >= 3) {
                          echo  $complementarios[$materia->id]['feb_mar']->nota;
                        }
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



                </tr>

                <?php }} ?>


            </tbody>
        </table>
    </div>

    <div class="subtotal">
        <div class="acuerdo">
            <table>
                <thead>
                    <tr>
                        <th>Sanciones</th>
                        <th>1º Trim</th>
                        <th>2ª Trim</th>
                        <th>3ª Trim</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                #construir de forma dinamica las sansiones.
                $at_san = array("Observaciones Disciplinarias","Amonestaciones","Suspensiones");

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
        </div>

        <div class="inasistencias">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Justificadas</th>
                        <th>Injustificadas</th>
                        <th>Totales</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                  $at_tipoIna=array("justificadas","injustificadas");
                  $at_periodoNombre=array("1ªtrim","2ªtrim","3ªtrim");

                 for ($c=0; $c < 3; $c++) { 

                  $total=0;
                  echo '<tr>';
                  echo '<td>'.$at_periodoNombre[$c].'</td>';

                  for ($j=0; $j < sizeof($at_tipoIna) ; $j++) { 

                    if (isset($inasistencias[$c][$at_tipoIna[$j]])) {
                     $total+=$inasistencias[$c][$at_tipoIna[$j]];
                     echo '<td>'.$inasistencias[$c][$at_tipoIna[$j]].'</td>';
                    }else{
                      echo '<td>0</td>';
                    }

                  }
                  echo '<td>'.$total.'</td>';
                  echo "</tr>";
                 }
                ?>
                </tbody>
            </table>
        </div>
        <div class="firmas">
            <div class="firm">
                <p style="font-size: 8px">Firma</p>
            </div>
            <div class="firm"></div>
            <div class="firm">
                <p style="font-size: 7px">Firma</p>
                <p style="font-size: 8px;text-align: center;">Padre/Madre/Tutor</p>
            </div>
            <div class="firm"></div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>