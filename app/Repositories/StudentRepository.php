<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Course;
use App\Models\Product;
use App\Models\Student;

class StudentRepository
{
    public function __construct(public Student $student)
    {
    }

    /**
     * @return void
     */
    public function unAssignCourse()
    {
        $this->student->start_date = null;
        $this->student->course_id = null;
        $this->student->save();
    }

    public function assignCourse(Course $course, $startDate, $optionIds=[])
    {
        $this->student->start_date = date('Y-m-d', strtotime($startDate));
        $this->student->course_id = $course->id;
        $this->assignProducts($optionIds);
        $this->student->save();
    }

    /**
     * @return Bill
     * @throws \Exception
     */
    public function generateRegistrationBill()
    {
        if (!$this->student->course) {
            throw new \Exception('No course assigned');
        }
        $bill = new Bill();
        $bill->student_id = $this->student->id;
        $bill->student_name = $this->student->first_name . ' ' . $this->student->last_name;
        $bill->issue_date = date('Y-m-d');
        $bill->amount = $this->student->course->registration->fee;
        $bill->paid_amount = 0;
        $bill->status = Bill::STATUS_UNPAID;
        $bill->products = [$this->student->course->registration->toArray()];
        $bill->save();
        return $bill;
    }

    /**
     * @return Bill
     * @throws \Exception
     */
    public function generateMonthlyBill()
    {
        if (!$this->student->course) {
            throw new \Exception('No course assigned');
        }
        $amount = $this->student->course->scholarship->fee;
        $amount += $this->student->options->sum('fee');
        $bill = new Bill();
        $bill->student_id = $this->student->id;
        $bill->student_name = $this->student->first_name . ' ' . $this->student->last_name;
        $bill->issue_date = date('Y-m-d');
        $bill->amount = $amount;
        $bill->paid_amount = 0;
        $bill->status = Bill::STATUS_UNPAID;
        $bill->products = $this->student->options->toArray();
        $bill->save();
        return $bill;
    }

    public function assignProducts($productIds)
    {
        $productIds = Product::query()->type(Product::TYPE_OPTION)->whereIn('id', $productIds)->pluck('id')->toArray();
        $this->student->options()->sync($productIds);
    }


}
