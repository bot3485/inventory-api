<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserDetail;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class UserStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:stats';

    /**
     * The console command description.
     */
    protected $description = 'Display detailed statistics on users and their activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== INVENTORY SYSTEM STATISTICS ===");

        // 1. Collecting general data
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $managers = User::where('role', 'manager')->count();
        $simpleUsers = User::where('role', 'user')->count();

        // 2. Collecting activity data (Online status)
        // We count users who have performed actions within the last 15 minutes
        $onlineCount = UserDetail::where('updated_at', '>', Carbon::now()->subMinutes(15))->count();

        // 3. Token data (security audit)
        $activeTokens = PersonalAccessToken::count();

        // OUTPUT TABLE #1: Role Distribution
        $this->newline();
        $this->comment("Role distribution:");
        $this->table(
            ['Role', 'Count'],
            [
                ['Administrators', $admins],
                ['Managers', $managers],
                ['Employees', $simpleUsers],
                ['TOTAL', $totalUsers],
            ]
        );

        // OUTPUT TABLE #2: Current Activity
        $this->comment("Current activity:");
        $this->table(
            ['Parameter', 'Value'],
            [
                ['Users online (15 min)', $onlineCount],
                ['Total active API tokens', $activeTokens],
            ]
        );

        // System status notification
        if ($onlineCount > 0) {
            $this->info("✅ The system is actively being used.");
        } else {
            $this->warn("💤 The system is currently idle.");
        }

        return Command::SUCCESS;
    }
}
