<?php
$Transacciones =[["id"=>1,"descripcion"=>"Pago telefono","monto"=>20.5],
                 ["id"=>2,"descripcion"=>"Pago tarjeta","monto"=>20.10],
                 ["id"=>3,"descripcion"=>"Pago super","monto"=>30.10],] ;


function registrarTransacciones($id,$descripcion,$monto){
    global $Transacciones;
    array_push($Transacciones,["id"=>$id,"descripcion"=>$descripcion,"monto"=>$monto]);
}           
   
function generarEstadoDeCuenta(){
   global $Transacciones;
   $total = 0;
   for ($i= 0; $i<count($Transacciones); $i++)
   {
    $total += $Transacciones[$i]["monto"];
   }

    $interes = 0.026;
    $totalConInteres = $total * (1 + $interes);
    $cashback = $total * 0.001;

   
    $montoFinal = $totalConInteres - $cashback;
     
    echo"<h2>Detalles de transaciones</h2>";
    for ($i= 0; $i<count($Transacciones);$i++){
        $montoConInteres = $Transacciones[$i]["monto"] * (1 + $interes);
        echo"Descripcion: ".$Transacciones[$i]["descripcion"]." Monto:".$Transacciones[$i]["monto"]." Monto con intereses: ".number_format($montoConInteres,2)."<br>";
    };

    echo "<h2>Resumen del Estado de Cuenta</h2>";
    echo "<p>Monto Total de Contado: $" . number_format($total, 2) . "</p>";
    echo "<p>Monto Total con Intereses (2.6%): $" . number_format($totalConInteres, 2) . "</p>";
    echo "<p>Cashback (0.1%): $" . number_format($cashback, 2) . "</p>";
    echo "<p>Monto Final a Pagar: $" . number_format($montoFinal, 2) . "</p>";

}

registrarTransacciones(4,"pago gym",50.5);
registrarTransacciones(5,"Pago comida",20.5);
registrarTransacciones(6,"Pago U",120.5);
registrarTransacciones(7,"Pago luz",20.5);
registrarTransacciones(8,"Pago agua",20.5);
registrarTransacciones(9,"Pago internet",20.5);
registrarTransacciones(10,"Pago cable",20.5);
registrarTransacciones(11,"Pago telefono",20.5);
generarEstadoDeCuenta();
?>
