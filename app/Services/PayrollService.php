<?php

namespace App\Services;

use Carbon\Carbon;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Log;

class PayrollService
{
    protected int $year;

    public function __construct(int $year = null)
    {
        $this->year = $year ?? now()->year;
    }

    /**
     * Generate payroll data for the year.
     *
     * @return array
     */
    public function generatePayrollData(): array
    {
        $payrollData = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($this->year, $month, 1)->format('F');

            // Calculate base salary date (last day of the month, or previous Friday if weekend)
            $salaryDate = Carbon::createFromDate($this->year, $month, 1)->endOfMonth();
            if ($salaryDate->isWeekend()) {
                $salaryDate = $salaryDate->previous(Carbon::FRIDAY);
            }

            // Calculate bonus date (15th, or next Wednesday if weekend)
            $bonusDate = Carbon::createFromDate($this->year, $month, 15);
            if ($bonusDate->isWeekend()) {
                $bonusDate = $bonusDate->next(Carbon::WEDNESDAY);
            }

            $payrollData[] = [
                'Month' => $monthName,
                'Salary Payment Date' => $salaryDate->toDateString(),
                'Bonus Payment Date' => $bonusDate->toDateString(),
            ];
        }

        return $payrollData;
    }

    /**
     * Generate and save payroll CSV.
     *
     * @return string
     */
    public function generatePayrollCSV(): string
    {
        try {
            $payrollData = $this->generatePayrollData();
            $csv = Writer::createFromFileObject(new SplTempFileObject());

            // Insert heading
            $csv->insertOne(['Month', 'Salary Payment Date', 'Bonus Payment Date']);

            // Insert rows
            foreach ($payrollData as $row) {
                $csv->insertOne($row);
            }

            // Save file to storage
            $filePath = storage_path("payroll_{$this->year}.csv");
            file_put_contents($filePath, $csv->toString());

            Log::info("Payroll CSV successfully generated: {$filePath}");

            return $filePath;
        } catch (\Exception $e) {
            Log::error("Failed to generate payroll CSV: " . $e->getMessage());
            throw new \RuntimeException('Payroll generation failed.');
        }
    }
}
