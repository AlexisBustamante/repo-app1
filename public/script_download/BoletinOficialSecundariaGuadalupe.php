<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
</head>
<title>Boletin</title>

<style>
body {
    font-size: 12px;

}

span {
    padding: 5px;
}

* {
    font-family: system-ui;
    padding: 0px;
    box-sizing: border-box;
}

.encabezado {
    display: flex;
    border: 1px solid black;
    padding: 5px;
    margin: 0px 5px 5px 5px;
}

.logo {
    width: 75px;
    height: 75px;
}

.datos_institucion {
    display: flex;
    flex-direction: column;
}

.titulo {
    margin: 0px 5px 0px 5px;
}

.titulo2 {
    margin: 0px 5px 0px 5px;
    display: flex;
    justify-content: space-between;
}

.titulo3 {
    display: flex;
    justify-content: space-between;
    margin: 0px 5px 0px 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    border: 1.5px solid black;
    letter-spacing: 1px;
    font-size: 0.8rem;
}

td,
th {
    border: 1px solid black;
    padding: 4px 8px;
    height: 20px;
    text-align: center;
}

.subtotal {
    margin-top: 5px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
}

.asigColumn {
    text-align: left;
}

.firmas {
    padding: 5px;
    border: 1px solid black;
    display: flex;
    justify-content: space-between;
    text-align: center;
    font-size: 12px;
}
img{
  height:75px;
  width:75px;
}
</style>
</head>

<body>
    <div class="hoja">
        <div class="encabezado">
            <div class="logo">
                <img src="<?php echo ASSETS_DIR; ?>/images/colegios/unc.png" alt="<?=$this->colegio->nombre; ?>">
            </div>

            <div class="datos_institucion">
                <div class="titulo">
                    <h2>INSTITUTO PRIVADO "GUADALUPE" (A-17)</h2>
                </div>
                <div class="titulo2">
                    <span>Boletín de calificaciones N°:</span>
                    <span>Ciclo Lectivo : <?=$inscripto->anio; ?></span>
                </div>
                <div class="titulo3">
                    <span>Alumno: <strong><?php echo $alumno->apellido.", ".$alumno->nombre; ?></strong></span>
                    <span>Curso: <strong><?php echo $curso->ano."º ".strtoupper($curso->division); ?></strong></span>
                </div>
            </div>
        </div>

        <div class="tbl_notas">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">Asignaturas</th>
                        <th colspan="3">Trimestre</th>
                        <th rowspan="2">Eval <br />Final</th>
                        <th colspan="2">Calificaciones</th>
                        <th rowspan="2">Calif. <br />Def</th>
                        <th rowspan="2">Condición</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>DIC</th>
                        <th>FEB</th>
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
                        <td>
                            <?php
                        #PF3
									if(isset($final[$materia->id][1]) && $periodo >= 1) {
									  	echo $final[$materia->id][1];
									}
								 ?>
                        </td>
                        <td>
                            <?php
                        #PF2
									if(isset($final[$materia->id][2]) && $periodo >= 2) {
										echo $final[$materia->id][2];
									}
								?>
                        </td>
                        <td>
                            <?php
                        #PF3
									if(isset($final[$materia->id][3]) && $periodo >= 3) {
										echo $final[$materia->id][3];
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
                        #La nota Final Promedida
				  					if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
				  						echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
				  					}
			  				  	?>
                        <td></td>
                    </tr>


                    <?php }
				} ?>

                </tbody>
            </table>
        </div>

        <div class="subtotal">

            <div class="inasistencias">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Trim 1</th>
                            <th>Trim 2</th>
                            <th>Trim 3</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Inasistencias</td>
                            <?php
                            $inas1 = 0;
                            $inas2 = 0;
                            $inas3 = 0;

                          if(isset($inasistencias[1]['justificadas']) && $inasistencias[1]['justificadas']) 
                          $inas1 += $inasistencias[1]['justificadas']; 

                          if(isset($inasistencias[1]['injustificadas']) && $inasistencias[1]['injustificadas']) 
                          $inas1 += $inasistencias[1]['injustificadas']; 
                            
                          if(isset($inasistencias[2]['justificadas']) && $inasistencias[2]['justificadas']) 
                          $inas2 += $inasistencias[2]['justificadas']; 

                          if(isset($inasistencias[2]['injustificadas']) && $inasistencias[2]['injustificadas']) 
                          $inas2 += $inasistencias[2]['injustificadas']; 

                          if(isset($inasistencias[3]['justificadas']) && $inasistencias[3]['justificadas']) 
                          $inas3 += $inasistencias[3]['justificadas']; 

                          if(isset($inasistencias[3]['injustificadas']) && $inasistencias[3]['injustificadas']) 
                          $inas3 += $inasistencias[3]['injustificadas']; 
                          ?>

                            <td><?php echo $inas1?></td>
                            <td><?php echo $inas2?></td>
                            <td><?php echo $inas3?></td>
                            <td>
                                <?php 
                              if ($inas1>=0 && $inas2>=0 && $inas3>=0) {
                                if ( ($inas1+$inas2+$inas3)>0) {
                                     echo ($inas1+$inas2+$inas3);
                                }
                            }
                              ?>
                            </td>

                        </tr>
                        <tr>
                            <td>Sanciones</td>

                            <?php
                            $san1=0;
                            $san2=0;
                            $san3=0;

                            #periodo1
                             if(isset($sanciones[1]['amonestaciones']) && $sanciones[1]['amonestaciones'] ) {$san1+=$sanciones[1]['amonestaciones'] ;} 
                             if(isset($sanciones[1]['suspensiones']) && $sanciones[1]['suspensiones'] ){  $san1+=$sanciones[1]['suspensiones'] ;}
                             if(isset($sanciones[1]['apercibimientos']) && $sanciones[1]['apercibimientos'] ) { $san1+=$sanciones[1]['apercibimientos'] ;}

                             #periodo2
                             if(isset($sanciones[2]['amonestaciones']) && $sanciones[2]['amonestaciones'] ) {$san2+=$sanciones[2]['amonestaciones'] ;} 
                             if(isset($sanciones[2]['suspensiones']) && $sanciones[2]['suspensiones'] ){  $san2+=$sanciones[2]['suspensiones'] ;}
                             if(isset($sanciones[2]['apercibimientos']) && $sanciones[2]['apercibimientos'] ) { $san2+=$sanciones[2]['apercibimientos'] ;}
                            
                                #periodo3
                              if(isset($sanciones[3]['amonestaciones']) && $sanciones[3]['amonestaciones'] ) {$san3+=$sanciones[3]['amonestaciones'] ;} 
                              if(isset($sanciones[3]['suspensiones']) && $sanciones[3]['suspensiones'] ){  $san3+=$sanciones[3]['suspensiones'] ;} 
                              if(isset($sanciones[3]['apercibimientos']) && $sanciones[3]['apercibimientos'] ) { $san3+=$sanciones[3]['apercibimientos'] ;}
                              $sanT=0;
                              $sanT = $san1+$san2+$san3;
                            ?>
                          


                            <td><?php echo $san1 ?></td>
                            <td><?php echo $san2 ?></td>
                            <td><?php echo $san3 ?></td>
                            <td><?php echo $sanT ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pendientes">
                <table>
                    <thead>
                        <tr>
                            <th>Asignaturas Pendientes</th>
                            <th>Año</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($previas as $key => $previa) { ?>
                        <tr>
                            <td><?php $previa->materia->nombre ?></td>
                            <td><?php $previa->materia->ano?></td>
                        </tr>

                        <?php   } ?>
                    </tbody>
                </table>
            </div>

        </div>



        <div class="firmas">
            <div>
                <p></p>
                <p>__________________________________</p>
                <p></p>
                <p>Firma del Alumno</p>

            </div>
            <div>
                <p></p>
                <p>__________________________________</p>
                <p></p>
                <p><b>Firma de Padre / Madre / Tutor</b> </p>

            </div>
            <div>
                <p></p>
                <p>__________________________________</p>
                <p></p>
                <p> <b>RECTOR: Prof. Alejandro Pomar</b> </p>
            </div>
        </div>

    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>