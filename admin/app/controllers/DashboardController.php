<?php

require_once __DIR__ . '/../models/Dashboard.php';
require_once __DIR__ . '/../core/csrf.php';

class DashboardController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Dashboard($pdo);
    }

    public function index()
    {
        return $this->model->getAll();
    }
}