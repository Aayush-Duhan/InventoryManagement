async function editCustomer(id) {
    window.location.href = `edit_customer.php?id=${id}`;
}

async function deleteCustomer(id) {
    // First get customer details including order count
    try {
        const checkResponse = await fetch(`api/customers.php?id=${id}`);
        const checkResult = await checkResponse.json();
        
        let message = 'Are you sure you want to delete this customer?';
        if(checkResult.data && checkResult.data.total_orders > 0) {
            message = `This customer has ${checkResult.data.total_orders} orders. Deleting this customer will also delete all their order history. Are you sure you want to proceed?`;
        }

        if (confirm(message)) {
            const response = await fetch(`api/customers.php?id=${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                alert('Customer deleted successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting the customer');
    }
} 