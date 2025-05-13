<?php
require_once __DIR__ . '/../entidad/Carrera.php';
require_once __DIR__ . '/DataSource.php';

class CarreraDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

}