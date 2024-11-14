<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
    
    // Log the request
    error_log("Generating report for last $days days");
    
    // Get sales trend data
    $salesTrendQuery = $conn->prepare("
        SELECT DATE(sale_date) as date,
               SUM(total_amount) as amount
        FROM sales
        WHERE sale_date >= DATE_SUB(CURRENT_DATE, INTERVAL :days DAY)
        GROUP BY DATE(sale_date)
        ORDER BY date ASC
    ");
    
    $salesTrendQuery->execute([':days' => $days]);
    $salesTrend = $salesTrendQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the sales trend data
    error_log("Sales trend data: " . print_r($salesTrend, true));
    
    // Get category distribution data
    $categoryQuery = $conn->query("
        SELECT p.category,
               SUM(s.total_amount) as amount
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.sale_date >= DATE_SUB(CURRENT_DATE, INTERVAL $days DAY)
        GROUP BY p.category
        ORDER BY amount DESC
    ");
    
    $categoryData = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the category data
    error_log("Category data: " . print_r($categoryData, true));
    
    // Format data for charts
    $dates = array_column($salesTrend, 'date');
    $amounts = array_column($salesTrend, 'amount');
    
    $categories = array_column($categoryData, 'category');
    $categoryAmounts = array_column($categoryData, 'amount');
    
    $response = [
        'status' => 'success',
        'salesTrend' => [
            'dates' => $dates,
            'amounts' => $amounts
        ],
        'categoryData' => [
            'categories' => $categories,
            'amounts' => $categoryAmounts
        ]
    ];
    
    // Log the response
    error_log("Sending response: " . print_r($response, true));
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    error_log("Error in reports.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 