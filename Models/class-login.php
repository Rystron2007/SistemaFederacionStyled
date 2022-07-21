<?php

class LoginModel extends Mysql
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
     * login_user
     * Funcion que recibe el usuario y contraseña
     * @param  mixed $usuario
     * @param  mixed $password
     * @return void
     */
    public function login_user(string $usuario, string $password)
    {
        $sql = "SELECT idpersona,status FROM persona WHERE
					email_user = '$usuario' and
					password = '$password' and
					status != 0 ";
        return $this->select($sql);
    }

    /**
     * session_login
     * Funcion que permite identificar el role para la secciones recibe un id user
     * @param  mixed $iduser
     * @return void
     */
    public function session_login(int $iduser)
    {

        //BUSCAR ROLE
        $sql = "SELECT p.idpersona,
							p.cedula,
							p.nombres,
							p.apellidos,
							p.telefono,
							p.email_user,
							r.id_rol,r.nombre_rol,
							p.status
					FROM persona p
					INNER JOIN rol r
					ON p.id_rol = r.id_rol
					WHERE p.idpersona = '$id_usrer'";
        $request = $this->select($sql);
        return $request;
    }

    /**
     * get_user_email
     * fFunciona que genera los datos de un usuario por medio del email recibiendolo como parametro
     * @param  mixed $string_email
     * @return void
     */
    public function get_user_email(string $string_email)
    {
        $sql = "SELECT idpersona,nombres,apellidos,status FROM persona WHERE
					email_user = '$string_email' and
					status = 1 ";
        return $this->select($sql);
    }

    /**
     * set_token_user
     * Funcion que permite asignar un token para el reseteo de la contraseña en caso de olvido o perdida
     * @param  mixed $id_persona
     * @param  mixed $token
     * @return void
     */
    public function set_token_user(int $id_persona, string $token)
    {
        $sql = "UPDATE persona SET token = ? WHERE idpersona = '$id_persona' ";
        $array_data = array($token);
        return $this->update($sql, $array_data);
    }

    /**
     * get_usuario
     * Funcion que recupera un usuario por email y token al momento de resetear
     * @param  mixed $email
     * @param  mixed $token
     * @return void
     */
    public function get_usuario(string $email, string $token)
    {
        $sql = "SELECT idpersona FROM persona WHERE
					email_user = '$email' and
					token = '$token' and
					status = 1 ";
        return $this->select($sql);
    }

    /**
     * insert_password
     * Funcion que permite insertar una contraseña al momento de resetear
     * @param  mixed $id_persona
     * @param  mixed $password
     * @return void
     */
    public function insert_password(int $id_persona, string $password)
    {
        $sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = '$id_persona' ";
        $array_data = array($password, "");
        return $this->update($sql, $array_data);
    }
}

// EOF
