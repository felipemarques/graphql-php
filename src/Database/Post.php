<?php

namespace App\GraphQL\Database;

use App\GraphQL\Connection;

class Post 
{
    public static function first($id, $info) 
    {
        $pdo = Connection::get();

        $allowedFields = [
            'title',
            'body'
        ];

        $fields = $info->getFieldSelection();

        $sql = createSelectSql('posts', $fields, $allowedFields, 'WHERE id=?');

        $statement = $pdo->prepare($sql);
        $statement->execute([
            $id
        ]);

        return $statement->fetch();
    }

    public static function byAuthor($author_id, $info) 
    {
        $pdo = Connection::get();

        $allowedFields = [
            'title',
            'body'
        ];

        $fields = $info->getFieldSelection();

        $sql = createSelectSql('posts', $fields, $allowedFields, 'WHERE author_id=?');

        $statement = $pdo->prepare($sql);
        $statement->execute([
            $author_id
        ]);

        return $statement->fetchAll();
    }

    public static function all($info)
    {
        $pdo = Connection::get();

        $allowedFields = [
            'title',
            'body'
        ];

        $fields = $info->getFieldSelection();

        $sql = createSelectSql('posts', $fields, $allowedFields);

        $statement = $pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function paginate($page, $limit, $info)
    {
        $pdo = Connection::get();

        $allowedFields = [
            'title',
            'body'
        ];

        $offset = $limit * ($page - 1);

        $fields = $info->getFieldSelection();

        $sql = createSelectSql('posts', $fields, $allowedFields, "LIMIT $offset, $limit");

        $statement = $pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }
}