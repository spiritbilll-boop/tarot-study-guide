<?php
/******************************************************************************
 *
 *  today.php
 *
 *  Tarot Card of the Day
 *
 *  Calculates one deterministic Tarot card for the current day.
 *
 *  The card changes automatically at local midnight (America/Phoenix)
 *  and cycles continuously through all 78 Tarot cards.
 *
 ******************************************************************************/

date_default_timezone_set('America/Phoenix');

$epoch = new DateTime(
    '2000-01-01',
    new DateTimeZone('America/Phoenix')
);

$today = new DateTime(
    'now',
    new DateTimeZone('America/Phoenix')
);

$days = $epoch->diff($today)->days;

$card_id = ($days % 78) + 1;

header("Location: card.php?id={$card_id}&today=1");

exit;
?>
