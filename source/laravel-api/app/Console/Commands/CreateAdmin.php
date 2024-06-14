<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\Service\AdminService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

/**
 * Create admin command
 * - usage: php artisan create:admin --name=test100 --email=test100@example.com --password=test100pass
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin
        {--name= : Admin name}
        {--email= : Admin email address}
        {--password= : Admin password}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    public function __construct(private AdminService $adminService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $blnRes = $this->validateOptions();
            if (!$blnRes) {
                return 1;
            }
            DB::beginTransaction();
            $userCreate = new UserCreateDTO(
                $this->option('name'),
                $this->option('email'),
                $this->option('name'),
                $this->option('password')
            );
            $this->adminService->createAdmin($userCreate);
            DB::commit();
            Log::channel('applog')->info(
                '[Admin] created successfully',
                ['data' => $this->options()]
            );
            $this->info('Admin user created successfully!');

            return 0;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Admin] Failed to create admin user.',
                ['data' => $this->options()]
            );
            Log::channel('applog')->critical(
                '[Admin] Unexpected Error:',
                ['data' => $th->getMessage()]
            );
            $this->error('Failed to create admin user.');

            return 1;
        }
    }

    private function validateOptions(): bool
    {
        $validator = Validator::make($this->options(), [
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            Log::channel('applog')->error(
                '[Admin] Error in option field.',
                ['data' => $this->options(), 'errors' => $validator->errors()->all()]
            );
            return false;
        }

        return true;
    }
}
