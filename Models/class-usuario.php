<?php

require_once "Controllers/class-exception-usuario.php";
require_once "Librerias/Objetos/class-objeto-persona.php";

class UsuariosModel extends Mysql
{
    private $objeto_persona;
    private $excepcion_persona;

    /**
     * __construct
     * Constructor por defecto
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->excepcion_persona = new ErrorsUsuarios();
    }

    /**
     * insert_usuario
     * Funcion que recibe como parametro un Objeto tipo persona y procede a insertar una persona a la
     * base de datos
     * @param  mixed $objeto_persona
     * @return void
     */
    public function insert_usuario(ObjPersona $objeto_persona)
    {
        try {
            $this->objeto_persona = $objeto_persona;
            $sql = "SELECT * FROM persona WHERE
					email_user = '{$this->objeto_persona->get_email()}' or cedula = '{$this->objeto_persona->get_cedula()}' ";
            $request = $this->select_all($sql);
            $this->excepcion_persona->validar_query_insertar($request);
            $query_insert = "INSERT INTO persona(cedula,nombres,apellidos,telefono,email_user,password,id_rol,status)
								  VALUES(?,?,?,?,?,?,?,?)";
            $array_data = array($this->objeto_persona->get_cedula(),
                $this->objeto_persona->get_nombre(),
                $this->objeto_persona->get_apellidos(),
                $this->objeto_persona->get_telefono(),
                $this->objeto_persona->get_email(),
                $this->objeto_persona->get_password(),
                $this->objeto_persona->get_tipo_id(),
                $this->objeto_persona->get_status());
            $request_insert = $this->insert($query_insert, $array_data);
            $return = $request_insert;
        } catch (Exception $e) {
            $return = $e->getMessage();
        }
        return $return;
    }

    /**
     * update_usuario
     * Funcion que recibe como parametro un Objeto tipo persona y procede a actualizar una persona a la
     * base de datos
     * @param  mixed $objeto_persona
     * @return void
     */
    public function update_usuario(ObjPersona $objeto_persona)
    {
        $this->objeto_persona = $objeto_persona;
        $sql = "SELECT * FROM persona WHERE (email_user = '{$this->objeto_persona->get_email()}' AND idpersona != '{$this->objeto_persona->get_id_persona()}')
										  OR (cedula = '{$this->objeto_persona->get_cedula()}' AND idpersona != '{$this->objeto_persona->get_id_persona()}') ";
        $request = $this->select_all($sql);
        try {
            $this->excepcion_persona->validar_query_insertar($request);
            if (empty($this->objeto_persona->get_password())) {
                $sql = "UPDATE persona SET cedula=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, id_rol=?, status=?
							WHERE idpersona = '{$this->objeto_persona->get_id_persona()}' ";
                $array_data = array($this->objeto_persona->get_cedula(),
                    $this->objeto_persona->get_nombre(),
                    $this->objeto_persona->get_apellidos(),
                    $this->objeto_persona->get_telefono(),
                    $this->objeto_persona->get_email(),
                    $this->objeto_persona->get_password(),
                    $this->objeto_persona->get_tipo_id(),
                    $this->objeto_persona->get_status());
            } else {
                $sql = "UPDATE persona SET cedula=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, id_rol=?, status=?
							WHERE idpersona = '{$this->objeto_persona->get_id_persona()}' ";
                $array_data = array($this->objeto_persona->get_cedula(),
                    $this->objeto_persona->get_nombre(),
                    $this->objeto_persona->get_apellidos(),
                    $this->objeto_persona->get_telefono(),
                    $this->objeto_persona->get_email(),
                    $this->objeto_persona->get_password(),
                    $this->objeto_persona->get_tipo_id(),
                    $this->objeto_persona->get_status());
            }
            $request = $this->update($sql, $array_data);
        } catch (Exception $e) {
            $request = $e->getMessage();
        }
        return $request;
    }

    /**
     * select_usuarios
     * Funcion que selecciona todos los usuarios no recibe parametros
     * @return void
     */
    public function select_usuarios()
    {
        $sql = "SELECT p.idpersona,p.cedula,p.nombres,p.apellidos,p.telefono,p.email_user,p.status,r.nombre_rol
					FROM persona p
					INNER JOIN rol r
					ON p.id_rol = r.id_rol
					WHERE p.status != 0 ";
        return $this->select_all($sql);
    }

    /**
     * select_usuario
     * Funcion que permite seleccionar un usuario recibe como parametro el Id del usuario a seleccionar
     * @param  mixed $id_persona
     * @return void
     */
    public function select_usuario(int $id_persona)
    {
        $sql = "SELECT p.idpersona,p.cedula,p.nombres,p.apellidos,p.telefono,p.email_user,r.id_rol,r.nombre_rol,p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro
					FROM persona p
					INNER JOIN rol r
					ON p.id_rol = r.id_rol
					WHERE p.idpersona = '$id_persona'";
        return $this->select($sql);
    }

    /**
     * delete_usuario
     * Funcion que permite eliminar un usuario recibiendo un ID
     * @param  mixed $id_persona
     * @return void
     */
    public function delete_usuario(int $id_persona)
    {
        $sql = "UPDATE persona SET status = ? WHERE idpersona = '$id_persona' ";
        $array_data = array(0);
        return $this->update($sql, $array_data);
    }

}

// EOF
