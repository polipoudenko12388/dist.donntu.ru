<?php

class Authorization
{
    private $conn;
    public $id_user;
    public $id_role;
    public $date_registration;
    public $date_last_visit;


    public function __construct($db) { $this->conn = $db;   }

    // поиск уже сущ. логина у других людей 
    public function verification_login($login)
    {
        $query =  $this->conn->prepare("select * from `dbwebsite_university`.`authorization` where `dbwebsite_university`.`authorization`.`login` = ?");
        $query->execute([$login]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        if ($result) return true;
        else return false;
    }

    // список ролей
    public function printRole()
    {
        $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`role_user`");
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_BOTH);

        return $result; 
    }

    public function verification_email($email)
    {
        $query =  $this->conn->prepare("select * from `dbwebsite_university`.`authorization` where `dbwebsite_university`.`authorization`.`email`=?");
        $query->execute([$email]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        if ($result) return true;
        else return false;
    }

    public function InsertAuthorizationUser($email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit)
    {
        try
        {
            $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`authorization` (`email`, `login`, `password`, `id_user`, `id_role`, `date_registration`, `date_last_visit`,`token`) 
            VALUES (?,?,?,?,?,?,?,?)");
            $query->execute([$email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit, null]);
        }
        catch(PDOException $e) { $query = false; }
    return  $query;
    }


    public function UpdateToken($token, $id_user)
    {
        try
        {
            $query =  $this->conn->prepare("UPDATE `dbwebsite_university`.`authorization` SET `token` = ? WHERE (`id_user` = ?)");
            $query->execute([$token, $id_user]);
        }
        catch(PDOException $e) { $query = false; }
    return  $query;
    }

    // поиск инфы по id_user
    public function print_user($id_user)
    {
        $query =  $this->conn->prepare("select * from `dbwebsite_university`.`authorization` 
        where `dbwebsite_university`.`authorization`.`id_user`= ?");
        $query->execute([$id_user]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        return $result;
    }
    // проверка введенных данных при авторизации
    public function find_user($email_login, $password)
    {
        $queryemail_login =  $this->conn->prepare("select * from `dbwebsite_university`.`authorization` 
        where ( `dbwebsite_university`.`authorization`.`email`= ? or `dbwebsite_university`.`authorization`.`login`= ? )");
        $queryemail_login->execute([$email_login, $email_login]);
        $result = $queryemail_login->fetch(PDO::FETCH_BOTH);

        if ($result) 
        {
            if (strcasecmp($password, $result['password'])==0)
            {
                $this->id_user = $result['id_user']; $this->id_role = $result['id_role'];
                $this->date_registration = $result['date_registration']; $this->date_last_visit = $result['date_last_visit'];
                return true;
            }
            else return false;
        }
        else return false;
    }

}
?>