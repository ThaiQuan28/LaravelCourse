<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Contact Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-address-book"></i> Contact Management System
                    </h1>
                    <p class="lead mb-4">
                        Hệ thống quản lý contact hiện đại với Laravel, sử dụng Repository và Service pattern.
                        Quản lý thông tin liên hệ một cách dễ dàng và hiệu quả.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/contacts" class="btn btn-light btn-lg">
                            <i class="fas fa-rocket"></i> Bắt đầu ngay
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle"></i> Tìm hiểu thêm
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-users fa-10x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

   

    <!-- Call to Action -->
    <div class="py-5">
        <div class="container text-center">      
            <a href="/contacts" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right"></i> Quản lý Contacts
            </a>
        </div>
    </div>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>