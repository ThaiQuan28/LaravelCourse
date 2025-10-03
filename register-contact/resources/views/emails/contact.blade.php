<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject ?? '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .contact-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 {{ $emailSubject ?? 'Tin nhắn từ Contact Management' }}</h1>
        <p>Tin nhắn từ hệ thống quản lý Contact</p>
    </div>
    
    <div class="content">
        <div class="contact-info">
            <h3>👤 Thông tin Contact</h3>
            <p><strong>Tên:</strong> {{ $contactName ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $contactEmail ?? 'N/A' }}</p>
            @if($contactPhone)
                <p><strong>Số điện thoại:</strong> {{ $contactPhone }}</p>
            @endif
            @if($contactAddress)
                <p><strong>Địa chỉ:</strong> {{ $contactAddress }}</p>
            @endif
        </div>
        
        <div class="message-box">
            <h3>💬 Nội dung tin nhắn</h3>
            <p>{{ $emailMessage ?? 'Không có nội dung' }}</p>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ url('/contacts') }}" class="btn">Hellooooo</a>
        </div>
    </div>
    
    <div class="footer">
        <p>Email này được gửi từ hệ thống của tôi</p>
        <p>Thời gian: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
