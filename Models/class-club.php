<?php

require_once "Controllers/exception-club.php";
require_once "Librerias/Objetos/objeto-club.php";

class ClubModel extends Mysql
{
    private $objeto_club;
    private $excepcion_club;

    /**
     * __construct
     * Constructor por defecto
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->excepcion_club = new ErrorsClub();
    }

    /**
     * select_club
     * Permite seleccionar un club
     * @param  mixed $id_club
     * @return void
     */
    public function select_club(int $id_club)
    {
        $id_club = $id_club;
        $sql = "SELECT *
                FROM `club` WHERE id_club = $id_club";
        $request = $this->select($sql);
        return $request;
    }

    /**
     * select_clubs
     * Selecciona todos los clubes
     * @return void
     */
    public function select_clubs()
    {
        $request = $this->select_all("SELECT * FROM club WHERE status !=0");
        return $request;
    }

    /**
     * insert_club
     * Recibe como parametro un Objeto tipo club y procede a insertar un club a la base de datos
     * @param  mixed $objeto_club
     * @return void
     */
    public function insert_club(objeto_club $objeto_club)
    {
        try {
            //Se asigna al objetio club de la clase el club recibido
            $this->objeto_club = $objeto_club;
            $sql = "SELECT * FROM club WHERE
					codigo_club = '{$this->objeto_club->get_codigo_club()}'";
            $request = $this->select_all($sql);
            $this->excepcion_club->validar_query_insertar($request);
            $query_insert = "INSERT INTO club(`codigo_club`, `nombre_club`, `correo_club`,
                                                `asociacion_futbol`, `direccion_club`,
                                                `fecha_fundacion`, `presidente`, `status`)
								  VALUES(?,?,?,?,?,?,?,?)";
            //Ingreso de los datos obtenidos a un arreglo de datos
            $array_data = array($this->objeto_club->get_codigo_club(),
                $this->objeto_club->get_nombre_club(),
                $this->objeto_club->get_correo_club(),
                $this->objeto_club->get_asociacion_futbol(),
                $this->objeto_club->get_direccion_club(),
                $this->objeto_club->get_fecha_fundacion(),
                $this->objeto_club->get_presidente(),
                $this->objeto_club->get_status());
            $request_insert = $this->insert($query_insert, $array_data);
            $return = $request_insert;
        } catch (Exception $e) {
            $return = $e->getMessage();
        }
        return $return;
    }
    /*Funcion que recibe como parametro un Objeto tipo club y procede a actualizar un club a la
    base de datos */

    /**
     * update_colegiado
     *
     * @param  mixed $objeto_club
     * @return void
     */
    public function update_colegiado(objeto_club $objeto_club)
    {
        $this->objeto_club = $objeto_club;
        $sql = "SELECT * FROM club WHERE (id_club = '{$this->objeto_club->get_id_Club()}') ";
        $request = $this->select_all($sql);
        try {
            $this->excepcion_club->validar_query_insertar($request);
            $sql = "UPDATE club SET codigo_club=?, nombre_club=?, correo_club=?, asociacion_futbol=?,
                            direccion_club=?,fecha_fundacion=?,presidente=?,status=?
							WHERE id_club = '{$this->objeto_club->get_id_club()}' ";
            $array_data = array(
                $this->objeto_club->get_codigo_club(),
                $this->objeto_club->get_nombre_club(),
                $this->objeto_club->get_correo_club(),
                $this->objeto_club->get_asociacion_futbol(),
                $this->objeto_club->get_direccion_club(),
                $this->objeto_club->get_fecha_fundacion(),
                $this->objeto_club->get_presidente(),
                $this->objeto_club->get_status());

            $request = $this->update($sql, $array_data);
        } catch (Exception $e) {
            $request = $e->getMessage();
        }
        return $request;
    }

    /**
     * eliminar_club
     * Permite eliminar un club
     * @param  mixed $id_club
     * @return void
     */
    public function eliminar_club(int $id_club)
    {
        $id_club = $id_club;
        $sql = "DELETE FROM `club` WHERE id_club = id_club";

        $request = $this->delete($sql);
        return $request;
    }

}

// EOF
