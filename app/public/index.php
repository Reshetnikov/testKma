<?php
require_once __DIR__ . '/../init.php';

// Получение данных из MariaDB
$mariaDbConnection = App::getMariaDbConnection();
$sql = "SELECT DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') as groupingTime,
               COUNT(*) as countRequests,
               AVG(length) as avgLength,
               MIN(create_at) as minTime,
               MAX(create_at) as maxTime
        FROM requests 
        GROUP BY groupingTime
        ORDER BY groupingTime ASC";
$listDataMariaDB = $mariaDbConnection->query($sql, PDO::FETCH_OBJ)->fetchAll();

// Получение данных из ClickHouse
$clickHouseConnection = App::getClickHouseConnection();
$sql = "SELECT toStartOfMinute(create_at) as groupingTime,
               COUNT(*) as countRequests,
               AVG(length) as avgLength,
               MIN(create_at) as minTime,
               MAX(create_at) as maxTime
        FROM requests 
        GROUP BY groupingTime
        ORDER BY groupingTime ASC";
$listDataClickHouse = $clickHouseConnection->select($sql)->rows();

?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="/app.css" />
</head>
<body>

<table>
    <caption>MariaDB</caption>
    <thead>
    <tr>
        <th>минута группировки</th>
        <th>количество строк за минуту</th>
        <th>средняя длина контента</th>
        <th>время первого сообщения в минуте</th>
        <th>время последнего сообщения в минуте</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($listDataMariaDB as $row) { ?>
        <tr>
            <td><?=date('d.m.Y H:i', strtotime($row->groupingTime))?></td>
            <td><?=$row->countRequests?></td>
            <td><?=$row->avgLength*1?></td>
            <td><?=date('d.m.Y H:i:s', strtotime($row->minTime))?></td>
            <td><?=date('d.m.Y H:i:s', strtotime($row->maxTime))?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<table>
    <caption>ClickHouse</caption>
    <thead>
    <tr>
        <th>минута группировки</th>
        <th>количество строк за минуту</th>
        <th>средняя длина контента</th>
        <th>время первого сообщения в минуте</th>
        <th>время последнего сообщения в минуте</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($listDataClickHouse as $row) { ?>
        <tr>
            <td><?=date('d.m.Y H:i', strtotime($row['groupingTime']))?></td>
            <td><?=$row['countRequests']?></td>
            <td><?=$row['avgLength']?></td>
            <td><?=date('d.m.Y H:i:s', strtotime($row['minTime']))?></td>
            <td><?=date('d.m.Y H:i:s', strtotime($row['maxTime']))?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>
