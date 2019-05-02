<?php
    
    namespace App\Console\Commands;
    
    use App\Models\Client;
    use App\Models\Order;
    use App\Models\Outsource;
    use App\Models\Paper;
    use App\Models\Service;
    use App\Models\Status;
    use App\User;
    use Carbon\Carbon;
    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\DB;
    
    class MigrateDatabase extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'db:migrate';
        
        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Migrate old database in new database';
        
        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function handle() {
            $_users = DB::select('select * from `_users`');
            foreach ($_users as $_user) {
                User::create([
                                 'login'      => $_user->login,
                                 'last_name'  => $_user->lastname,
                                 'first_name' => $_user->firstname,
                                 'password'   => bcrypt($_user->password),
                                 'role'       => $_user->role == 'ADMIN' ? 1 : 2,
                             ]);
            }
            DB::statement('insert into `statuses` (name) select name from `_statuses` order by id');
            DB::statement('insert into `services` (name) select name from `_servises` order by id');
            DB::statement('insert into `papers` (name) select name from `_papers` order by id');
            DB::statement('insert into `outsources` (name, code) select name, code from `_outsources` order by id');
            DB::statement('insert into `clients` (name, phone, email) select client, phone, email from `_clients` order by id');
            DB::statement('insert into `sms` (sms_id, order_id, type, is_sent) select sms1_id, id, 1, 1 from `_orders` where sms1_id > 0 order by id');
            DB::statement('insert into `sms` (sms_id, order_id, type, is_sent) select sms2_id, id, 2, 1 from `_orders` where sms2_id > 0 order by id');
            
            $_orders = DB::select('select * from `_orders`');
            foreach ($_orders as $_order) {
                $order = new Order();
                $user  = User::whereRaw('concat(last_name, " ", first_name) = "'.$_order->operator.'"')->first();
                if ($user) {
                    $order->user_id = $user->id;
                }
                if ($_order->phone) {
                    $client = Client::where('phone', $_order->phone)->first();
                    if ($client) {
                        $order->client_id = $client->id;
                    }
                }
                if ($_order->status) {
                    $status = Status::where('name', $_order->status)->first();
                    if ($status) {
                        $order->status_id = $status->id;
                    }
                }
                if ($_order->outsource) {
                    $outsource = Outsource::where('code', $_order->outsource)->first();
                    if ($outsource) {
                        $order->status_id = $status->id;
                    }
                }
                if ($_order->service) {
                    $service = Service::where('name', $_order->service)->first();
                    if ($service) {
                        $order->service_id = $service->id;
                    }
                }
                if ($_order->paper) {
                    $paper = Paper::where('name', $_order->paper)->first();
                    if ($paper) {
                        $order->paper_id = $paper->id;
                    }
                }
                $order->is_color     = $_order->incolor == 'on' ? true : false;
                $order->is_non_color = $_order->noncolor == 'on' ? true : false;
                $order->quantity     = $_order->qty > 0 ? $_order->qty : null;
                switch ($_order->pay) {
                    case 'Наличные':
                        $order->pay_type = 1;
                        break;
                    case 'Счёт б/н':
                        $order->pay_type = 2;
                        break;
                    case 'Карта':
                        $order->pay_type = 3;
                        break;
                }
                $order->amount       = (int)$_order->grn1 + ((int)$_order->kops1 / 100);
                $order->prepayment   = (int)$_order->grn2 + ((int)$_order->kops2 / 100);
                $order->price_design = (int)$_order->price_design;
                $order->comment      = $_order->comment_full;
                $order->is_files     = $_order->files;
                $order->created_at   = $_order->date_create;
                if (strlen($_order->date) == 16) {
                    $order->date_end = $order->updated_at = Carbon::parse($_order->date);
                } else {
                    $order->date_end = $order->updated_at = $_order->date_create;
                }
    
                $order->save();
            }
        }
    }