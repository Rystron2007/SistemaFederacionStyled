<?php

require_once "Controllers/class-exception-olegiados.php";
require_once "Librerias/Objetos/class-objeto-colegiado.php";

class ColegiadosModel extends Mysql
{
    private $objeto_colegiado;
    private $excepcion_colegiado;

    /**
     * __construct
     * Constructor por defecto
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->excepcion_colegiado = new ErrorsColegiados();
    }

    /**
     * insert_colegiado
     * Funcion que recibe como parametro un Objeto tipo colegiado y procede a insertar un colegiado a la base de datos
     * @param  mixed $objeto_colegiado
     * @return void
     */
    public function insert_colegiado(ObjColegiado $objeto_colegiado)
    {
        try {
            $this->objeto_colegiado = $objeto_colegiado;
            $sql = "SELECT * FROM colegiado WHERE
					id_persona = '{$this->objeto_colegiado->getIdPersona()}'";
            $request = $this->select_all($sql);
            $this->excepcion_colegiado->validar_query_insertar($request);
            $query_insert = "INSERT INTO colegiado(`id_persona`, `codigo_federacion`, `status`)
								  VALUES(?,?,?)";
            $array_data = array($this->objeto_colegiado->get_id_persona(),
                $this->objeto_colegiado->get_cod_federacion(),
                $this->objeto_colegiado->get_status());
            $request_insert = $this->insert($query_insert, $array_data);
            $return = $request_insert;
        } catch (Exception $e) {
            $return = $e->getMessage();
        }
        return $return;
    }

    /**
     * update_colegiado
     * Funcion que recibe como parametro un Objeto tipo colegiado y procede a actualizar un colegiado a la base de datos
     * @param  mixed $objeto_colegiado
     * @return void
     */
    public function update_colegiado(ObjColegiado $objeto_colegiado)
    {
        $this->objeto_colegiado = $objeto_colegiado;
        $sql = "SELECT * FROM colegiado WHERE (id_persona = '{$this->objeto_colegiado->get_id_persona()}' AND id_colegiado != '{$this->objeto_colegiado->get_id_colegiado()}') ";
        $request = $this->select_all($sql);
        try {
            $this->excepcion_colegiado->validar_query_insertar($request);
            $sql = "UPDATE colegiado SET id_persona=?, codigo_federacion=?, status=?
							WHERE id_colegiado = '{$this->objeto_colegiado->get_id_colegiado()}' ";
            $array_data = array($this->objeto_colegiado->get_id_persona(),
                $this->objeto_colegiado->get_cod_federacion(),
                $this->objeto_colegiado->get_status());

            $request = $this->update($sql, $array_data);
        } catch (Exception $e) {
            $request = $e->getMessage();
        }
        return $request;
    }

    /**
     * select_colegiados
     * Funcion que selecciona todos los colegiados no recibe parametros
     * @return void
     */
    public function select_colegiados()
    {
        $sql = "SELECT c.id_colegiado, c.codigo_federacion, c.status, p.idpersona,p.cedula,p.nombres,p.apellidos,p.telefono,p.email_user
					FROM colegiado c
					INNER JOIN persona p
					ON c.id_persona = p.idpersona
					WHERE c.status != 0 ";
        return $this->select_all($sql);
    }

    /**
     * select_colegiado
     * Funcion que permite seleccionar un colegiado recibe como parametro el Id del colegiado a seleccionar
     * @param  mixed $idColegiado
     * @return void
     */
    public function select_colegiado(int $idColegiado)
    {
        $intIdColegiado = $idColegiado;
        $sql = "SELECT c.id_colegiado, c.codigo_federacion, c.status, p.idpersona,p.cedula,p.nombres,p.apellidos,p.telefono,p.email_user,DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro
					FROM colegiado c
					INNER JOIN persona p
					ON c.id_persona = p.idpersona
					WHERE c.id_colegiado = $intIdColegiado";
        return $this->select($sql);
    }

    /**
     * delete_colegiado
     * Funcion que permite eliminar un colegiado recibiendo un ID
     * @param  mixed $idColegiado
     * @return void
     */
    public function delete_colegiado(int $idColegiado)
    {
        $this->idColegiado = $idColegiado;
        $sql = "DELETE from colegiado WHERE id_colegiado = $this->idColegiado";
        return $this->delete($sql);
    }

}

// EOF
