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

    .asigColumn{
        text-align:left;
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
    th{
        color:blue;
    }

    table {
        width: 100%;
    }

    tr {
        height: 15px;
    }


    .tbl_firmas {
        width:100%;
        margin-top: 45px;
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
?>


<body>
    <div class="encabezado">
        <div class="logo">
        <img height="75" src="<?php echo ASSETS_DIR; ?>/images/colegios/<?=$this->colegio->id; ?>.png" alt="<?=$this->colegio->nombre; ?>">
        </div>
        <div class="datos_personales">
            <h1 style="text-align: center; color:blue;"> Informe Final de desempeño del alumno</h1>
            <strong style="text-align: center;"><?php echo $alumno->apellido.", ".$alumno->nombre; ?></strong>
            <span style="text-align: center;">Ciclo Lectivo: <?php echo $inscripto->anio; ?></span>
            <span style="text-align: center;"><?php echo $curso->ano.'º'; ?><?php echo $curso->division; ?> Nivel Primaria </span>
        </div>
        <div>
        <img height="75" src="<?php echo ASSETS_DIR; ?>/images/colegios/2336_apdes.png" alt="<?=$this->colegio->nombre; ?>">
        </div>
    </div>

    <div class="tabla_notas">
        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>1º Periodo</th>
                    <th>2º Periodo</th>
                    <th>N.F:P <br> (Nota Final Ponderada)</th>
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
                    <td><?php echo (isset($final[$materia->id][2]) && $periodo >= 2) ? $final[$materia->id][2] : ''; ?>
                    </td>
                    <td>
                        <?php
                        #La nota Final promediada
		if(isset($final[$materia->id][1]) && isset($final[$materia->id][2]) && $periodo >= 3) {
			echo $acreditacion = truncar_final(array_sum($final[$materia->id])/sizeof($final[$materia->id]),2);
			}
		?>



                </tr>


                <?php }} ?>
            </tbody>

        </table>

    </div>
    <div class="tbl_firmas">
        <div class="firmas">
            <span>_______________________</span>
            <span style="font-size:10px;" >Firma P.E.C.</span>
        </div>
        <div class="firmas">
            <span>________________________</span>
                <span style="font-size:10px;">Firma Director</span>
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