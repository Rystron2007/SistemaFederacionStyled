<?php

class PermisosModel extends Mysql
{

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
     * select_modulos
     * Seleccionamos todos los modulos
     * @return void
     */
    public function select_modulos()
    {
        $sql = "SELECT * FROM modulo WHERE status != 0";
        return $this->select_all($sql);
    }

    /**
     * select_permisos_rol
     * Seleccionamos los permisos que estan asociados a un determinado rol
     * @param  mixed $id_rol
     * @return void
     */
    public function select_permisos_rol(int $id_rol)
    {
        $sql = "SELECT * FROM permisos WHERE id_rol = '$id_rol'";
        return $this->select_all($sql);
    }

    /**
     * delete_permisos
     * Eliminamos permisos de un determinado usuario
     * @param  mixed $id_rol
     * @return void
     */
    public function delete_permisos(int $id_rol)
    {
        $sql = "DELETE FROM permisos WHERE id_rol = '$id_rol'";
        return $this->delete($sql);
    }

    /**
     * insert_permisos
     * Insertamos permisos recibiendo los permisos por parametros
     * @param  mixed $id_rol
     * @param  mixed $id_modulo
     * @param  mixed $read
     * @param  mixed $write
     * @param  mixed $update
     * @param  mixed $delete
     * @return void
     */
    public function insert_permisos(int $id_rol, int $id_modulo, int $read, int $write, int $update, int $delete)
    {
        $query_insert = "INSERT INTO `permisos`(`id_rol`, `id_modulo`, `read_permiso`, `write_permiso`, `update_permiso`, `delete_permiso`) VALUES (?,?,?,?,?,?)";
        $array_data = array($id_rol, $id_modulo, $read, $write, $update, $delete);
        return $this->insert($query_insert, $array_data);
    }
}

// EOF
