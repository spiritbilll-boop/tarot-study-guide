<?php

$card_id = (date('z') % 78) + 1;

header("Location: card.php?id=" . $card_id);

exit;
?>
