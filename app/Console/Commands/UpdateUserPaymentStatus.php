<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateUserPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserPaymentStatusUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment status every month from paid to pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        //Update payment status for users who paid at least one month ago
        User::where('payment_status', 'Paid')
            ->whereNotNull('payment_date') // Make sure there is a payment history
            ->whereRaw('TIMESTAMPDIFF(MONTH, payment_date, NOW()) >= 1') //Checks one month has passed since the date
            ->update(['payment_status' => 'Pending']);
    }
}
