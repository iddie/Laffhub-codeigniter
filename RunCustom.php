<?php 
require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;

function config_db()
{
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'laffhub_test',
        'username'  => 'root',
        'password'  => 'omega95',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);   
    $capsule->setAsGlobal();
}

function execute()
{
    config_db();
    $completeData =  Capsule::table('subscriptions')->get();
    $count = $completeData->count();
    $start = 1;
    foreach($completeData as $row){
        echo "Working ID ".$row->id."\n";
        if ($row->id>2225) {
            $optout = Capsule::table('optouts')->where('msisdn', $row->msisdn)
            ->limit(1)->get();
            if ($optout->count()>0) {
                $optout_item = $optout[0];
                if ($optout_item->optout_date>$row->subscribe_date) {
                    echo "Setting UnsubDate to ".$optout_item->optout_date."\n";
                    Capsule::table('subscriptions')->where('id', $row->id)
                ->update(['date_unsubscribed'=>$optout_item->optout_date]);
                }
            }
            $sub_mode = Capsule::table('sms_requests')->where('msisdn', $row->msisdn)
        ->where('message', 'YES')->get();
            if ($sub_mode->count()>0 && $row->subscription_mode!='SMS') {
                echo "Setting sub mode to SMS \n";
                Capsule::table('subscriptions')->where('id', $row->id)->update(['subscription_mode'=>'SMS']);
            } else if($sub_mode->count()==0 && $row->subscription_mode!='WEB') {
                echo "Setting sub mode to WEB \n";
                Capsule::table('subscriptions')->where('id', $row->id)->update(['subscription_mode'=>'WEB']);
            }
        }
    }
    echo 'Action completed';
}
execute();