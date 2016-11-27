<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;
use \sgk\back\ZoneDBAdaptor;
use \sgk\back\TaskDBAdaptor;
use \sgk\back\UnitDBAdaptor;
use \sgk\back\ZoneResponseBodyGenerator;
use \sgk\back\CleanableResponseBodyGenerator;
use \sgk\back\UnitResponseBodyGenerator;
use \sgk\back\RefillableResponseBodyGenerator;
use \sgk\back\CleanableCleaningToolResponseBodyGenerator;
use \sgk\back\UserResponseBodyGenerator;
use \sgk\back\TaskResponseBodyGenerator;
use \sgk\back\CleanableDBAdaptor;
use \sgk\back\RefillableDBAdaptor;
use \sgk\back\CleanableCleaningToolDBAdaptor;

use \sgk\back\UserDBadaptor;
// CleanableCleaningToolResponseBodyGenerator

require_once __DIR__ . '/vendor/autoload.php';

$app = new App;
// $zonedbadapter = new ZoneDBAdaptor();
// $app->get('/hello/{name}', function (Request $request, Response $response) {
//     $name = $request->getAttribute('name');
//     $response->getBody()->write("Hello, $name");

//     return $response;
// });
//task put get
// employ get + stats probably
					// $app->post('/unit', function ($request, $response) {
					// 		// add
					// 		$allPostVars = $request->getParsedBody(); //array of new items
					// 		$response = $response->getBody()->write(getBodyJson($allPostVars));
					//  		return $response;
					// });
function defineId($request)
{
	$id = $request->getAttribute('id');
	return $id;
}
function FormatTask($taskArray)
{
	// return $taskArray;
	if(isset($taskArray['id'])){
		if($taskArray['type']=='CLEAN'){
				$CleanableDBAdaptor = new CleanableDBAdaptor();
				$id = $taskArray['relatedItemId'];
				$currentCleanables = $CleanableDBAdaptor->read($id);
				$data = CleanableCollectionNullCheck($currentCleanables,0);
				$data['kek']='heh';
				return $data;
				// var_dump($currentCleanables);	
				// sendUsingSlim($response,$data);
		}else{
			return false;
		}
	}
	return false;
}
function formatLatLngInCoords($array){
	if(!isset($array[0])){
		$array['coordinates']=json_decode($array['coordinates']);
	}else{
		foreach ($array as $key => $value) {
			// echo '1';
			$array[$key]['coordinates']=json_decode($array[$key]['coordinates']);
		}
	}
	return $array;
}


function definer($array){

	if(!isset($array['name'])){
		$array['name']='No name';		
	}
	if(!isset($array['type'])){
		$array['type']='default';		
	}
	if(!isset($array['coordinates'])){
		$array['coordinates']='[]';		
	}

	// $array['name']=isseterAndDefiner($array['name'],'New name');
	// $array['type']=isseterAndDefiner($array['type'],null);
	// $array['coordinates']=isseterAndDefiner($array['coordinates'],null);
	return $array;
}
function sendUsingSlim($response,$array)
{
	$response->getBody()->write(getBodyJson($array));
	return $response;
}
function ZoneCollectionNullCheck($Zone,$count){
	$ZoneResponseBodyGenerator = new ZoneResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $ZoneResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $ZoneResponseBodyGenerator->generateBody($Zone);
		
	}
}
function TaskCollectionNullCheck($Zone,$count){
	$TaskResponseBodyGenerator = new TaskResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $TaskResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $TaskResponseBodyGenerator->generateBody($Zone);
		
	}
}
function UserCollectionNullCheck($Zone,$count){
	$UserResponseBodyGenerator = new UserResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $UserResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $UserResponseBodyGenerator->generateBody($Zone);
		
	}
}
function UnitCollectionNullCheck($Zone,$count){
	$UnitResponseBodyGenerator = new UnitResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $UnitResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $UnitResponseBodyGenerator->generateBody($Zone);
		
	}
}
// Refillable
function RefillableCollectionNullCheck($Zone,$count){
	$RefillableResponseBodyGenerator = new RefillableResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $RefillableResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $RefillableResponseBodyGenerator->generateBody($Zone);
		
	}
}
function CleanableCollectionNullCheck($Zone,$count){
	$ZoneResponseBodyGenerator = new CleanableResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $ZoneResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $ZoneResponseBodyGenerator->generateBody($Zone);
		
	}
}
// CleanableToolCollectionNullCheck
function CleanableToolCollectionNullCheck($Zone,$count){
	$ZoneResponseBodyGenerator = new CleanableCleaningToolResponseBodyGenerator();
	if(is_null($Zone)){
		return [];
	}else if($count!==1){	
		return $ZoneResponseBodyGenerator->generateBulkBody($Zone);
	}else{
		return $ZoneResponseBodyGenerator->generateBody($Zone);
	}
}
$app->put('/tasks/{id}', function ($request, $response) {
	$taskDBAdaptor = new TaskDBAdaptor();
	$id = defineId($request);
	$allPostVars = $request->getParsedBody();
	$singleTask=$taskDBAdaptor->update((int)$allPostVars['userCreatedById'],(int)$allPostVars['userAssignedToId'],(int)$allPostVars['relatedItemId'],$allPostVars['type'],$allPostVars['status']);
	$unFormatedData=TaskCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,$unFormatedData);
	return $response;
});
$app->get('/tasks', function ($request, $response) {
	$taskdbadapter = new TaskDBAdaptor();
	$id = defineId($request);
	$allZones = $taskdbadapter->readAll();
	$unFormatedData=TaskCollectionNullCheck($allZones,0);
	// $FormatedData=[];
	// foreach ($unFormatedData as $key => $value) {
	// 	$FormatedData[]=FormatTask($unFormatedData[$key]);
	// }
	sendUsingSlim($response,$unFormatedData);
	return $response;
});
$app->get('/tasks/{id}', function ($request, $response) { // по ид таска/по ид 
	$TaskDBAdaptor = new TaskDBAdaptor();
	$id = defineId($request);
	$singleZone = $TaskDBAdaptor->read((int)$id);
	$unFormatedData=TaskCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,$unFormatedData);
	return $response;
});
$app->post('/tasks',function($request, $response){
	// TaskResponseBodyGenerator		
	$taskDBAdaptor = new TaskDBAdaptor();
	$allPostVars = $request->getParsedBody();
	// $allPostVars=definer($allPostVars);
	// var_dump($allPostVars);
	$singleZone=$taskDBAdaptor->create((int)$allPostVars['userId'],(int)$allPostVars['relatedItemId'],$allPostVars['type'],$allPostVars['status']);
	$unFormatedData=TaskCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,formatLatLngInCoords($unFormatedData));
	return $response;
});
$app->get('/zone/{id}', function ($request, $response) {
	//lat lng
	$zonedbadapter = new ZoneDBAdaptor();
	$id = defineId($request);
	$singleZone = $zonedbadapter->read((int)$id);
	$unFormatedData=ZoneCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,formatLatLngInCoords($unFormatedData));
	return $response;
});
$app->post('/zone', function ($request, $response) {
	$zonedbadapter = new ZoneDBAdaptor();
	$allPostVars = $request->getParsedBody();
	$allPostVars=definer($allPostVars);
	// var_dump($allPostVars);
	$singleZone=$zonedbadapter->create($allPostVars['name'],$allPostVars['type'],$allPostVars['coordinates']);
	$unFormatedData=ZoneCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,formatLatLngInCoords($unFormatedData));
	return $response;
});
$app->get('/zones', function ($request, $response) {
	$zonedbadapter = new ZoneDBAdaptor();
	$id = defineId($request);
	$allZones = $zonedbadapter->readAll();
	$unFormatedData=ZoneCollectionNullCheck($allZones,0);
	sendUsingSlim($response,formatLatLngInCoords($unFormatedData));
	return $response;
});
$app->get('/units/{id:[0-9]+}/cleanable', function ($request, $response) {
	$CleanableDBAdaptor = new CleanableDBAdaptor();
	$id = defineId($request);
	$currentCleanables = $CleanableDBAdaptor->readAll((int)$id);
	$data = CleanableCollectionNullCheck($currentCleanables,0);
	// var_dump($currentCleanables);	
	sendUsingSlim($response,$data);
	return $response;

});
$app->get('/authorize', function ($request, $response) {
	$choice=rand(1,100);
	if($choice>50){
		$array=[
			"id"=>1,
			"name"=>'Leonardo Di Caprio',
			"type"=>"MAINTENANCE"
		];
	}else{
		$array=[
			"id"=>2,
			"name"=>'Jackie Chan',
			"type"=>"EMPLOYEE"
		];
	}
	sendUsingSlim($response,$array);
	return $response;
});
$app->get('/session_data', function ($request, $response) {	
	session_start();
	$UserDBAdaptor = new UserDBAdaptor();
	if(!isset($_SESSION['user_id'])){
		$_SESSION['user_id']=1;
	}
		$id = $_SESSION['user_id'];
	$singleZone = $UserDBAdaptor->read((int)$id);
	$unFormatedData=UserCollectionNullCheck($singleZone,1);
	sendUsingSlim($response,$unFormatedData);
	return $response;
	// $_SESSION;
});
$app->get('/session_data/{id}', function ($request, $response) {	
	session_start();
	$id = defineId($request);
	$_SESSION['user_id'] = $id;
	// $_SESSION;
	sendUsingSlim($response,array('status'=>200));
	return $response;
});
$app->get('/units/{no_need_in_it_here}/cleanable/{id:[0-9]+}', function ($request, $response) {
	$CleanableCleaningToolDBAdaptor = new CleanableCleaningToolDBAdaptor();
	$id = defineId($request);
	$currentCleanableTool = $CleanableCleaningToolDBAdaptor->readAll((int)$id);
	$data = CleanableToolCollectionNullCheck($currentCleanableTool,0);
	// var_dump($currentCleanables);	
	sendUsingSlim($response,$data);
	return $response;

});
$app->get('/units/{id:[0-9]+}/refillable', function ($request, $response) {
	$RefillableDBAdaptor = new RefillableDBAdaptor();
	$id = defineId($request);
	$currentRefillable = $RefillableDBAdaptor->readAll((int)$id);
	$data = RefillableCollectionNullCheck($currentRefillable,0);
	// var_dump($currentCleanables);	
	sendUsingSlim($response,$data);
	return $response;
});
$app->get('/zones/{id:[0-9]+}/units', function ($request, $response) {
	$unitdbadapter = new UnitDBAdaptor();
	$id = defineId($request);
	$currentUnits = $unitdbadapter->readAll((int)$id);
	// var_dump($currentUnits);
	// foreach ($currentUnits as $key => $value) {
	// 	$currentUnits[$key]['health']=rand(1,100);
	// }
	$collection = UnitCollectionNullCheck($currentUnits,0);
	foreach ($collection as $key => $value) {
		if(isset($value['id'])){
			$collection[$key]['health']=$unitdbadapter->getUnitHealth($value['id']);
		}
	}
	sendUsingSlim($response,$collection);
	return $response;


});
$app->get('/units/{id}', function ($request, $response) {
	$unitdbadapter = new UnitDBAdaptor();
	$id = defineId($request);
	$currentUnits = $unitdbadapter->read((int)$id);
	$collection = UnitCollectionNullCheck($currentUnits,1);
	if(isset($collection['id'])){
		$collection['health']=$unitdbadapter->getUnitHealth($collection['id']);
	}
	sendUsingSlim($response,$collection);
	return $response;
	//modify
});
$app->delete('/units/{id}', function ($request, $response) {
	$id = defineId($request);
	//delete
});
$app->get('/rating/{id}', function (Request $request, Response $response) {
	$id = defineId($request);
	// id | userId | rating [1-5] | delivered [0,1]
	// query
	$userdata=[
		"id"=>$id,
		"rating"=> rand(1,5)
	];
	$response = $response->getBody()->write(getBodyJson($userdata));
	return $response;

});
$app->get('/rating/{id}/long', function (Request $request, Response $response) {
	$id = defineId($request);
	// id | userId | rating [1-5] | delivered [0,1]
	// query
		$delivered=1;
		if($delivered == 1) {
			$refresh=["refresh"=>false];
		}else{
			$refresh=["refresh"=>true];
		}
		    $response = $response->getBody()->write(getBodyJson($refresh));
		    return $response;
});
$app->get('/health', function (Request $request, Response $response) {
    $healthCollection = [];
    $itemOne = [
        'id' => 1,
        'name' => 'Chair',
        'health' => 100,
        'cleaningItems' => [
            'item1',
            'item2'
        ],
        'coordinates' => [-50,-30],
    ];
    $itemTwo = [
        'id' => 2,
        'name' => 'Floor',
        'health' => 80,
        'cleaningItems' => [
            'item3',
            'item4'
        ],
        'coordinates' => [15, 20],
    ];

   	$unitdbadapter = new UnitDBAdaptor();
	// $id = defineId($request);
	$currentUnits = $unitdbadapter->readAll();
	$collection = UnitCollectionNullCheck($currentUnits,0);
	foreach ($collection as $key => $value) {
		if(isset($collection[$key]['id'])){
			$collection[$key]['health']=$unitdbadapter->getUnitHealth($collection[$key]['id']);
		}
	}
	foreach ($collection as $key => $value) {
			if(isset($collection[$key]['id'])){
				$CleanableDBAdaptor = new CleanableDBAdaptor();
				
				$id = $collection[$key]['id'];

				$currentCleanables = $CleanableDBAdaptor->readAll((int)$id);
				$data = CleanableCollectionNullCheck($currentCleanables,0);
				// var_dump($currentCleanables);	
				foreach ($data as $k => $v) {
						$CleanableCleaningToolDBAdaptor = new CleanableCleaningToolDBAdaptor();
						$id = $v['id'];
						$currentCleanableTool = $CleanableCleaningToolDBAdaptor->readAll((int)$id);
						$data = CleanableToolCollectionNullCheck($currentCleanableTool,0);	
						foreach ($data as $ke => $va) {
							$collection[$key]['cleaningItems'][]=$va['cleanableId'];
						}	
			}
		}
	}
	sendUsingSlim($response,$collection);
	return $response;


    // $health_collection[] = $item_one;
    // $health_collection[] = $item_one;

    // $healthCollection = addItemToCollection($healthCollection, $itemOne);
    // $healthCollection = addItemToCollection($healthCollection, $itemTwo);

    // $response = $response->getBody()->write(getBodyJson($healthCollection));

    // return $response;
});

/**
 * @param $collection
 * @param $item
 *
 * @return array
 */
function addItemToCollection($collection, $item)
{
    $collection[] = $item;

    return $collection;
}

/**
 * @param $body
 *
 * @return string
 */
function getBodyJson($body)
{
    return json_encode($body, JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING);
}

$app->run();
// if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
// 	$uri = 'https://';
// } else {
// 	$uri = 'http://';
// }
// $uri .= $_SERVER['HTTP_HOST'];
// header('Location: '.$uri.'/dashboard/');
// exit;
// <!-- Something is wrong with the XAMPP installation :-( -->

// routes
// get -> back/health/
// [{
// 	id:1,
// 	name:'Chair',
// 	health:100,
// 	cleaningItems:[
// 		'швабра',
// 		'пипидастр'
// 	],
// 	surfaceTypes:[

// 	]
// }]
// get -> back/health/{id}
// get -> back/health/{id}/surfaceTypes
// get -> back/health/{id}/cleaningItems
