<?php

require_once __DIR__ . '/../models/DetailMonitoringPenanaman.php';
require_once __DIR__ . '/../core/csrf.php';

class DetailMonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new DetailMonitoringPenanaman($pdo);
    }


    public function index()
    {
        return $this->model->getAll();
    }


    public function find($id)
    {
        return $this->model->findById($id);
    }


    /**
     * CREATE
     */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->createWithStock(
            $data,
            $user_id
        );
    }


    /**
     * UPDATE
     */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->updateWithStock(
            $id,
            $data,
            $user_id
        );
    }


    /**
     * DELETE
     */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->deleteWithStock(
            $id,
            $user_id
        );
    }


    public function getBankBenih()
    {
        return $this->model->getBankBenih();
    }


    public function getMonitoringPenanaman()
    {
        return $this->model->getMonitoringPenanaman();
    }


    public function getBankBenihById($id)
    {
        return $this->model->getBankBenihById($id);
    }
}