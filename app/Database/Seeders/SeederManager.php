<?php

namespace App\Database\Seeders;

use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\Enums\NotificationLogType;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class SeederManager
{
    /**
     * @var bool
     */
    private bool $printActions = false;

    public function __construct()
    {
    }

    public function runSeeders()
    {
        $this->users();
    }

    /**
     * @return void
     */
    protected function users(): void
    {
        $userRepo = new UserRepository();
        $userRepo->create([
            'username' => 'admin',
            'password' => password_hash('admin', PASSWORD_BCRYPT),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        // Print alert in console if its needed
        if($this->printActions){
            echo "Seeder 'users' ran.\n";
        }
    }

    /**
     * @param bool $printActions
     */
    public function printActions(bool $printActions): void
    {
        $this->printActions = $printActions;
    }
}