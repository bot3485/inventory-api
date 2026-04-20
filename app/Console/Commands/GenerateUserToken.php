<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUserToken extends Command
{
    /**
     * The name and signature of the console command.
     * We added '--force' option to bypass the confirmation if needed.
     * Use: php artisan user:token {email} {--force}
     */
    protected $signature = 'user:token {email} {--force : Revoke all existing tokens before generating a new one}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a single active API token for a user, revoking previous ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $force = $this->option('force');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Error: User with email [{$email}] not found in the database.");
            return Command::FAILURE;
        }

        // 1. CHECK & CLEANUP: Find existing tokens
        $existingTokensCount = $user->tokens()->count();

        if ($existingTokensCount > 0) {
            if ($force || $this->confirm("User already has {$existingTokensCount} active token(s). Do you want to revoke them all and generate a fresh one?")) {
                $user->tokens()->delete();
                $this->warn("All previous tokens for {$user->name} have been revoked.");
            } else {
                $this->info("Operation cancelled. No new tokens were created.");
                return Command::SUCCESS;
            }
        }

        // 2. GENERATE: Create the new exclusive token
        $token = $user->createToken('CLI-Terminal-Access')->plainTextToken;

        $this->info("Success! New exclusive token for {$user->name} ({$user->role}):");
        $this->line($token);

        return Command::SUCCESS;
    }
}
