<?php
header('Content-Type: application/json');
require_once '../config/database.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        try {
            $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $products]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("INSERT INTO products (name, category, price, quantity, description) 
                                  VALUES (:name, :category, :price, :quantity, :description)");
            
            $stmt->execute([
                ':name' => $data['name'],
                ':category' => $data['category'],
                ':price' => $data['price'],
                ':quantity' => $data['quantity'],
                ':description' => $data['description']
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("UPDATE products 
                                  SET name = :name, 
                                      category = :category, 
                                      price = :price, 
                                      quantity = :quantity, 
                                      description = :description
                                  WHERE id = :id");
            
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':category' => $data['category'],
                ':price' => $data['price'],
                ':quantity' => $data['quantity'],
                ':description' => $data['description']
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $id = $_GET['id'];
            $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
?> 