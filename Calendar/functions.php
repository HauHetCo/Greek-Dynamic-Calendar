<?php
function greekMonths(): array {
    return [1=>'Ιανουάριος',2=>'Φεβρουάριος',3=>'Μάρτιος',4=>'Απρίλιος',5=>'Μάιος',6=>'Ιούνιος',7=>'Ιούλιος',8=>'Αύγουστος',9=>'Σεπτέμβριος',10=>'Οκτώβριος',11=>'Νοέμβριος',12=>'Δεκέμβριος'];
}

function greekDaysShort(): array {
    return ['Δε','Τρ','Τε','Πε','Πα','Σα','Κυ'];
}

function greekDaysFull(): array {
    return ['Δευτέρα','Τρίτη','Τετάρτη','Πέμπτη','Παρασκευή','Σάββατο','Κυριακή'];
}

function getMonthGrid(int $year, int $month): array {
    $first = new DateTimeImmutable("$year-$month-01", new DateTimeZone('Europe/Athens'));
    $days = (int)$first->format('t');
    $offset = (int)$first->format('N');
    $cells = [];
    for ($i = 1; $i < $offset; $i++) $cells[] = null;
    for ($day = 1; $day <= $days; $day++) $cells[] = $day;
    while (count($cells) % 7 !== 0) $cells[] = null;
    return array_chunk($cells, 7);
}

function fetchMonthNamedays(PDO $pdo, int $month): array {
    $stmt = $pdo->prepare(
        'SELECT day_of_month, GROUP_CONCAT(person_name ORDER BY person_name SEPARATOR ", ") AS people
         FROM namedays
         WHERE month_of_year = :month
         GROUP BY day_of_month
         ORDER BY day_of_month'
    );
    $stmt->execute(['month' => $month]);
    $rows = $stmt->fetchAll();
    $result = [];
    foreach ($rows as $row) {
        $result[(int)$row['day_of_month']] = $row['people'];
    }
    return $result;
}

function fetchTodayNamedays(PDO $pdo, int $month, int $day): array {
    $stmt = $pdo->prepare(
        'SELECT person_name FROM namedays
         WHERE month_of_year = :month AND day_of_month = :day
         ORDER BY person_name'
    );
    $stmt->execute(['month' => $month, 'day' => $day]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function searchNameday(PDO $pdo, string $q): array {
    $stmt = $pdo->prepare(
        'SELECT person_name, month_of_year, day_of_month, notes
         FROM namedays
         WHERE person_name LIKE :q
         ORDER BY person_name, month_of_year, day_of_month'
    );
    $stmt->execute(['q' => '%' . $q . '%']);
    return $stmt->fetchAll();
}

function fetchFixedHolidays(PDO $pdo, int $month): array {
    $stmt = $pdo->prepare('SELECT day_of_month, title FROM holidays WHERE month_of_year = :month ORDER BY day_of_month');
    $stmt->execute(['month' => $month]);
    $rows = $stmt->fetchAll();
    $result = [];
    foreach ($rows as $row) {
        $result[(int)$row['day_of_month']] = $row['title'];
    }
    return $result;
}
