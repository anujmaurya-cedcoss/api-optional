<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->response->redirect('/index/display');
    }
    public function displayAction()
    {
        $ch = curl_init();
        $url = "http://172.31.0.5/api/robots";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        $this->view->data = $output;
    }

    public function editAction()
    {
        $id = $_GET['id'];
        $ch = curl_init();
        $url = "http://172.31.0.5/api/robots/$id";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        
        $this->view->data = $output;
    }

    public function updateAction()
    {
        $id = $_GET['id'];
        $_POST['id'] = (int) $id;
        $_POST['year'] = (int)$_POST['year'];
        $ch = curl_init();
        $url = "http://172.31.0.5/api/robots/$id";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'put');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_POST));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        $this->response->redirect('/index/display');
    }

    public function deleteAction()
    {
        $id = $_GET['id'];
        $ch = curl_init();
        $url = "http://172.31.0.5/api/robots/$id";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'delete');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        $this->response->redirect('/index/display');
    }

    public function addNewAction()
    {
        // redirected to view
    }
    public function addAction()
    {
        $_POST['id'] = (int)$_POST['id'];
        $_POST['year'] = (int)$_POST['year'];
        $ch = curl_init();
        $url = "http://172.31.0.5/api/robots";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_POST));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
        $this->response->redirect('/index/display');
    }
}
