<?php
header('Content-Type: application/json');
require_once '../config/database.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        try {
            $stmt = $conn->prepare("SELECT * FROM customers ORDER BY created_at DESC");
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $customers]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address) 
                                  VALUES (:name, :email, :phone, :address)");
            
            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':address' => $data['address']
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Customer added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("UPDATE customers 
                                  SET name = :name, 
                                      email = :email, 
                                      phone = :phone, 
                                      address = :address
                                  WHERE id = :id");
            
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':address' => $data['address']
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Customer updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $id = $_GET['id'];
            
            // Start transaction
            $conn->beginTransaction();
            
            // First check if customer has any sales
            $checkSales = $conn->prepare("SELECT COUNT(*) FROM sales WHERE customer_id = :id");
            $checkSales->execute([':id' => $id]);
            $hasOrders = $checkSales->fetchColumn() > 0;
            
            if($hasOrders) {
                // Delete associated sales records first
                $deleteSales = $conn->prepare("DELETE FROM sales WHERE customer_id = :id");
                $deleteSales->execute([':id' => $id]);
            }
            
            // Then delete the customer
            $deleteCustomer = $conn->prepare("DELETE FROM customers WHERE id = :id");
            $deleteCustomer->execute([':id' => $id]);
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Customer and associated records deleted successfully'
            ]);
            
        } catch(PDOException $e) {
            // Rollback transaction on error
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
?> 