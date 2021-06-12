<?php

namespace App\Service;

use App\Entity\Expense;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Yectep\PhpSpreadsheetBundle\Factory;

class ExpenseReportGenerator {

    private Factory $excelBundle;
    private Spreadsheet $spreadsheet;
    private int $currentIndex = 1;
    private string $reportBasePath;
    private ?string $reportPath = null;

    public function __construct(
        Factory $excelBundle,
        ParameterBagInterface $parameterBag
    ) {

        $this->excelBundle = $excelBundle;
        $this->spreadsheet = $excelBundle->createSpreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);
        $this->reportBasePath = $parameterBag->get('generated_report_base_path');
    }

    public function generateExpenseReport(array $expenses) {

        $this->writeHeader();
        foreach ($expenses as $expense) {
            $this->writeRow($expense);
        }
        $this->resizeColumns();
        $this->saveReport();
    }

    private function writeHeader() {

        $this
            ->spreadsheet
            ->getActiveSheet()
            ->setCellValue("A$this->currentIndex", "S/N")
            ->setCellValue("B$this->currentIndex", "Owner")
            ->setCellValue("C$this->currentIndex", "Amount in ₦")
            ->setCellValue("D$this->currentIndex", "Incurred On")
            ->setCellValue("E$this->currentIndex", "Status")
            ->getStyle("A$this->currentIndex:E$this->currentIndex")
            ->getFont()
            ->setBold(true);
        $this->applyThinBorder("A$this->currentIndex:E$this->currentIndex");
        $this->currentIndex++;
    }

    private function applyThinBorder(string $range) {

        $this
            ->spreadsheet
            ->getActiveSheet()
            ->getStyle($range)
            ->applyFromArray(
                [
                    'borders'   => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => [
                                'argb' => Color::COLOR_BLACK
                            ],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]
            );
    }

    private function writeRow(Expense $expense) {

        $this
            ->spreadsheet
            ->getActiveSheet()
            ->setCellValue("A$this->currentIndex", $this->currentIndex - 1)
            ->setCellValue("B$this->currentIndex", $expense->getOwner())
            ->setCellValue("C$this->currentIndex", number_format($expense->getAmount(), 2))
            ->setCellValue("D$this->currentIndex", $expense->getIncurredOn()->format('l jS F, Y'))
            ->setCellValue("E$this->currentIndex", ucfirst($expense->getStatus()));
        $this->applyThinBorder("A$this->currentIndex:E$this->currentIndex");
        $this->currentIndex++;
    }

    private function resizeColumns() {

        $columns = ['B', 'C', 'D', 'E'];
        foreach ($columns as $column) {
            $this->spreadsheet
                ->getActiveSheet()
                ->getColumnDimension($column)
                ->setWidth(50);
        }
    }

    private function saveReport() {

        $this->reportPath = "$this->reportBasePath/Expense Report " . time() . ".xlsx";
        $writer = $this->excelBundle->createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($this->reportPath);
    }

    public function getReportPath()
    : string {

        return $this->reportPath;
    }
}