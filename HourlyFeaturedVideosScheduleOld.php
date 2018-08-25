<?php 
require_once 'vendor/autoload.php';

function config_db()
{
    $dataSource = new \Delight\Db\PdoDataSource('mysql');
    $dataSource->setHostname('localhost');
    $dataSource->setPort(3306);
    $dataSource->setDatabaseName('laffhub_laffhubdb');
    $dataSource->setCharset('utf8mb4');
    // $dataSource->setUsername('root');
    // $dataSource->setPassword('omega95');
   $dataSource->setUsername('laffhub_laffuser');
    $dataSource->setPassword('vUzm6Nh^^y*v');
    $db = \Delight\Db\PdoDatabase::fromDataSource($dataSource);
    return $db;
}
function get_latest_videos($limit=5)
{
    $db = config_db();
    $latest_videos_ids = $db->select(
        'SELECT id FROM videos where play_status = 1 ORDER BY date_created DESC LIMIT ?',
        [
            50,
        ]
    );
    shuffle($latest_videos_ids);
    for ($x=0; $x <=12;$x++) {
        $ids[] = $latest_videos_ids[$x]['id'];
    }
    $take_five_random = $db->select(
        'SELECT id,category,video_title FROM videos WHERE id = ? or id = ? or id = ? or  id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? ',
        $ids
    );
    return ['db'=>$db,'data'=>$take_five_random];
}
get_latest_videos();
function update_featured()
{
    $videos = get_latest_videos();
    foreach ($videos['data'] as $video) {
        $ids[] =  $video['id'];
    }
    $videos['db']->update(
        'videos',
        [
            'featured' => 'NO',
        ],
        [
            // where
            'featured' => 'YES',
        ]
    );
    $videos['db']->exec(
        "UPDATE videos SET featured = 'YES' WHERE  id = ? or id = ? or  id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ? or id = ?",
       [ 
        $ids[0],
        $ids[1],
        $ids[2],
        $ids[3],
        $ids[4],
        $ids[5],
        $ids[6],
        $ids[7],
        $ids[8],
        $ids[9],
        $ids[10],
        $ids[11]
       ]
    );
    return $videos['data'];
}
$data = update_featured();
echo json_encode([
    'action'=>'Shuffle featured videos',
    'data'=>$data,
    'Schedule Time'=>date('Y-m-d H:i:s a')
]);
