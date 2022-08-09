<?php

namespace App\Console\Commands;

use App\Services\BankOne\CustomerAccount\UploadServices;
use Illuminate\Console\Command;

class BankOneUploadServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bankOne:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $uploadServices;
    public function __construct(UploadServices $uploadServices)
    {
        $this->uploadServices = $uploadServices;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->uploadServices->uploadPassport();
    }
}
