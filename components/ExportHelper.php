<?php

namespace app\components;

use app\models\Account;
use app\models\AuthItem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportHelper
{

    public static function templateUser($id_branch, $branch_name, $fieldNames)
    {
        $user = Helper::identity();

        $title = 'Template User Cabang ' . $branch_name;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Impor');

        $i = $start = 2;
        $sheet->setCellValue('A' . ($i - 1), 'Impor User Cabang ' . $branch_name);

        $row = $field = 'A';
        foreach ($fieldNames as $name) {
            $field = $row;
            $sheet->setCellValue($row . $i, $name);
            $row++;
        }

        $modelUser = Account::find()
            ->innerJoinWith(['role'])
            ->where(['id_client' => $user->id_client])
            ->andWhere(['id_branch' => $id_branch])
            ->all();

        // ['Username', 'Password', 'Role', 'Email', 'Name', 'Nip', 'No Telp', 'Cabang'];
        foreach ($modelUser as $index => $m) {
            $sheet->setCellValue('A' . ($i + $index + 1), $m->username)
                ->setCellValue('B' . ($i + $index + 1), NULL)
                ->setCellValue('C' . ($i + $index + 1), $m->role->item_name)
                ->setCellValue('D' . ($i + $index + 1), $m->email)
                ->setCellValue('E' . ($i + $index + 1), $m->name)
                ->setCellValue('F' . ($i + $index + 1), $m->nip)
                ->setCellValue('G' . ($i + $index + 1), $m->no_telp);
        }

        self::setAutoSize($sheet, $field, $start, $i);

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Master')
            ->setCellValue('A' . ($i - 1), 'Data Referensi')
            ->setCellValue('A' . $i, 'Role');

        foreach (AuthItem::getList() as $role) {
            $i++;
            $sheet2->setCellValue('A' . $i, $role);
        }

        self::doExport($title, $spreadsheet);
        die;
    }

    public static function requiredCell($sheet, $cell)
    {
        $sheet->getCell($cell)
            ->getDataValidation()
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setPrompt('Harus diisi');
    }

    public static function doExport($title, $spreadsheet)
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = str_replace(' ', '_', $title) . "_" . date("d_m_Y_His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($spreadsheet, 'Xls');

        $objWriter->save('php://output');
    }

    public static function setAutoSize($sheet, $last, $i = 13, $j = 13)
    {
        $title = [
            'font' => array(
                'bold' => true,
                'size' => 16
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
            ),
        ];
        $header = [
            'font' => array(
                'bold' => true,
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('argb' => '899DCF00'),
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
            ),
        ];
        $border = [
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            )
        ];
        $tt = $i - 1;
        $sheet->getStyle("A$tt:$last$tt")->applyFromArray($title);
        $sheet->getStyle("A$i:$last$i")->applyFromArray($header);
        $sheet->getStyle("A$i:$last$j")->applyFromArray($border);
        $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD'];
        $stop = false;
        foreach ($abc as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
            if ($col == $last) {
                $stop = TRUE;
                break;
            }
        }

        if (!$stop) {
            foreach ($abc as $i) {
                foreach ($abc as $j) {
                    $col = $i . $j;
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    if ($col == $last) {
                        return;
                    }
                }
            }
        }
    }
}

function colorFilled($sheet, $range)
{
    $style = [
        'fill' => array(
            'fillType' => Fill::FILL_SOLID,
            'color' => array('argb' => 'FFA500'),
        ),
        'borders' => array(
            'allBorders' => array(
                'borderStyle' => Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
            ),
        )
    ];
    $sheet->getStyle($range)->applyFromArray($style);

    return $sheet;
}

function setDropdown($sheet, $col, $values)
{
    $validation = $sheet->getCell($col)->getDataValidation();
    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
    $validation->setFormula1($values);
    $validation->setAllowBlank(false);
    $validation->setShowInputMessage(true);
    $validation->setShowErrorMessage(true);
    $validation->setShowDropDown(true);
    $validation->setErrorTitle('Input error');
    $validation->setError('Value is not in list.');
    $validation->setPromptTitle('Pick from list');
    $validation->setPrompt('Please pick a value from the drop-down list.');
}
