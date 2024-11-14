<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $customerId = $_GET['customer_id'];
        
        // Get customer name
        $customerStmt = $conn->prepare("SELECT name FROM customers WHERE id = ?");
        $customerStmt->execute([$customerId]);
        $customerName = $customerStmt->fetchColumn();
        
        // Get customer orders
        $stmt = $conn->prepare("
            SELECT s.*, p.name as product_name
            FROM sales s
            JOIN products p ON s.product_id = p.id
            WHERE s.customer_id = ?
            ORDER BY s.sale_date DESC
        ");
        
        $stmt->execute([$customerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'customer_name' => $customerName,
            'orders' => $orders
        ]);
        
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?> 