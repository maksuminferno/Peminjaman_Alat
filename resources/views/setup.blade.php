<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Instructions - Sistem Peminjaman Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --orange-500: #f97316;
            --orange-600: #ea580c;
            --orange-700: #c2410c;
        }
        
        body {
            background-color: #f4f6f9;
            background: linear-gradient(to bottom, #fffaf5, #fff3e8);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, #ffffff, var(--orange-50));
            border-bottom: 1px solid #fde68a;
            color: #2c3e50;
            text-align: center;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: var(--orange-500);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4><i class="fas fa-cog me-2"></i>Setup Instructions</h4>
            </div>
            <div class="card-body">
                <h5>Database Setup Required</h5>
                <p>To use the application with the test accounts, you have two options:</p>
                
                <h6>Option 1: Using Artisan Commands (Recommended)</h6>
                <p>If you have Laravel properly configured:</p>
                
                <h6>Step 1: Run Migrations</h6>
                <pre class="bg-light p-3 rounded">php artisan migrate</pre>
                
                <h6>Step 2: Run Seeders</h6>
                <pre class="bg-light p-3 rounded">php artisan db:seed</pre>
                
                <p>Or run both together:</p>
                <pre class="bg-light p-3 rounded">php artisan migrate --seed</pre>
                
                <h6>Option 2: Manual SQL Import</h6>
                <p>If artisan commands are not available, you can manually execute the SQL commands:</p>
                <ol>
                    <li>Open your database manager (e.g., DB Browser for SQLite)</li>
                    <li>Connect to the <code>database/database.sqlite</code> file</li>
                    <li>Execute the SQL commands from the <code>insert_test_data.sql</code> file</li>
                </ol>
                
                <h6>Test Accounts Available:</h6>
                <ul>
                    <li>Username: <strong>peminjam1</strong>, Password: <strong>password</strong></li>
                    <li>Username: <strong>peminjam2</strong>, Password: <strong>password</strong></li>
                    <li>Username: <strong>admin</strong>, Password: <strong>password</strong></li>
                </ul>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    If you're using Laragon or similar local development environment, 
                    make sure PHP and Composer are properly configured in your system PATH.
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Go to Login</a>
                    <a href="/insert_test_data.sql" class="btn btn-outline-secondary" download>Download SQL File</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>