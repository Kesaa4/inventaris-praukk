<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Terjadi Kesalahan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .error-container {
            background: white;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 { 
            color: #667eea; 
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }
        p { 
            color: #666; 
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 14px 40px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 16px;
        }
        .btn-home:hover { 
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .error-code {
            color: #999;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Oops! Terjadi Kesalahan</h1>
        <p>Maaf, terjadi kesalahan pada sistem. Tim kami telah diberitahu dan sedang memperbaikinya.</p>
        <p>Silakan coba lagi dalam beberapa saat atau hubungi administrator jika masalah berlanjut.</p>
        <a href="<?= base_url() ?>" class="btn-home">Kembali ke Beranda</a>
        <div class="error-code">Error Code: 500 - Internal Server Error</div>
    </div>
</body>
</html>
