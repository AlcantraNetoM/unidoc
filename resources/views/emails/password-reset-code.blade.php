<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Recuperação de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .code-box {
            background-color: #f8f9fa;
            border: 2px dashed #6c757d;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recuperação de Senha</h1>
        </div>
        
        <p>Olá, <strong>{{ $name }}</strong>!</p>
        
        <p>Você solicitou a recuperação de senha para sua conta. Para continuar, utilize o código de verificação abaixo:</p>
        
        <div class="code-box">
            <div class="code">{{ $code }}</div>
            <p style="margin: 10px 0 0 0; font-size: 14px; color: #666;">Código de Verificação</p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Importante:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Este código é válido por apenas {{ $expires_in }} minutos</li>
                <li>Use este código apenas se você solicitou a recuperação de senha</li>
                <li>Nunca compartilhe este código com outras pessoas</li>
            </ul>
        </div>
        
        <p>Digite este código na página de recuperação para continuar o processo de redefinição de sua senha.</p>
        
        <p>Se você não solicitou esta recuperação de senha, pode ignorar este email com segurança. Sua conta permanecerá protegida.</p>
        
        <p>Atenciosamente,<br>
        Equipe de Suporte</p>
        
        <div class="footer">
            <p>Este é um email automático, não responda a esta mensagem.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
