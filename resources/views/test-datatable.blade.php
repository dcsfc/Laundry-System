<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Data Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Data Table Test</h1>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600">Testing if dataTable function is available:</p>
            <p id="test-result" class="text-sm font-mono"></p>
        </div>
        
        <x-data-table
            :columns="$columns"
            :data="$users"
            :actions="$actions"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :empty-message="'No data found'"
            :title="'Test Users'"
            :description="'A simple test of the data table component'"
        />
    </div>

    <script>
        // Test if dataTable function is available
        document.addEventListener('DOMContentLoaded', function() {
            const testResult = document.getElementById('test-result');
            if (typeof window.dataTable === 'function') {
                testResult.textContent = '✅ dataTable function is available';
                testResult.className = 'text-sm font-mono text-green-600';
                
                // Debug: Check if Alpine is working
                setTimeout(() => {
                    const alpineElement = document.querySelector('[x-data]');
                    if (alpineElement) {
                        console.log('Alpine element found:', alpineElement);
                        console.log('Alpine data:', alpineElement._x_dataStack);
                    } else {
                        console.log('No Alpine element found');
                    }
                }, 1000);
            } else {
                testResult.textContent = '❌ dataTable function is NOT available';
                testResult.className = 'text-sm font-mono text-red-600';
            }
        });

        function viewUser(row) {
            console.log('View user:', row);
            alert('View user: ' + row.id + ' - ' + row.name);
        }

        function editUser(row) {
            console.log('Edit user:', row);
            alert('Edit user: ' + row.id + ' - ' + row.name);
        }
    </script>
</body>
</html>
