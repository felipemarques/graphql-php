<?php

use GraphQL\GraphQL;
use App\GraphQL\AppType;
use GraphQL\Type\Schema;
use App\GraphQL\Connection;
use App\GraphQL\Database\User;
use App\GraphQL\Types\QueryType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

try {

    $schema = new Schema([
        'query' => new QueryType(),
        'mutation' => new ObjectType([
            'name' => 'Mutations',
            'fields' => [
                'createUser' => [
                    'type' => AppType::user(),
                    'args' => [
                        'name' => Type::nonNull(Type::string()),
                        'email' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function($value, $args) {
                        try {
                            //return ['name' => $args['name'], 'email' => $args['email']];
                            return User::insert($args['name'], $args['email'], 1);
                        } catch (\Exception $e) {
                            return ['name' => $e->getMessage()];
                        }
                    }
                ]
            ]
        ])
    ]);

    $input = file_get_contents('php://input');
    $input = json_decode($input, true);
    $query = $input['query'];

    $variables = $input['variables'] ?? null;
        
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();

} catch (\Exception $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage()
            ]
        ]
    ];
}

header('Content-type: application/json');
echo json_encode($output);