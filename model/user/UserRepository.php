<?php


class UserRepository extends Repository
{

    /*
     * @var $connected boolean
     */
    protected $connected = false;

    const OBJECT_CLASS_NAME = 'model/user/User';




    /**
     * @param string $mail
     * @param string $password
     * @param boolean $encrypt
     * @return User
     */
    public function connect($mail, $password, $encrypt = true){
        if($encrypt){
            $passwordEncrypted = sha1($password.$GLOBALS['salt']);
        }else{
            $passwordEncrypted = $password;
        }

        $connect = $this->db->prepare('SELECT * FROM user WHERE mail = :mail AND password = :password');
        $connect -> bindParam(':password', $passwordEncrypted, PDO::PARAM_STR, strlen($passwordEncrypted));
        $connect -> bindParam(':mail', $mail, PDO::PARAM_STR, strlen($mail));
        $connect -> execute();

        if($userArray = $connect -> fetch(PDO::FETCH_ASSOC)){
            $_SESSION['mail'] = $GLOBALS['mail'];
            $_SESSION['password'] = $GLOBALS['password'];
            $user = new User();
            $user -> createFromResults($userArray);
            $this->user = $user;
            $this->connected = true;
        }

        return $this->user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }



}