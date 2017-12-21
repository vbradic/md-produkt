<?php

function calculate_discount_price($fabric_price, $discount) {
    return ($fabric_price - ($discount/100)*$fabric_price);
}
