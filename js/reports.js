let salesTrendChart;
let categoryChart;

document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    updateCharts(); // Initial load

    // Add event listener for time range changes
    const timeRangeSelect = document.getElementById('timeRange');
    if(timeRangeSelect) {
        timeRangeSelect.addEventListener('change', updateCharts);
    }
});

function initCharts() {
    // Initialize Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    if(salesTrendChart) {
        salesTrendChart.destroy();
    }
    salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Daily Sales',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });

    // Initialize Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    if(categoryChart) {
        categoryChart.destroy();
    }
    categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

async function updateCharts() {
    const timeRange = document.getElementById('timeRange').value;
    
    try {
        const response = await fetch(`api/reports.php?days=${timeRange}`);
        const data = await response.json();
        
        if (data.status === 'success') {
            // Update Sales Trend Chart
            salesTrendChart.data.labels = data.salesTrend.dates;
            salesTrendChart.data.datasets[0].data = data.salesTrend.amounts;
            salesTrendChart.update();

            // Update Category Chart
            categoryChart.data.labels = data.categoryData.categories;
            categoryChart.data.datasets[0].data = data.categoryData.amounts;
            categoryChart.update();
        }
    } catch (error) {
        console.error('Error updating charts:', error);
    }
} 