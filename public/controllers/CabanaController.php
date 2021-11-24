<?php

require_once './models/Cabana.php';
require_once './interfaces/IApiUsable.php';
use Psr\Http\Message\UploadedFileInterface;

class CabanaController extends Cabana implements IApiUsable
{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $precio = $parametros['precio'];
        $archivos = $request->getUploadedFiles();
        $nombre = $parametros['nombre'];
        $estilo = $parametros['estilo'];
        $cantidad_personas = $parametros['cantidad_personas'];
        $cantidad_habitaciones = $parametros['cantidad_habitaciones'];
        $extension= explode(".", $archivos['foto']->getClientFilename());
          
        // var_dump($archivos);
        if ($archivos['foto']->getError() == UPLOAD_ERR_OK) {
            $destino="./fotos/";
            $extension= explode(".", $archivos['foto']->getClientFilename());
            $destino .= $nombre . '/';
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            // $archivos['foto']->getClientFilename()
             $archivos['foto']->moveTo($destino . $archivos['foto']->getClientFilename());
             $foto = $archivos['foto']->getClientFilename();
            
        }
        $cripto = new Cabana();
        $cripto->precio = $precio;
        $cripto->foto = $foto;
        $cripto->nombre = $nombre;
        $cripto->estilo = $estilo;
        $cripto->cantidad_personas = $cantidad_personas;
        $cripto->cantidad_habitaciones = $cantidad_habitaciones;
        $cripto->crearCabana();

        var_dump($cripto);

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // // Buscamos usuario por nombre
        // $nacionalidad = $args['nacionalidad'];
        // // var_dump($nacionalidad);
        // $nac = Cabana::obtenerCriptoNacionalidad($nacionalidad);
        // $payload = json_encode($nac);

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Cabana::obtenerTodos();
        $payload = json_encode(array("LISTA CABANIAS" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        parse_str(file_get_contents('php://input'), $parametros);

        $nombre = $args['cabana'];
        $precio = $parametros['precio'];
        // $foto = $request->getUploadedFiles();
        $estilo = $parametros['estilo'];
        $foto = $parametros['foto'];
        $cantidad_habitaciones = $parametros['cantidad_habitaciones'];
        $cantidad_personas = $parametros['cantidad_personas'];

        $cripto = new Cabana();
        $cripto->nombre = $nombre;
        $cripto->precio = $precio;
        $cripto->foto = $foto;
        $cripto->estilo = $estilo;
        $cripto->cantidad_habitaciones = $cantidad_habitaciones;
        $cripto->cantidad_personas = $cantidad_personas;

        $arrCriptos =  Cabana::obtenerTodos();

        foreach ($arrCriptos as  $moneda) {
            if ($cripto->foto == $moneda->foto ) {
                $destino="./Backup/";
                $extension= explode(".", $moneda->foto);
                $destino .= $nombre . '/';
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                $origen = './fotos/' . strtolower($nombre) . '/' . $moneda->foto ;
                // $archivos['foto']->getClientFilename()
                // $moneda->foto->moveTo($destino . $moneda->foto);
                copy($origen,$destino. $cripto->foto);
                break;        
            }
        }

        $cripto->modificarCabana();
        

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $cabana = $args['cabana'];

        Cabana::borrarCabana($cabana);

        $payload = json_encode(array("mensaje" => "Criptomneda borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }






}
