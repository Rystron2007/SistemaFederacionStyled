<?php

require_once "Controllers/class-exceptiion-rol.php";
require_once "Librerias/Objetos/class-objeto-roles.php";

class RolesModel extends Mysql
{
    private $objeto_rol;

    /**
     * __construct
     * Constructor por defecto
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * select_roles
     * Metodo para consultar todos los roles
     * @return void
     */
    public function select_roles()
    {
        //creacion del query para solicitar los datos
        $sql = "SELECT * FROM rol WHERE status !=0";
        //Llamado al metodo select_all donde se ejecuta la consulta a la base de datos
        //en la clase Mysql
        $request = $this->select_all($sql);
        return $request;
    }

    /**
     * select_rol
     * Metodo para consultar un rol
     * @param  mixed $id_rol
     * @return void
     */
    public function select_rol(int $id_rol)
    {
        $sql = "SELECT * FROM rol WHERE id_rol = '$id_rol'";
        return $this->select($sql);
    }

    /**
     * insert_rol
     *  Metodo para insertar un rol individual
     * @param  mixed $objeto_rol
     * @return void
     */
    public function insert_rol(ObjRoles $objeto_rol)
    {
        $this->objeto_rol = $objeto_rol;
        $return = "";
        //creacion del query para validar si el rol existe o no
        $sql = "SELECT * FROM rol WHERE nombre_rol = '{$this->objeto_rol->get_rol()}'";
        $request = $this->select_all($sql);
        //Si el rol no existe la variable request estara vacia
        if (empty($request)) {
            //Creacion del query para ingresar los datos
            $query_insert = "INSERT INTO rol(nombre_rol,descripcion_rol,status) VALUES (?,?,?)";
            //Asignacion de los datos al array para su ingreso
            $array_data = array($this->objeto_rol->get_rol(),
                $this->objeto_rol->get_descripcion(),
                $this->objeto_rol->get_status());
            //Llamado al metodo insert enviando el query y los datos
            $request_insert = $this->insert($query_insert, $array_data);
            //se regrese al valor obtenido por el insert
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    /**
     * update_rol
     * Actualiza un rol
     * @param  mixed $objeto_rol
     * @return void
     */
    public function update_rol(ObjRoles $objeto_rol)
    {
        $this->objeto_rol = $objeto_rol;
        $sql = "SELECT * FROM rol WHERE nombre_rol = '{$this->objeto_rol->get_rol()}' AND id_rol != '{$this->objeto_rol->get_id_rol()}'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE rol SET nombre_rol = ?, descripcion_rol = ?, status = ? WHERE id_rol = '{$this->objeto_rol->get_id_rol()}'";
            $array_data = array($this->objeto_rol->get_rol(),
                $this->objeto_rol->get_descripcion(),
                $this->objeto_rol->get_status());
            $request = $this->update($sql, $array_data);
        } else {
            $request = "exist";
        }
        return $request;
    }

    /**
     * delete_rol
     * Funcion para eliminar Roles
     * @param  mixed $id_rol
     * @return void
     */
    public function delete_rol(int $id_rol)
    {
        $request = $this->validar_rol_del($id_rol);
        if (empty($request)) {
            $sql = "UPDATE rol SET status = ? WHERE id_rol = '$id_rol'";
            $array_data = array(0);
            $request_delete = $this->update($sql, $array_data);
            $request = $this->respuesta_del($request_delete);
        } else {
            $request = 'exist';
        }
        return $request;
    }

    /**
     * respuesta_del
     * Funcion que valida la respuesta de una eliminacion
     * @param  mixed $request
     * @return void
     */
    public function respuesta_del($request)
    {
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    /**
     * validar_rol_del
     * Funcion que valida la eliminación de un rol
     * @param  mixed $id_rol
     * @return void
     */
    public function validar_rol_del(int $id_rol)
    {
        $sql = "SELECT * FROM persona WHERE id_rol = '$id_rol'";
        return $this->select_all($sql);
    }
}

// EOF
