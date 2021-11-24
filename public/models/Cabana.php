<?php


class Cabana 
{
    public $precio;
    public $foto;
    public $nombre;
    public $estilo;
    public $cantidad_habitaciones;
    public $cantidad_personas;
    

    public function crearCabana()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cabana (precio,foto,nombre,estilo,cantidad_habitaciones,cantidad_personas) 
        VALUES (:precio,:foto,:nombre,:estilo,:cantidad_habitaciones,:cantidad_personas)");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estilo', $this->estilo, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad_habitaciones', $this->cantidad_habitaciones, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad_personas', $this->cantidad_personas, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT precio,foto,nombre,estilo,cantidad_habitaciones,cantidad_personas FROM cabana");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cabana');
    }


    public static function obtenerCabanaId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT precio,foto,nombre,estilo,cantidad_habitaciones,cantidad_personas FROM cabana WHERE id_cabana = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cabana');
    }

        public static function borrarCabana($cabana)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM cabana WHERE nombre = :cabana");
        $consulta->bindValue(':cabana', $cabana, PDO::PARAM_STR);
        $consulta->execute();
    }

    public  function modificarCabana()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cabana SET precio = :precio, foto = :foto WHERE nombre = :nombre");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        // $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
    }

}
