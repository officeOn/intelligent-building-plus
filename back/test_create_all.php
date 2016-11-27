<?php
namespace xyz;

use sgk\back\CleanableCleaningToolDBAdaptor;
use sgk\back\CleanableDBAdaptor;
use sgk\back\RefillableDBAdaptor;
use sgk\back\UnitDBAdaptor;
use sgk\back\ZoneDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$commonArea1 = [
    [83.19489563661588, -48.86718750000001],
    [75.23066741281573, -130.07812500000003],
    [51.6180165487737, -166.64062500000003],
    [38.82259097617713, -111.09375000000001],
    [70.61261423801925, -107.22656250000001],
    [79.81230226556369, -41.13281250000001]
];
$commonArea1Json = json_encode($commonArea1, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$commonArea1Zone = ZoneDBAdaptor::create('Common Area 1', 'COMMON', $commonArea1Json);

$offices1 = [
    [48.922499263758255, -165.58593750000003],
    [43.32517767999296, -143.78906250000003],
    [25.799891182088334, -115.66406250000001],
    [12.55456352859367, -119.17968750000001],
    [13.923403897723347, -144.49218750000003],
    [-13.581920900545844, -152.92968750000003],
    [-13.923403897723347, -174.37500000000003],
    [19.973348786110613, -175.42968750000003]
];
$offices1Json = json_encode($offices1, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$offices1Zone = ZoneDBAdaptor::create('Offices Area', 'OFFICES', $offices1Json);

$concentrationArea1 = [
    [-84.33698037639608, -15.117187500000002],
    [-81.30832090051811, -14.414062500000002],
    [-80.87282721505684, 9.843750000000002],
    [-77.8418477505252, 9.492187500000002],
    [-77.46602847687328, 30.937500000000004],
    [-83.94227191521858, 40.78125000000001]
];
$concentrationArea1Json = json_encode($concentrationArea1, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$concentrationArea1Zone = ZoneDBAdaptor::create('Concentration Area 1', 'CONCENTRATION', $concentrationArea1Json);

$commonArea2 = [
    [-62.43107423292092, -156.09375000000003],
    [-42.811521745097885, -79.80468750000001],
    [-63.54855223203643, -38.3203125],
    [-78.6991059255054, -63.63281250000001],
    [-80.5897269130857, -42.890625],
    [-83.86761625146906, -49.921875],
    [-81.03861703916249, -103.71093750000001]
];
$commonArea2Json = json_encode($commonArea2, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$commonArea2Zone = ZoneDBAdaptor::create('Common Area 2', 'COMMON', $commonArea2Json);

$kitchen = [
    [-15.623036831528252, -154.33593750000003],
    [-44.087585028245165, -153.28125],
    [-43.580390855607845, -101.95312500000001],
    [-15.623036831528252, -112.14843750000001]
];
$kitchenJson = json_encode($kitchen, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$kitchenZone = ZoneDBAdaptor::create('Kitchen 1', 'COMMON', $kitchenJson);

$coffeeMachineCoordinates = [-35.0, -115.0];
$coffeeMachineCoordinatesJson = json_encode($coffeeMachineCoordinates, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES);
$coffeeMachine = UnitDBAdaptor::create('Coffee Machine', $kitchenZone->getId(), $coffeeMachineCoordinatesJson);

$coffeeBeansContainer = RefillableDBAdaptor::create(
    'Coffee Beans Container',
    $coffeeMachine->getId(),
    45,
    100,
    'COFFEE_BEANS'
);

$coffeeCupStack = RefillableDBAdaptor::create(
    'Coffee Cup Stack',
    $coffeeMachine->getId(),
    10,
    30,
    'COFFEE_CUPS'
);

$creamerContainer = RefillableDBAdaptor::create(
    'Creamer Container',
    $coffeeMachine->getId(),
    78,
    100,
    'CREAMER'
);

$usedCoffeeContainer = CleanableDBAdaptor::create(
    'Used Coffee Container',
    $coffeeMachine->getId(),
    75
);
CleanableCleaningToolDBAdaptor::create($usedCoffeeContainer->getId(), 'SPONGE');
CleanableCleaningToolDBAdaptor::create($usedCoffeeContainer->getId(), 'DISH_WASHING_LIQUID');

$internalPipes = CleanableDBAdaptor::create(
    'Pipes of Coffee Machine',
    $coffeeMachine->getId(),
    85
);
CleanableCleaningToolDBAdaptor::create($internalPipes->getId(), 'TUBE_BRUSH');

$exterior = CleanableDBAdaptor::create(
    'Exterior of Coffee Machine',
    $coffeeMachine->getId(),
    30
);
CleanableCleaningToolDBAdaptor::create($exterior->getId(), 'CLOTH');
CleanableCleaningToolDBAdaptor::create($exterior->getId(), 'SPIRIT');
