<?php


class Alquiler 
{
    // public $precio;
    // public $foto;
    // public $nombre;
    // public $nacionalidad;
    

    public function crearAlquiler()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO alquiler(nombre_cabana,nombre_usuario,estilo_cabana,cantidad_dias,foto) 
        VALUES (:nombre_cabana,:nombre_usuario,:estilo_cabana,:cantidad_dias,:foto)");
        $consulta->bindValue(':nombre_cabana', $this->nombre_cabana, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_usuario', $this->nombre_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':estilo_cabana', $this->estilo_cabana, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad_dias', $this->cantidad_dias, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre_cabana,nombre_usuario,estilo_cabana,fecha,cantidad_dias,foto FROM alquiler");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Alquiler');
    }

    public static function obtenerCabanasEstilo($estilo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre_cabana,nombre_usuario,estilo_cabana,cantidad_dias,foto FROM alquiler WHERE estilo_cabana = :estilo
        AND fecha BETWEEN :fechaUno AND :FechaDos ");
        $consulta->bindValue(':estilo', $estilo, PDO::PARAM_STR);
        $consulta->bindValue(':fechaUno', '2021-6-10', PDO::PARAM_STR);
        $consulta->bindValue(':FechaDos', '2021-6-13', PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Alquiler');
    }


    public static function obtenerCriptoIdVenta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario,id_cripto,cantidad,foto_venta FROM ventas WHERE id_cripto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerPorNombre($nombre)
    {
        echo 'hola';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            'SELECT mail , nombre_cabana , cantidad_dias , foto
            FROM usuarios U INNER JOIN alquiler A' .' ON  U.mail = A.nombre_usuario');
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }


    // public static function toString($venta){
    //     $cadena .= '<ul>'. '<li>'


    //     return $cadena;
    // }



}
