<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
    * {
        font-size: 12px;
    }

    .ctn-hoja {
        display: flex;
        justify-content: space-around;
    }

    .encabezado1 {
        margin: 0px 1px 0px 1px;
        border: 1px solid black;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        min-width: 400px;
    }

    .elemento {
        margin: 0px 10px 0px 10px;
    }

    .col {
        display: flex;
        flex-direction: column;
    }

    table {
        margin-top: 10px;
        width: 100%;
        border: 1px solid black;
        max-width: 400px;
        border-collapse: collapse;
    }

    tr,
    th {
        border: 1px solid black;
        font-size: 10px;
        text-align: center;
        height: 15px;
        min-height: 20px;
    }

    td {
        border: 1px solid black;
    }

    .asignatura {
        width: 130px;
    }
    </style>
</head>

<body>


    <div class="ctn-hoja">

        <!--Columna 1 calificaciones-->
        <div class="col">
            <div class="encabezado1">
                <div class="elemento">
                    <span>
                        <p><b>School Year:</b> <?=$inscripto->anio; ?></p>
                    </span>
                </div>
                <div class="elemento">
                    <span>
                        <p><b>Year:</b> <?php echo $curso->ano?></p>
                    </span>
                </div>
                <div class="elemento">
                    <span>
                        <p><b>Section :</b> <?php echo $curso->division?></p>
                    </span>
                </div>
            </div>

            <div class="tbl_notas">
                <table>
                    <thead>
                        <tr>
                            <th class="asignatura">SUBJECT</th>
                            <th>1ST<br>REPORT</th>
                            <th>2ND<br>REPORT</th>
                            <th>3RD<br>REPORT</th>
                            <th>Extended<br>Learning<br>period</th>
                            <th>FINAL<br>REPORT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materias as $key => $materia) {
                    if (! in_array($materia->id, $ocultar)) {
                    $uni = array(); # Materias unificadas
                    $acreditacion = 0;
	              ?>
                        <tr>
                            <td><?= $materia->nombre;?></td>
                            <td><?php echo (isset($final[$materia->id][1]) && $periodo >= 1) ? $final[$materia->id][1]:'';?>
                            </td>
                            <td><?php echo (isset($final[$materia->id][2]) && $periodo >= 1) ? $final[$materia->id][2]:'';?>
                            </td>
                            <td><?php echo (isset($final[$materia->id][3]) && $periodo >= 1) ? $final[$materia->id][3]:'';?>
                            </td>
                            <td></td>
                            <td>
                                <?php
                        #La nota Final promediada
				  					if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && isset($final[$materia->id][3]) && $periodo >= 3) {
				  						echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
				  					}
			  				  	?>

                            </td>


                        </tr>

                        <?php }}?>
                    </tbody>

                </table>
            </div>
            <div class="tbl_sub1">
                <table>
                    <tr>
                        <td class="asignatura"><b>DAYS OPEN</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    <tr>
                        <td><b>NON-ATTENDANCE</b></td>

                        <?php
                          $ina1=0;
                          $ina2=0;
                          $ina3=0;
                          $inaT=0;

                          $ina1+=(isset($inasistencias[1]['justificadas']) && $inasistencias[1]['justificadas']) ? $inasistencias[1]['justificadas']: 0;
                          $ina2+=(isset($inasistencias[2]['justificadas']) && $inasistencias[2]['justificadas']) ? $inasistencias[2]['justificadas']: 0;
                          $ina2+=(isset($inasistencias[3]['justificadas']) && $inasistencias[3]['justificadas']) ? $inasistencias[3]['justificadas']: 0;

                          $ina1+=(isset($inasistencias[1]['injustificadas']) && $inasistencias[1]['injustificadas']) ? $inasistencias[1]['injustificadas']: 0;
                          $ina2+=(isset($inasistencias[2]['injustificadas']) && $inasistencias[2]['injustificadas']) ? $inasistencias[2]['injustificadas']: 0;
                          $ina2+=(isset($inasistencias[3]['injustificadas']) && $inasistencias[3]['injustificadas']) ? $inasistencias[3]['injustificadas']: 0;
                          
                          $inaT=$ina1+$ina2+$ina3
                  
                  ?>

                        <td><?php echo $ina1 ?></td>
                        <td><?php echo $ina2 ?></td>
                        <td><?php echo $ina3 ?></td>
                        <td>0</td>
                        <td><?php echo $inaT ?></td>
                    </tr>
                    </tr>
                </table>

            </div>
            <div class="tbl_sub2">
                <table>

                    <tr>
                        <td class="asignatura"><b>TEACHER'S <br>SIGNATURE</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>ENGLISH HEAD'S
                                SIGNATURE</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>STUDENT'S
                                SIGNATURE</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>FATHER'S/MOTHER'S/
                                TUTOR'S SIGNATURE</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

            </div>

            <div class="encabezado2">
                <div class="elemento">
                    <span>
                        <p><b>STUDENT'S NAME:</b><?php echo $alumno->apellido.", ".$alumno->nombre;?></p>
                    </span>
                </div>
                <div class="elemento">
                    <span>
                        <p><b>ENGLISH HEAD'S SIGNATURE:</b><?php echo $docente[0]->nombre.' '.$docente[0]->apellido;?>
                        </p>
                    </span>
                </div>
            </div>
        </div>

        <!--Columna 2 comentarios-->

        <div class="col2">
            <div class="encabezado1">
                <div class="elemento">
                    <span>
                        <p><b>COMMENTS</b></p>
                    </span>
                </div>
                <div class="elemento">
                    <span>
                        <p><b>Year:</b> <?php echo $curso->ano?></p>
                    </span>
                </div>
                <div class="elemento">
                    <span>
                        <p><b></b> <?=$inscripto->anio; ?></p>
                    </span>
                </div>
            </div>

            <div class="tbl_comments">
                <table>
                    <thead>
                        <tr>
                            <th>1ST REPORT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p>
                                    You show yourself as an active student and interested to learn. Continue working to
                                    improve your writing skills,
                                    prepare with more patience and time for exams and resort to your folder to check and
                                    study new topics. Keep
                                    it up
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>2ND REPORT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p>
                                    You show yourself as an active student and interested to learn. Continue working to
                                    improve your writing skills,
                                    prepare with more patience and time for exams and resort to your folder to check and
                                    study new topics. Keep
                                    it up
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>3RD REPORT </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p>
                                    You show yourself as an active student and interested to learn. Continue working to
                                    improve your writing skills,
                                    prepare with more patience and time for exams and resort to your folder to check and
                                    study new topics. Keep
                                    it up
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="encabezado2">

                <div class="elemento">
                    <span>
                        <p><b>PROMOTED TO :</b> </p>
                    </span>
                </div>
            </div>

        </div>
    </div>
    <div style="page-break-after: always;"></div>
</body>

</html>