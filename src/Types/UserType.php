<?php

namespace App\GraphQL\Types;

use App\GraphQL\AppType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Database\Post;

class UserType extends ObjectType
{
    public function __construct()
    {
        $config = $this->config();
        parent::__construct($config);
    }

    private function config() 
    {
        return [
            'name' => 'User',
            'description' => 'Exibe um usuario',
            'fields' => [
                'id' => [
                    'type' => Type::id(),
                ],
                'name' => [
                    'type' => Type::string()
                ],
                'email' => [
                    'type' => Type::string()
                ],
                'posts' => [
                    'description' => 'Artigos escritos por este user.',
                    'type' => Type::listOf(AppType::post())
                ]
            ],
            'resolveField' => function($value, $args, $context, $info) {
                if ($info->fieldName == 'posts') {
                    return Post::byAuthor($value['id'], $info); 
                }

                return $value[$info->fieldName];
            }

        ];
    }
}