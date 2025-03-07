<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\PayrollService;
use Carbon\Carbon;

class GeneratePayrollCSVTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPayrollDatesAreCalculatedCorrectly()
    {
        $payrollService = new PayrollService(2025);
        $payrollData = $payrollService->generatePayrollData();

        foreach ($payrollData as $entry) {
            $salaryDate = Carbon::parse($entry['Salary Payment Date']);
            $bonusDate = Carbon::parse($entry['Bonus Payment Date']);

            // Salary should not fall on a weekend
            $this->assertFalse($salaryDate->isWeekend());

            // Bonus should be on the 15th or next Wednesday
            if ($bonusDate->day !== 15) {
                $this->assertEquals(Carbon::WEDNESDAY, $bonusDate->dayOfWeek);
            }
        }
    }
}
