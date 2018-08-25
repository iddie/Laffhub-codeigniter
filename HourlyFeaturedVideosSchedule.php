<?php 
require_once 'vendor/autoload.php';

function config_db()
{
    $dataSource = new \Delight\Db\PdoDataSource('mysql');
    $dataSource->setHostname('localhost');
    $dataSource->setPort(3306);
    $dataSource->setDatabaseName('laffhub_laffhubdb');
    $dataSource->setCharset('utf8mb4');
    $dataSource->setUsername('root');
    $dataSource->setPassword('omega95');
//    $dataSource->setUsername('laffhub_laffuser');
    // $dataSource->setPassword('vUzm6Nh^^y*v');
    $db = \Delight\Db\PdoDatabase::fromDataSource($dataSource);
    return $db;
}
function get_categories()
{
    $db = config_db();
    $categories = $db->select(
        'SELECT * FROM video_categories'
    );
    return ['db'=>$db,'categories'=>$categories];
}
function get_video_from_category($db,$category)
{
    $videos = $db->select(
        'SELECT id,category,video_title FROM videos where play_status = 1 and category = ? ORDER BY date_created DESC LIMIT ?',
        [
            $category,
            40,
        ]
    );
    $count = count($videos);
    if($count!=0){
        return $videos[mt_rand(0, $count-1)];
    }
    else{
        $random_fix = $db->select(
            'SELECT id,category,video_title FROM videos where play_status = 1 ORDER BY date_created DESC LIMIT ?',
            [
                50,
            ]
        );
        return $random_fix[mt_rand(0, count($random_fix)-1)];
    }
}
 function reset_featured($db)
 {
    $db->update(
        'videos',
        [
            'featured' => 'NO',
        ],
        [
            // where
            'featured' => 'YES',
        ]
    );
 }  
 function set_featured($db, $id)
 {
    $db->exec(
        "UPDATE videos SET featured = 'YES' WHERE  id = ? ",
       [ 
        $id,
       ]
    );
 } 
function execute()
{
    $data_categories = get_categories();
    $db = $data_categories['db'];
    $categories = $data_categories['categories'];
    $selected_videos = [];
    reset_featured($db);
    foreach($categories as $item){
        $video = get_video_from_category($db, $item['category']);
        set_featured($db, $video['id']);
         $selected_videos[]= $video;
    }
    $video_extra = get_video_from_category(
        $db,
        $categories[mt_rand(0,count($categories)-1)]['category']
    );
    set_featured($db, $video_extra['id']);
    $selected_videos[] = $video_extra;
    return $selected_videos;
}
$data = execute();
echo json_encode([
    'action'=>'Shuffle featured videos',
    'data'=>$data,
    'count'=>count($data),
    'Schedule Time'=>date('Y-m-d H:i:s a')
]);
