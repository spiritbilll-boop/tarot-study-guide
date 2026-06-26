<?php

$card_id = rand(1,78);

header("Location: card.php?id=" . $card_id);

exit;
