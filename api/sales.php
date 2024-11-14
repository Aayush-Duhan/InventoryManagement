<?php
header('Content-Type: application/json');
require_once '../config/database.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Start transaction
            $conn->beginTransaction();
            
            // Check product availability
            $checkProduct = $conn->prepare("SELECT quantity, price FROM products WHERE id = :id");
            $checkProduct->execute([':id' => $data['product_id']]);
            $product = $checkProduct->fetch(PDO::FETCH_ASSOC);
            
            if(!$product) {
                throw new Exception('Product not found');
            }
            
            if($product['quantity'] < $data['quantity']) {
                throw new Exception('Insufficient stock');
            }
            
            // Insert sale record
            $stmt = $conn->prepare("INSERT INTO sales (product_id, customer_id, quantity, total_amount) 
                                  VALUES (:product_id, :customer_id, :quantity, :total_amount)");
            
            $stmt->execute([
                ':product_id' => $data['product_id'],
                ':customer_id' => $data['customer_id'],
                ':quantity' => $data['quantity'],
                ':total_amount' => $data['total_amount']
            ]);
            
            // Update product quantity
            $updateProduct = $conn->prepare("UPDATE products 
                                          SET quantity = quantity - :sold_quantity 
                                          WHERE id = :id");
            
            $updateProduct->execute([
                ':id' => $data['product_id'],
                ':sold_quantity' => $data['quantity']
            ]);
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['status' => 'success', 'message' => 'Sale added successfully']);
            
        } catch(Exception $e) {
            // Rollback transaction on error
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
?> 