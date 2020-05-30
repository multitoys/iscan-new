<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RenameOrderFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:rename:folder';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dirs = Storage::allDirectories(Order::FILES_DIR);
        foreach ($dirs as $dir) {
            $dir = '/'.$dir;
            $dir = str_replace(Order::FILES_DIR.'/', '', $dir);

            if (strlen($dir) < 6) {
                Storage::move(Order::FILES_DIR.'/'.$dir, Order::getFolder($dir));
            }
        }
    }
}
