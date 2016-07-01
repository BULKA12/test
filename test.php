<?php
/**
 * Created by PhpStorm.
 * User: Vladislav Petrenko
 * Date: 23.06.2016
 * Time: 21:29
 */

/**
 * Модель статей, взята с моего сайта.
 */
class Articles
{

    const SHOW_BY_DEFAULT = 6;

    public static function getArticles()
    {
        $db = Db::getConnection();

        $result = $db->query('SELECT * FROM articles ORDER BY articles_id ASC');
        $articlesList = array();
        $i = 0;
        while ($row = $result->fetch()){
            $articlesList[$i]['articles_id'] = $row['articles_id'];
            $articlesList[$i]['title'] = $row['title'];
            $articlesList[$i]['intro_text'] = $row['intro_text'];
            $articlesList[$i]['articles'] = $row['articles'];
            $i++;
        }
        return $articlesList;
    }


    public static function getAllArticlesList($page = 1)
    {
        $page = intval($page);
        $offset = $page * self::SHOW_BY_DEFAULT;
        $db = Db::getConnection();

        $articlesList = array();

        $result = $db->query('SELECT articles_id, title, intro_text FROM articles '
            . 'ORDER BY articles_id DESC '
            . 'LIMIT '.self::SHOW_BY_DEFAULT
            . ' OFFSET '. $offset);

        $i = 0;
        while ($row = $result->fetch())
        {
            $articlesList[$i]['articles_id'] = $row['articles_id'];
            $articlesList[$i]['title'] = $row['title'];
            $articlesList[$i]['intro_text'] = $row['intro_text'];
            $i++;
        }

        return $articlesList;
    }

    public static function getArticlesItemById($id)
    {
        $id = intval($id);

        if ($id)
        {
            $db = Db::getConnection();

            $result = $db->query('SELECT * FROM articles WHERE articles_id=' . $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            return $result->fetch();

        }
    }

    public static function getArticlesListByPage($page = 1)
    {
        $page = intval($page);
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $db = Db::getConnection();
        $articlesList = array();
        $result = $db->query("SELECT * FROM articles "
            . "LIMIT ".self::SHOW_BY_DEFAULT
            . ' OFFSET '. $offset);

        $i = 0;
        while ($row = $result->fetch())
        {
            $articlesList[$i]['articles_id'] = $row['articles_id'];
            $articlesList[$i]['title'] = $row['title'];
            $articlesList[$i]['intro_text'] = $row['intro_text'];
            $i++;
        }

        return $articlesList;
    }

    public static function getTotalArticles($id)
    {
        $db = Db::getConnection();

        $result = $db->query('SELECT * FROM articles  '
            . 'WHERE articles_id ="'.$id.'"');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $row['count'];
    }

    public static function createArticles($option)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO articles '
            . '(title, articles, intro_text)'
            . 'VALUES '
            . '(:title, :articles, :intro_text)';
        $result = $db->prepare($sql);

        $result->bindParam(':title', $option['title'], PDO::PARAM_STR);
        $result->bindParam(':articles', $option['articles'], PDO::PARAM_STR);
        $result->bindParam(':intro_text', $option['intro_text'], PDO::PARAM_STR);

        if($result->execute()){
            return $db->lastInsertId();
        }
        return 0;


    }







}