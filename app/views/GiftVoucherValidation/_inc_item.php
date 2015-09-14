<?php
$gift_voucher_item	= $item->voucher;
$item->date_str 	= g::formatDateTime($item->date);
$item->value_str 	= "$" . number_format($item->value  , 2, '.', ',');
$item->balance_str 	= "$" . number_format($item->balance, 2, '.', ',');
?>