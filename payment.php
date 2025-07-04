<?php

require 'vendor/autoload.php';

// Set your server key
\Midtrans\Config::$serverKey = 'SB-Mid-server-C1ta5HP9_KFpsSrBQaSJP3zC';

// Uncomment for production environment
// \Midtrans\Config::$isProduction = true;

// Enable sanitization
\Midtrans\Config::$isSanitized = true;

// Enable 3D-Secure
\Midtrans\Config::$is3ds = true;

// Create the transaction
$transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => 145000, // no decimal allowed for creditcard
);

$item_details = array(
    array(
        'id' => 'a1',
        'price' => 145000,
        'quantity' => 1,
        'name' => "Apple"
    )
);

$customer_details = array(
    'first_name' => "Andri",
    'last_name' => "Litani",
    'email' => "andri@litani.com",
    'phone' => "081122334455"
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details
);

$snapToken = \Midtrans\Snap::getSnapToken($transaction);
echo "snapToken = ".$snapToken;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>
</head>
<body>
    <button id="pay-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Pay!</button>
    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('<?= $snapToken ?>');
    };
    </script>
</body>
</html>
