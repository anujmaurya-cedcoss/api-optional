<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;

define("BASE_PATH", __DIR__);
require_once(BASE_PATH . '/vendor/autoload.php');

$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://root:VajsFVXK36vxh4M6@cluster0.nwpyx9q.mongodb.net/?retryWrites=true&w=majority'
        );
        return $mongo->rest_api;
    },
    true
);
$container->set(
    'collectionManager',
    function () {
        return new Manager();
    }
);
$app = new Micro($container);

// Retrieves all robots
$app->get(
    '/api/robots',
    function () {
        $collection = $this->mongo->robots;
        $robotList = $collection->find();
        $data = [];

        foreach ($robotList as $robot) {
            $data[] = [
                'id' => $robot['id'],
                'name' => $robot['name'],
                'year' => $robot['year'],
                'type' => $robot['type']
            ];
        }
        echo json_encode($data);
    }
);

// Searches for robots with $name in their name
$app->get(
    '/api/robots/search/{name}',
    function ($name) {
        $collection = $this->mongo->robots;
        $robotList = $collection->find(["name" => $name]);

        $data = [];

        foreach ($robotList as $robot) {
            $data[] = [
                'id' => $robot['id'],
                'name' => $robot['name'],
                'type' => $robot['type'],
                'year' => $robot['year']
            ];
        }
        echo json_encode($data);
    }
);

// Retrieves robots based on key
$app->get(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
        $collection = $this->mongo->robots;
        $robotList = $collection->findOne(["id" => (int)$id]);
        echo json_encode($robotList);
    }
);

// Adds a new robot
$app->post(
    '/api/robots',
    function () use ($app) {
        $robot = $app->request->getJsonRawBody();
        $collection = $this->mongo->robots;
        $arr = [
            "id" => $robot->id,
            "name" => $robot->name,
            "year" => $robot->year,
            "type" => $robot->type
            
        ];
        $status = $collection->insertOne($arr);
        $inserted = $status->getInsertedCount();
        echo $inserted;
    }
);

// update the robot
$app->put(
    '/api/robots/{id:[0-9]+}',
    function ($id) use ($app) {
        $robot = $app->request->getJsonRawBody();
        $response = $this->mongo->robots->updateOne(['id' => (int) $id], ['$set' => $robot]);
        return $response;
    }
);

// Deletes robots based on primary key
$app->delete(
    '/api/robots/{id:[0-9]+}',
    function ($id) use ($app) {
        $response = $this->mongo->robots->deleteOne(["id" => (int) $id]);
        return $response;
    }
);

$app->handle($_SERVER['REQUEST_URI']);
