<?php
/**
 * Created by PhpStorm.
 * User: Vladislav Petrenko
 * Date: 25.06.2016
 * Time: 14:58
 */

/**
 * Модель юзер моего сайта
 */
class User
{

    public static function register($login, $email, $password)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO users (login, email, password) '
            . 'VALUES (:login, :email, :password)';

        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);

        return $result->execute();

    }

    public static function checkName($login)
    {
        if (strlen($login) >= 2){
            return true;
        }
        return false;
    }

    public static function checkPassword($password)
    {
        if (strlen($password) >= 6){
            return true;
        }
        return false;
    }

    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) ){
            return true;
        }
        return false;
    }

    public static function checkEmailExists($email)
    {
        $db = Db::getConnection();

        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if($result->fetchColumn())
            return true;
        return false;


    }

    public static function checkUserDate($email, $password)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch();
        if($user){
            return $user['id'];
        }

        return false;
    }

    public static function auth($userId)
    {

        $_SESSION['users'] = $userId;
    }

    public static function checkLogged()
    {

        if(isset($_SESSION['users'])){
            return $_SESSION['users'];
        }

        header("Location: /user/login");
    }

    public static function isGuest()
    {

        if(isset($_SESSION['users'])){
            return false;
        }
        return true;
    }

    public static function getUserById($id)
    {
        if($id){
            $db = Db::getConnection();
            $sql = 'SELECT * FROM users WHERE id = :id';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);

            //Указываем что хотим получить в виде массива
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();

            return $result->fetch();

        }
    }

    public static function edit($id, $name, $password)
    {
        $db = Db::getConnection();

        $sql = "UPDATE users
            SET login = :login, password = :password
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':login', $name, PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        return $result->execute();
    }


}