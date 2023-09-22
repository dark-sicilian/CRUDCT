<?php

$txtId_emp=(isset($_POST['txtId_emp']))?$_POST['txtId_emp']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtApellido=(isset($_POST['txtApellido']))?$_POST['txtApellido']:"";
$txtDireccion=(isset($_POST['txtDireccion']))?$_POST['txtDireccion']:"";
$txtTelefono=(isset($_POST['txtTelefono']))?$_POST['txtTelefono']:"";
$txtId_puesto=(isset($_POST['txtId_puesto']))?$_POST['txtId_puesto']:"";
$txtImagen=(isset($_FILES['txtImagen']["name"]))?$_FILES['txtImagen']["name"]:"";

$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../Conexion/conexion.php");
    switch($accion){
        case "btnAgregar":

            $sentencia = $pdo->prepare("INSERT INTO empleado (Nombre, Apellido, Direccion, Telefono, Id_puesto, Imagen)
            VALUES (:Nombre, :Apellido, :Direccion, :Telefono, :Id_puesto, :Imagen)");


            $sentencia->bindParam(':Nombre',$txtNombre);
            $sentencia->bindParam(':Apellido',$txtApellido);
            $sentencia->bindParam(':Direccion',$txtDireccion);
            $sentencia->bindParam(':Telefono',$txtTelefono);
            $sentencia->bindParam(':Id_puesto',$txtId_puesto);



            $Fecha=New DateTime();
            $nombreArchivo=($txtImagen!="")?$Fecha->getTimestamp()."_".$_FILES["$txtImagen"]["name"]:"imagen.jpg";
        
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){
                move_uploaded_file($tmpImagen,"../Imagenes/".$nombreArchivo);
            }
            $sentencia->bindParam(':Imagen',$nombreArchivo);
            
            $sentencia->execute();

            echo"Presionaste btnAgregar";
        break;
        case "btnModificar":

            $sentencia = $pdo->prepare("UPDATE empleado SET
            Nombre=:Nombre,
            Apellido=:Apellido,
            Direccion=:Direccion,
            Telefono=:Telefono,
            Id_puesto=:Id_puesto WHERE Id_emp=:Id_emp");

            $sentencia->bindParam(':Nombre',$txtNombre);
            $sentencia->bindParam(':Apellido',$txtApellido);
            $sentencia->bindParam(':Direccion',$txtDireccion);
            $sentencia->bindParam(':Telefono',$txtTelefono);
            $sentencia->bindParam(':Id_puesto',$txtId_puesto);
            $sentencia->bindParam(':Id_emp',$txtId_emp);
            
            $sentencia->execute();

            $Fecha=New DateTime();
            $nombreArchivo=($txtImagen!="")?$Fecha->getTimestamp()."_".$_FILES["$txtImagen"]["name"]:"imagen.jpg";
        
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){
                move_uploaded_file($tmpImagen,"../Imagenes/".$nombreArchivo);

                $sentencia = $pdo->prepare("SELECT Imagen FROM empleado WHERE Id_emp=:Id_emp");
            $sentencia->bindParam(':Id_emp',$txtId_emp);
            $sentencia->execute();
            $empleado=$sentencia->fetch(PDO::FETCH_LAZY);
            print_r($empleado);
            if (isset($empleado["Imagen"])){
                if(file_exists(".._/Imagenes/".$empleado["Imagen"])){
                    unlink("../Imagenes/".$empleado["Imagen"]);

                }
            }
        

                $sentencia = $pdo->prepare("UPDATE empleado SET
            Imagen=:Imagen WHERE Id_emp=:Id_emp");
            $sentencia->bindParam(':Imagen',$nombreArchivo);
            $sentencia->bindParam(':Id_emp',$txtId_emp);
            
            $sentencia->execute();
            }

            

            header('Location: index.php');

            echo"Presionaste btnModificar";
        break;
        case "btnEliminar":
            echo"Presionaste btnEliminar";

            $sentencia = $pdo->prepare("SELECT Imagen FROM empleado WHERE Id_emp=:Id_emp");
            $sentencia->bindParam(':Id_emp',$txtId_emp);
            $sentencia->execute();
            $empleado=$sentencia->fetch(PDO::FETCH_LAZY);
            print_r($empleado);
            if (isset($empleado["Imagen"])){
                if(file_exists(".._/Imagenes/".$empleado["Imagen"])){
                    unlink("../Imagenes/".$empleado["Imagen"]);

                }
            }
        
        
            $sentencia = $pdo->prepare("DELETE FROM empleado WHERE Id_emp=:Id_emp");

            $sentencia->bindParam(':Id_emp',$txtId_emp);

            $sentencia->execute();
            header('Location: index.php');

        break;
        case "btnCancelar":
            echo"Presionaste btnCancelar";
        break;
        
    }
    $sentencia = $pdo->prepare("SELECT * FROM empleado WHERE 1");

        $sentencia->execute();
        $listaEmpleado=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        print_r($listaEmpleado);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud w php </title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
   <div class="container">
    <form action="" method="post" enctype="multipart/form-data" >

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Empleado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
        <input type="hidden" required name="txtId_emp" value ="<?php echo$txtId_emp;?>" placeholder="" id="txtId_emp" required="">

        <div class="form-group col-md-4">
        <label for="">Nombre:</label>
        <input type="text" class="form-control" name="txtNombre" required value ="<?php echo$txtNombre;?>" placeholder="" id="txtNombre" required="">
<br>
</div>

        <div class="form-group col-md-4">
        <label for="">Apellido:</label>
        <input type="text" class="form-control" name="txtApellido" required value ="<?php echo$txtApellido;?>" placeholder="" id="txtApellido" required="">
<br>
</div>
        <div class="form-group col-md-12">
        <label for="">Direccion:</label>
        <input type="text" class="form-control" name="txtDireccion" required value ="<?php echo$txtDireccion;?>" placeholder="" id="txtDireccion" required="">
<br>
</div>

        <div class="form-group col-md-4">
        <label for="">Telefono:</label>
        <input type="text" class="form-control" name="txtTelefono" required value ="<?php echo$txtTelefono;?>" placeholder="" id="txtTelefono" required="">
<br>
</div>
        <div class="form-group col-md-4">
        <label for="">Id_puesto:</label>
        <input type="text" class="form-control" name="txtId_puesto" required value ="<?php echo$txtId_puesto;?>" placeholder="" id="txtId_puesto" required="">
<br>
</div>
        <div class="form-group col-md-12">
        <label for="">Imagen:</label>
        <input type="file" class="form-control" accept="image/*" name="txtImagen" value ="<?php echo$txtImagen;?>" placeholder="" id="txtImagen" required="">
<br>
</div>
    </div>
      </div>
      <div class="modal-footer">
      <button value="btnAgregar" class="btn btn-success" type="submit" name="accion">Agregar </button>
        <button value="btnModificar" class="btn btn-warning" type="submit" name="accion">Modificar </button>
        <button value="btnEliminar" class="btn btn-danger"type="submit" name="accion">Eliminar </button>
        <button value="btnCancelar" class="btn btn-primary" type="submit" name="accion">Cancelar </button>

        </div>
        </div>
    </div>
    </div>
        
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Agregar registro + 
</button>

</form>


<div class="row">

    <table class="table table-hover table bordered">
        <thead class="thead-dark">
            <tr>
                <th>Imagen</th>
                <th>Nombre Completo</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>
    <?php foreach($listaEmpleado as $empleado){?>
            <tr>
                <td><img class="img-thumbnail" width="100px" src="../Imagenes<?php echo $empleado ['Imagen'];?>"/><td>
                <td><?php echo $empleado['Nombre'];?> <?php echo $empleado['Apellido'];?></td>
                <td><?php echo $empleado['Telefono'];?></td>
                <td>
                    
                <form action="" method="post">

                <input type="hidden" name="txtId_emp" value="<?php echo $empleado['Id_emp'];?>">
                <input type="hidden" name="txtNombre" value="<?php echo $empleado['Nombre'];?>">
                <input type="hidden" name="txtApellido" value="<?php echo $empleado['Apellido'];?>">
                <input type="hidden" name="txtDireccion" value="<?php echo $empleado['Direccion'];?>">
                <input type="hidden" name="txtTelefono" value="<?php echo $empleado['Telefono'];?>">
                <input type="hidden" name="txtId_puesto" value="<?php echo $empleado['Id_puesto'];?>">
                <input type="hidden" name="txtImagen" value="<?php echo $empleado['Imagen'];?>">


                <input type="submit" value= "Seleccionar" name="accion">
                <button value="btnEliminar" type="submit" name="accion">Eliminar </button>

                </form>
                
    </td>
                
            </tr>

        <?php } ?>
    </table>

    </div>

</div>
</body>
</html>