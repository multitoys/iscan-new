<?php
    
    namespace App\Console\Commands;
    
    use App\Models\Client;
    use App\Models\Order;
    use App\Models\Outsource;
    use App\Models\Paper;
    use App\Models\Service;
    use App\Models\Sms;
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
            DB::transaction(function () {
                $_users = DB::connection('old')->select('select * from `users`');
//                dd($_users);
                foreach ($_users as $_user) {
                    User::create([
                        'login'      => $_user->login,
                        'last_name'  => $_user->lastname,
                        'first_name' => $_user->firstname,
                        'password'   => bcrypt($_user->password),
                        'role'       => $_user->role == 'ADMIN' ? 1 : 2,
                    ]);
                }
                $this->info('users complete!');
                
                $_statuses = DB::connection('old')->select('select * from `statuses` order by id');
                foreach ($_statuses as $_status) {
                    Status::create(['name' => $_status->name]);
                }
                $this->info('statuses complete!');
                
                $_services = DB::connection('old')->select('select * from `servises` order by id');
                foreach ($_services as $_service) {
                    Service::create(['name' => $_service->name]);
                }
                $this->info('services complete!');
                
                $_papers = DB::connection('old')->select('select * from `papers` order by id');
                foreach ($_papers as $_paper) {
                    Paper::create(['name' => $_paper->name]);
                }
                $this->info('papers complete!');
                
                $_outsources = DB::connection('old')->select('select * from `outsources` order by id');
                foreach ($_outsources as $_outsource) {
                    Outsource::create(['name' => $_outsource->name, 'code' => $_outsource->code]);
                }
                $this->info('outsources complete!');
                
                $_clients = DB::connection('old')->select('select * from `clients` order by id');
                foreach ($_clients as $_client) {
                    Client::create([
                        'name'  => $_client->client,
                        'phone' => $_client->phone,
                        'email' => $_client->email
                    ]);
                }
                $this->info('clients complete!');
                
                $_orders = DB::connection('old')->select('select * from `orders`');
                $bar     = $this->output->createProgressBar(count($_orders));
                foreach ($_orders as $_order) {
                    $order = new Order();
                    $user  = User::whereRaw('concat(last_name, " ", first_name) = "' . $_order->operator . '"')->first();
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
                    if (preg_match('/\d{4}-\d{2}-\d{2}\s\d{2}\:\d{2}/', $_order->date)) {
                        $order->date_end = $order->updated_at = Carbon::parse($_order->date);
                    } else {
                        $order->date_end = $order->updated_at = $_order->date_create;
                    }
        
                    $order->save();
                    
                    if ($_order->sms1_id > 0) {
                        Sms::create([
                            'sms_id'   => $_order->sms1_id,
                            'order_id' => $_order->id,
                            'type'     => 1,
                            'is_sent'  => 1,
                        ]);
                    }
                    if ($_order->sms2_id > 0) {
                        Sms::create([
                            'sms_id'   => $_order->sms1_id,
                            'order_id' => $_order->id,
                            'type'     => 2,
                            'is_sent'  => 1,
                        ]);
                    }
                    
                    $bar->advance();
                }
                $bar->finish();
                $this->info('orders complete!');
            });
        }
    }