<?php

require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Data Penerima Manfaat');

/*
|--------------------------------------------------------------------------
| JUDUL
|--------------------------------------------------------------------------
*/

$sheet->mergeCells('A1:I1');

$sheet->setCellValue(
    'A1',
    'Template Data Penerima Manfaat'
);

$sheet->getStyle('A1')->applyFromArray([
    'font' => [
        'bold' => true,
        'size' => 16
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

$sheet->getRowDimension(1)->setRowHeight(30);


/*
|--------------------------------------------------------------------------
| KETERANGAN
|--------------------------------------------------------------------------
*/

$sheet->mergeCells('A2:I2');

$sheet->setCellValue(
    'A2',
    'Silahkan isi dengan data yang sesuai agar bisa terupload ke Borneologi, untuk kolom bertanda bintang merah wajib diisi'
);

$sheet->getStyle('A2')->applyFromArray([
    'font' => [
        'italic' => true,
        'size' => 11
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ]
]);

$sheet->getRowDimension(2)->setRowHeight(35);


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

$headers = [
    'A' => 'NIK',
    'B' => 'No KK',
    'C' => 'Nama Lengkap *',
    'D' => 'Nama Panggilan',
    'E' => 'Jenis Kelamin *',
    'F' => 'Tanggal Lahir',
    'G' => 'No HP',
    'H' => 'Desa *',
    'I' => 'Alamat'
];

foreach ($headers as $column => $header) {

    $sheet->setCellValue(
        $column . '4',
        $header
    );
}


/*
|--------------------------------------------------------------------------
| STYLE HEADER
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A4:I4')->applyFromArray([
    'font' => [
        'bold' => true
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'D9EAF7'
        ]
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

$sheet->getRowDimension(4)->setRowHeight(35);


/*
|--------------------------------------------------------------------------
| WARNA BINTANG MERAH
|--------------------------------------------------------------------------
*/

$requiredColumns = ['C', 'E', 'H'];

foreach ($requiredColumns as $column) {

    $richText = new RichText();

    $text = str_replace(
        ' *',
        '',
        $headers[$column]
    );

    $runText = $richText->createTextRun($text);

    $runText->getFont()->setBold(true);

    $runStar = $richText->createTextRun(' *');

    $runStar->getFont()
        ->setBold(true)
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));

    $sheet->getCell($column . '4')
        ->setValue($richText);
}


/*
|--------------------------------------------------------------------------
| CONTOH DATA
|--------------------------------------------------------------------------
*/

$sheet->setCellValueExplicit(
    'A5',
    '6271010101010001',
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
);

$sheet->setCellValueExplicit(
    'B5',
    '6271010101010001',
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
);

$sheet->setCellValue('C5', 'Contoh Nama Lengkap');
$sheet->setCellValue('D5', 'Contoh');
$sheet->setCellValue('E5', 'L');
$sheet->setCellValue('F5', '01/01/1990');

$sheet->setCellValueExplicit(
    'G5',
    '081234567890',
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
);

$sheet->setCellValue('H5', 'Contoh Nama Desa');
$sheet->setCellValue('I5', 'Contoh alamat lengkap');


/*
|--------------------------------------------------------------------------
| STYLE CONTOH
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A5:I5')->applyFromArray([
    'font' => [
        'italic' => true
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);


/*
|--------------------------------------------------------------------------
| FORMAT KOLOM TEXT
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A:A')
    ->getNumberFormat()
    ->setFormatCode('@');

$sheet->getStyle('B:B')
    ->getNumberFormat()
    ->setFormatCode('@');

$sheet->getStyle('G:G')
    ->getNumberFormat()
    ->setFormatCode('@');


/*
|--------------------------------------------------------------------------
| BORDER DATA
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A5:I100')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);


/*
|--------------------------------------------------------------------------
| LEBAR KOLOM
|--------------------------------------------------------------------------
*/

$widths = [
    'A' => 20,
    'B' => 20,
    'C' => 28,
    'D' => 20,
    'E' => 20,
    'F' => 18,
    'G' => 18,
    'H' => 30,
    'I' => 40
];

foreach ($widths as $column => $width) {
    $sheet->getColumnDimension($column)
        ->setWidth($width);
}


/*
|--------------------------------------------------------------------------
| FREEZE HEADER
|--------------------------------------------------------------------------
*/

$sheet->freezePane('A5');


/*
|--------------------------------------------------------------------------
| VALIDASI NIK DAN NO KK
|--------------------------------------------------------------------------
|
| Maksimal 16 digit dan hanya angka.
|
*/

foreach (['A', 'B'] as $column) {

    $validation = $sheet
        ->getCell($column . '5')
        ->getDataValidation();

    $validation->setType(
        DataValidation::TYPE_CUSTOM
    );

    $validation->setErrorStyle(
        DataValidation::STYLE_STOP
    );

    $validation->setAllowBlank(true);

    $validation->setShowInputMessage(true);

    $validation->setShowErrorMessage(true);

    $validation->setShowDropDown(false);

    $validation->setErrorTitle(
        'Format tidak valid'
    );

    $validation->setError(
        'Kolom ini hanya boleh berisi angka dan maksimal 16 digit.'
    );

    $validation->setPromptTitle(
        'NIK / No KK'
    );

    $validation->setPrompt(
        'Masukkan maksimal 16 digit angka.'
    );

    $validation->setFormula1(
        '=AND(ISNUMBER(--' . $column . '5),LEN(' . $column . '5)<=16)'
    );

    /*
    | Terapkan validation ke baris 5 sampai 1000
    */
    for ($row = 5; $row <= 1000; $row++) {

        if ($row > 5) {

            $validationClone = clone $validation;

            $sheet
                ->getCell($column . $row)
                ->setDataValidation(
                    $validationClone
                );
        }
    }
}

/*
|--------------------------------------------------------------------------
| DOWNLOAD
|--------------------------------------------------------------------------
*/

$filename = 'Template_Data_Penerima_Manfaat.xlsx';

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment; filename="' . $filename . '"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);

$writer->save('php://output');

exit;