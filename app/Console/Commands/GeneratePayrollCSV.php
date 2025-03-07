<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayrollService;

class GeneratePayrollCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:generate {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payroll CSV for sales staff';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $year = $this->argument('year') ?? now()->year;

        try {
            $payrollService = new PayrollService($year);
            $csvPath = $payrollService->generatePayrollCSV();

            $this->info("Payroll CSV successfully generated: {$csvPath}");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
