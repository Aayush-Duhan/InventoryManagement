<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Get sales data grouped by product
    $stmt = $conn->query("
        SELECT p.name, 
               COALESCE(SUM(s.total_amount), 0) as revenue
        FROM products p
        LEFT JOIN sales s ON p.id = s.product_id
        GROUP BY p.id, p.name
        ORDER BY revenue DESC
        LIMIT 10
    ");
    
    $products = [];
    $revenues = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row['name'];
        $revenues[] = floatval($row['revenue']);
    }
    
    echo json_encode([
        'status' => 'success',
        'labels' => $products,
        'revenues' => $revenues
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 