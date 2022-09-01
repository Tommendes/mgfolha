<?php
$scriptFolder = __DIR__ . ("/../procedures/updates/base/");

$clientFolder = __DIR__ . ("/../updates/site/");

if (!file_exists($clientFolder)) {
    mkdir($clientFolder, 0755, true);
}

foreach (array_slice(scandir($scriptFolder), 2) as $script) {
    $scriptFrom = $scriptFolder . $script;
    $scriptTo = $clientFolder . $script;
    echo "\$scriptFrom: $scriptFrom,\n \$scriptTo: $scriptTo\n";

    if (copy($scriptFrom, $scriptTo)) {
        // $command = 'mysql --host=localhost'
        //         . ' --user=mgfolha_folha'
        //         . ' --password=\'E@0[t$X$_}K)Px[l=[\''
        //         . ' --database=mgfolha_folha'
        //         . ' --execute="SOURCE ' . $scriptTo . '"';
        // $result = shell_exec($command);
    } else {
        $result = "Não foi possível criar: $scriptTo";
    }
    echo $result;
}