<?php 
require_once 'config/database.php';

// Get customer ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch customer details
if($id) {
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$customer) {
        header('Location: customers.php');
        exit;
    }
} else {
    header('Location: customers.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer - Sales Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.7s ease-out forwards; }
        .text-shadow { text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6); }
        #sidebar.active { transform: translateX(0); }
        .no-transition { transition: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>

    <div class="w-full h-screen overflow-auto p-10 transition-all duration-700" id="main-content">
        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="fixed top-2 left-2 bg-gray-900 text-white p-2 rounded-full z-50">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Page Header -->
        <div class="flex justify-between items-center bg-white p-4 rounded shadow-md relative mb-6">
            <h1 class="text-2xl font-bold">Edit Customer</h1>
        </div>

        <!-- Edit Customer Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="editCustomerForm" class="space-y-6">
                <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="name" required 
                               value="<?php echo htmlspecialchars($customer['name']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required 
                               value="<?php echo htmlspecialchars($customer['email']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" 
                               value="<?php echo htmlspecialchars($customer['phone']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($customer['address']); ?></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.location.href='customers.php'" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="script.js"></script>
    <script>
        document.getElementById('editCustomerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('api/customers.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if(result.status === 'success') {
                    alert('Customer updated successfully!');
                    window.location.href = 'customers.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the customer');
            }
        });
    </script>
</body>
</html> 