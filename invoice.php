<?php
include 'includes/db.php';

// Check if order_id or service_id is provided
$type = isset($_GET['type']) ? $_GET['type'] : 'order';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid request");
}

if ($type == 'order') {
    // Get order details
    $stmt = $pdo->prepare("SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.order_id = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();
    
    if (!$data) {
        die("Order not found");
    }
    
    // Get order items
    $items_stmt = $pdo->prepare("SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :id");
    $items_stmt->execute([':id' => $id]);
    $items = $items_stmt->fetchAll();
    
    $invoice_no = "INV-ORD-" . str_pad($id, 5, '0', STR_PAD_LEFT);
    $date = date('d M Y', strtotime($data['order_date']));
    $total = $data['total_amount'];
    
} else {
    // Get service details
    $stmt = $pdo->prepare("SELECT s.*, u.name, u.email, g.name as gardener_name FROM services s JOIN users u ON s.user_id = u.user_id LEFT JOIN gardeners g ON s.gardener_id = g.gardener_id WHERE s.service_id = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();
    
    if (!$data) {
        die("Service not found");
    }
    
    $invoice_no = "INV-SVC-" . str_pad($id, 5, '0', STR_PAD_LEFT);
    $date = date('d M Y', strtotime($data['service_date']));
    $items = [];
    $total = 500; // Default service charge
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo $invoice_no; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .invoice-container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 5px 30px rgba(0,0,0,0.1); border-radius: 10px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #2e7d32; }
        .logo { font-size: 2rem; font-weight: 700; color: #2e7d32; }
        .logo i { margin-left: 5px; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { color: #2e7d32; margin-bottom: 10px; }
        .invoice-info p { color: #666; margin: 5px 0; }
        .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .party h4 { color: #2e7d32; margin-bottom: 10px; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        .party p { color: #333; margin: 5px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #2e7d32; color: white; padding: 12px 15px; text-align: left; }
        .items-table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        .items-table tr:hover { background: #f9f9f9; }
        .items-table .text-right { text-align: right; }
        .totals { display: flex; justify-content: flex-end; }
        .totals-table { width: 300px; }
        .totals-table td { padding: 10px 15px; }
        .totals-table .total-row { background: #e8f5e9; font-weight: 700; font-size: 1.2rem; }
        .totals-table .total-row td { color: #2e7d32; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; }
        .footer p { margin: 5px 0; }
        .print-btn { display: inline-block; background: #2e7d32; color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; margin-top: 20px; }
        .print-btn:hover { background: #1b5e20; }
        @media print {
            body { padding: 0; background: white; }
            .invoice-container { box-shadow: none; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">Greenly 🌿</div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>Invoice No:</strong> <?php echo $invoice_no; ?></p>
                <p><strong>Date:</strong> <?php echo $date; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($data['payment_status'] ?? $data['service_status']); ?></p>
            </div>
        </div>
        
        <div class="parties">
            <div class="party">
                <h4>From</h4>
                <p><strong>Greenly Plant Store</strong></p>
                <p>123 Civil Line, Nagpur 440016</p>
                <p>Phone: +91 72491 64457</p>
                <p>Email: help@greenly.com</p>
            </div>
            <div class="party">
                <h4>Bill To</h4>
                <p><strong><?php echo htmlspecialchars($data['name']); ?></strong></p>
                <p>Email: <?php echo htmlspecialchars($data['email']); ?></p>
                <?php if($type == 'service' && $data['gardener_name']): ?>
                    <p>Gardener: <?php echo htmlspecialchars($data['gardener_name']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $type == 'order' ? 'Product' : 'Service'; ?></th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if($type == 'order' && count($items) > 0): ?>
                    <?php $i = 1; foreach($items as $item): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td class="text-right"><?php echo $item['quantity']; ?></td>
                            <td class="text-right">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-right">₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>1</td>
                        <td><?php echo ucfirst($data['service_type']); ?> Service</td>
                        <td class="text-right">1</td>
                        <td class="text-right">₹<?php echo number_format($total, 2); ?></td>
                        <td class="text-right">₹<?php echo number_format($total, 2); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">₹<?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td>Shipping/Tax</td>
                    <td class="text-right">₹0.00</td>
                </tr>
                <tr class="total-row">
                    <td>Grand Total</td>
                    <td class="text-right">₹<?php echo number_format($total, 2); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p><strong>Thank you for choosing Greenly!</strong></p>
            <p>For any queries, contact us at help@greenly.com</p>
            <a href="javascript:window.print()" class="print-btn">🖨️ Print Invoice</a>
        </div>
    </div>
</body>
</html>
