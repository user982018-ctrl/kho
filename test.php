<?php
$A = [
    ["seller_id" => 1, "name" => "New Customer 1"],
    ["seller_id" => 2, "name" => "New Customer 2"]
];

$B = [
    ["seller_id" => 2, "name" => "Old Customer 2"],
    ["seller_id" => 3, "name" => "Old Customer 3"]
];

// Gộp và loại trùng seller_id
$merged = array_merge($A, $B);

$unique = [];
foreach ($merged as $item) {
    $unique[$item['seller_id']] = $item; // id trùng sẽ bị ghi đè
}

$result = array_values($unique);

echo "<pre>";
print_r($result);
echo "</pre>";