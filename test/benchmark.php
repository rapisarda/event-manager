<?php 
require '../vendor/autoload.php';

$start = microtime(true);
$evm = new Rapisarda\EventManager\EventManager;
$nbEvent = 100;
$listenerPerEvent = 1000;

for($i=0 ; $i < $nbEvent; $i++){
    $ev = new \Rapisarda\EventManager\Event("event-$i");
    for($j = 0; $j < $listenerPerEvent; $j++){
        $evm->attach($ev->getName(), function() {}, rand());
    }
    $evm->trigger($ev->getName());
}

$totaltime = microtime(true) - $start;
echo "total time with $nbEvent event and $listenerPerEvent sorted listener per event: $totaltime";
echo '<br>';

$start = microtime(true);
$evm = new Rapisarda\EventManager\EventManager;

for($i=0 ; $i < $nbEvent; $i++){
    $ev = new \Rapisarda\EventManager\Event("event-$i");
    for($j = 0; $j < $listenerPerEvent; $j++){
        $evm->attach($ev->getName(), function() {});
    }
    $evm->trigger($ev->getName());
}

$totaltime = microtime(true) - $start;
echo "total time with $nbEvent event and $listenerPerEvent not sorted listener per event: $totaltime";
echo '<br>';

echo '<hr>';

echo 'Test for know which sort method are faster';
echo '<br>';

$nbListener = 10000;
for($i = 0; $i< $nbListener; $i++){
    $p = rand();
//    $p = 0;
    $listener[$i] = [
        'p' =>  $p,
        'c' => function(){},
    ];
    $priorities[$i] = $p;
    $callables[$i]  = function(){};
    
    $priorities2[$i] = $p;
    $callables2[$i]  = function(){};
}

// usort \\
$startUsort = microtime(true);
usort($listener, function($a, $b){
    return $b['p'] - $a['p'];
});
$totaltime = microtime(true) - $startUsort;
echo "total time usort with $nbListener listener: $totaltime";
echo '<br>';

// array_multisort \\
$startarray_multisort = microtime(true);
array_multisort($priorities, SORT_DESC, $callables);
$totaltime = microtime(true) - $startarray_multisort;
echo "total time array_multisort with $nbListener listener: $totaltime";
echo '<br>';

// arsort \\
$startarsort = microtime(true);
arsort($priorities2);
$totaltime = microtime(true) - $startarsort;
echo "total time arsort with $nbListener listener: $totaltime";
echo '<br>';

