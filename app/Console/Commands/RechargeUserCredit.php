<?php

namespace App\Console\Commands;

use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RechargeUserCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge the user credit. This command should be running by the start of the month.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            User::where(function($query) {
                return $query->where('role_id', Role::PREMIUM_USER)
                    ->orWhere('role_id', Role::REGULAR_USER);
            })->each(function($user) {
                $user->claimCredit();
            });
        } catch (\Exception $e) {
            Log::emergency($e->getMessage(), [
                'trace' => $e->getTrace(),
            ]);
        }

        return 0;
    }
}
