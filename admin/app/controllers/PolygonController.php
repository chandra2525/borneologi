<?php

require_once __DIR__ . '/../models/Polygon.php';
require_once __DIR__ . '/../core/csrf.php';

class PolygonController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Polygon($pdo);
    }

    /*
    =========================
    LIST POLYGON
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    public function getDataTables(
        $start,
        $length,
        $search,
        $orderColumn,
        $orderDir
    ) {
        $start = max(0, (int) $start);

        $length = (int) $length;

        if ($length < 1) {
            $length = 10;
        }

        if ($length > 100) {
            $length = 100;
        }


        /*
    |--------------------------------------------------------------------------
    | Mapping kolom DataTables
    |--------------------------------------------------------------------------
    */

        $allowedColumns = [
            0 => 'id',
            1 => 'kode_polygon',
            2 => 'nama_polygon',
            4 => 'relasi_nama',
            5 => 'relasi_tipe',
            6 => 'is_active'
        ];

        $orderColumn = $allowedColumns[$orderColumn] ?? 'id';

        $orderDir = strtolower($orderDir) === 'desc'
            ? 'DESC'
            : 'ASC';


        /*
    |--------------------------------------------------------------------------
    | Total data
    |--------------------------------------------------------------------------
    */

        $recordsTotal = $this->model->countAll();


        /*
    |--------------------------------------------------------------------------
    | Total data setelah search
    |--------------------------------------------------------------------------
    */

        $search = trim($search);

        if ($search === '') {

            $recordsFiltered = $recordsTotal;
        } else {

            $recordsFiltered = $this->model->countFiltered($search);
        }


        /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

        $data = $this->model->getPaginated(
            $start,
            $length,
            $search,
            $orderColumn,
            $orderDir
        );


        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    /*
    =========================
    GET POLYGON BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE POLYGON
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_polygon = trim($data['kode_polygon']);
        $nama_polygon = trim($data['nama_polygon']);
        $geom_area = trim($data['geom_area']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_polygon' => $kode_polygon,
            'nama_polygon' => $nama_polygon,
            'geom_area' => $geom_area,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE POLYGON
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_polygon = trim($data['kode_polygon']);
        $nama_polygon = trim($data['nama_polygon']);
        $geom_area = trim($data['geom_area']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_polygon' => $kode_polygon,
            'nama_polygon' => $nama_polygon,
            'geom_area' => $geom_area,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE POLYGON (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getHutanAdat()
    {
        return $this->model->getHutanAdat();
    }

    public function getProvinsi()
    {
        return $this->model->getProvinsi();
    }

    public function getKabupaten()
    {
        return $this->model->getKabupaten();
    }

    public function getKecamatan()
    {
        return $this->model->getKecamatan();
    }

    public function getDesa()
    {
        return $this->model->getDesa();
    }

    public function getKaleka()
    {
        return $this->model->getKaleka();
    }

    public function getHutanLindung()
    {
        return $this->model->getHutanLindung();
    }

    public function getHutanProduksiTetap()
    {
        return $this->model->getHutanProduksiTetap();
    }

    public function getHutanProduksiTerbatas()
    {
        return $this->model->getHutanProduksiTerbatas();
    }

    public function getHutanProduksiKonversi()
    {
        return $this->model->getHutanProduksiKonversi();
    }

    public function getKawasanKonservasi()
    {
        return $this->model->getKawasanKonservasi();
    }

    public function getAreaPenggunaanLain()
    {
        return $this->model->getAreaPenggunaanLain();
    }

    public function getPolygonHAData()
    {
        return $this->model->getPolygonHAData();
    }

    public function getPolygonProvData()
    {
        return $this->model->getPolygonProvData();
    }

    public function getPolygonKabData()
    {
        return $this->model->getPolygonKabData();
    }

    public function getPolygonKecData()
    {
        return $this->model->getPolygonKecData();
    }

    public function getPolygonDesaData()
    {
        return $this->model->getPolygonDesaData();
    }

    public function getPolygonKalekaData()
    {
        return $this->model->getPolygonKalekaData();
    }

    public function getPolygonHutanLindungData()
    {
        return $this->model->getPolygonHutanLindungData();
    }

    public function getPolygonHutanProduksiTetapData()
    {
        return $this->model->getPolygonHutanProduksiTetapData();
    }

    public function getPolygonHutanProduksiTerbatasData()
    {
        return $this->model->getPolygonHutanProduksiTerbatasData();
    }

    public function getPolygonHutanProduksiKonversiData()
    {
        return $this->model->getPolygonHutanProduksiKonversiData();
    }

    public function getPolygonKawasanKonservasiData()
    {
        return $this->model->getPolygonKawasanKonservasiData();
    }

    public function getPolygonAreaPenggunaanLainData()
    {
        return $this->model->getPolygonAreaPenggunaanLainData();
    }

    public function getDetailPolygonProv($id)
    {
        return $this->model->getDetailPolygonProv($id);
    }

    public function getDetailPolygonKab($id)
    {
        return $this->model->getDetailPolygonKab($id);
    }

    public function getDetailPolygonKec($id)
    {
        return $this->model->getDetailPolygonKec($id);
    }

    public function getDetailPolygonDesa($id)
    {
        return $this->model->getDetailPolygonDesa($id);
    }

    public function getDetailPolygonHa($id)
    {
        return $this->model->getDetailPolygonHa($id);
    }

    public function getDetailPolygonKaleka($id)
    {
        return $this->model->getDetailPolygonKaleka($id);
    }

    public function getDetailPolygonBankBenih($id)
    {
        return $this->model->getDetailPolygonBankBenih($id);
    }

    public function getDetailPolygonKalekaKelompokPetani($id)
    {
        return $this->model->getDetailPolygonKalekaKelompokPetani($id);
    }

    public function getPengurusMHA($id)
    {
        return $this->model->getPengurusMHA($id);
    }

    public function getTotalFarmer()
    {
        return $this->model->getTotalFarmer();
    }

    public function getLatLongBenihData()
    {
        return $this->model->getLatLongBenihData();
    }

    public function getMonitoringBenih($id)
    {
        return $this->model->getMonitoringBenih($id);
    }

    public function getLahanBenih($id)
    {
        return $this->model->getLahanBenih($id);
    }
}
