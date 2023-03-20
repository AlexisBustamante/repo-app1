<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>
<style>
* {
    font-family: system-ui;
    box-sizing: border-box;
}

.encabezado {
    display: flex;
    justify-content: space-between;
}

.logo {
    width: 120px;
    height: 60px;
    background-color: aquamarine;
}

.datosColegio {
    text-align: center;
    display: flex;
    flex-direction: column;
    margin-right: 15px;
    padding: 2px;
    margin: 0 0 2px 0;
}

.dir {
    font-size: 9px;
    margin: 0 0 2px 0;
}

.linea {
    width: 100%;
    background-color: rgba(155, 3, 3, 0.603);
    height: 3px;
}

.datosColegio {
    text-align: center;
    display: flex;
    margin-right: 15px;
    padding: 2px;
}

.hoja {
    display: flex;
    flex-direction: column;
}

.info {
    text-align: center;
}

p {
    font-size: small;
}

h3 {
    margin: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    border: 1.5px solid black;
}

th {
    border: 1px solid black;
    font-size: 11px;
}

td {
    border: 1px solid black;
    padding: 2px;
    text-align: center;
    font-size: 12px;
}

.datosAlumno {
    margin-bottom: 5px;
}

.asig {
    width: 50%;
}

.asigItera {
    text-align: left;
}

.titlesub {
    padding-left: 40px;
    height: 25px;
}

.textolibre {
    font-size: 9px;
}

.firmas {
    margin-top: 5px;
}

.firma {
    display: flex;
    flex-direction: column;
    margin-top: 10px;
}
</style>
<?php 
// var_dump($this);
?>

<body>
    <div class="hoja">
        <div class="encabezado">
            <div class="">
                <img height="75" src="<?php echo ASSETS_DIR; ?>/images/colegios/<?=$this->colegio->id; ?>.png"
                    alt="<?=$this->colegio->nombre; ?>">

            </div>
            <div class="datosColegio">
                <b><?php echo $this->colegio->nombre;?></b>
                <span class="dir"><?php echo $this->colegio->direccion;?></span>
                <div class="linea"></div>
                <div class="datosColegio2">
                    <span class="dir">Tel:<?php echo $this->colegio->telefono;?>-</span>
                    <span class="dir">cel: 0986 350 660 -</span>
                    <span class="dir">web:www.cerritos.edu.py</span>
                </div>
            </div>
        </div>
        <div class="info">
            <p>
                Y Jesús crecía en sabiduría y en estatura,<br />
                y en gracia para con Dios y los hombres. Lucas 2:52.
            </p>
            <h3>Boletín de Calificaciones</h3>
            <h3>Educación Escolar Básica</h3>
        </div>

        <div class="datosAlumno">
            <table>
                <tbody>
                    <tr>
                        <td>Apelldios: <br />Nombres:</td>
                        <td colspan="5"><?php echo $alumno->apellido."<br>".$alumno->nombre;?></td>
                    </tr>
                    <tr>
                        <td>Grado</td>
                        <td><?php echo $curso->division;?></td>
                        <td>Ciclo:</td>
                        <td><?php echo $curso->ano."º";?></td>
                        <td>Año</td>
                        <td><?php echo $inscripto->anio;?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tbl_notas">
            <table>
                <thead>
                    <tr>
                        <th class="asig" rowspan="4">Àreas</th>
                        <th colspan="4">Periodos</th>
                    </tr>
                    <tr>
                        <th colspan="3">Ordinario</th>
                        <th colspan="3">Compl.</th>
                    </tr>
                    <tr>
                        <th>1a. <br />Etapa</th>
                        <th>2a. <br />Etapa</th>
                        <th rowspan="2">Calif. <br />Final</th>
                        <th rowspan="2">Calif. <br />Final</th>
                    </tr>
                    <tr>
                        <th>1a. <br />Etapa</th>
                        <th>1a. <br />Etapa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
					
					$arraySum1=[];
					$arraySum2=[];
					$arraySum3=[];
					$arraySum4=[];

					foreach ($materias as $key => $materia) {
                    if (! in_array($materia->id, $ocultar)) {
                    $uni = array(); # Materias unificadas
                    $acreditacion = 0;

					if (isset($final[$materia->id][1])) {
						# code...
						$arraySum1[]=(int)$final[$materia->id][1];
					}
					if (isset($final[$materia->id][2])) {
						# code...
						$arraySum2[]=(int)$final[$materia->id][2];
					}
			
	              ?>

                    <tr>
                        <td class="asigItera"><?php echo $materia->nombre ?></td>
                        <td><?php echo (isset($final[$materia->id][1]) && $periodo >= (sizeof($ciclo)-1)) ? $final[$materia->id][1]:'';
						?>
                        </td>
                        <td><?php echo (isset($final[$materia->id][2]) && $periodo >= (sizeof($ciclo)-1)) ? $final[$materia->id][2]:'';?>
                        </td>
                        <td>

                            <?php
                        #La nota Final promediada
				  					if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
				  						echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);				
										$arraySum3[]=(int)$acreditacion;
				  					}
			  				  	?>
                        </td>
                        <td>
                            <?php 
							echo ($periodo=(sizeof($ciclo)-1)) && (isset($finalCiclo[$materia->id]->nota))   ? $finalCiclo[$materia->id]->nota :'';	
							
							if (isset($finalCiclo[$materia->id]->nota)) {
							$arraySum4[]=(int)$finalCiclo[$materia->id]->nota;
							}
							
							?>
                        </td>
                    </tr>
                    <?php 
					}} 
				  ?>
                    <tr>
                        <td class="asigItera titlesub"><b>Total Puntos</b></td>
                        <td> <?php echo array_sum($arraySum1)>0 ? array_sum($arraySum1) :'';?> </td>
                        <td><?php echo array_sum($arraySum2)>0 ? array_sum($arraySum2) :'';?></td>
                        <td><?php echo array_sum($arraySum3)>0 ? array_sum($arraySum3) :'';?></td>
                        <td><?php echo array_sum($arraySum4)>0 ? array_sum($arraySum4) :'';?></td>
                    </tr>

                    <tr>
                        <td class="asigItera titlesub" colspan="4">
                            <b>Término Medio General</b>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td class="asigItera titlesub" colspan="4">
                            <b>Pasa de Grado</b>
                        </td>
                        <td>
                            <?php 
				  				$prom=truncar_final(array_sum($arraySum4)/sizeof($arraySum4),2);				
								if($prom>6){
									echo 'SI';
								}else {
									echo 'NO';
								}
							
							?>
                        </td>
                    </tr>
                    <tr>
                        <td class="asigItera titlesub" colspan="5">
                            <b>INFORME DESCRIPTIVO ­ PRIMERA ETAPA</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textolibre">
                            Ha tenido buen rendimiento académico en esta etapa. Con más
                            esfuerzo y dedicación obtendrá mejores resultados. Es muy capaz
                        </td>
                    </tr>
                    <td class="asigItera titlesub" colspan="5">
                        <b>INFORME DESCRIPTIVO ­ SEGUNDA ETAPA</b>
                    </td>
                    <tr>
                        <td colspan="5" class="textolibre">
                            Ha logrado buen rendimiento en esta etapa. El Señor te bendiga y
                            te guarde; te mire con agrado y te extienda su amor; te muestre
                            su favor y te conceda la paz”. Números 6:24­26. Felices
                            Vacaciones!
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="firmas">
            <table>
                <thead>
                    <tr>
                        <th>
                            <div class="firma">
                                <span>Francisco Ramón Giménez Britez</span>
                                <span>_________________________________________</span>
                                <p>Profesor Guía</p>
                            </div>
                        </th>
                        <th>
                            <div class="firma">
                                <span>Juan Rodrigo Godoy Gutierrez</span>
                                <span>_________________________________________</span>
                                <p>Director Académico</p>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

</body>

</html>