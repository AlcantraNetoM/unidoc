# Configuração de Email para Recuperação de Senha

## Estado Atual
- ✅ Sistema funcionando com `MAIL_MAILER=log`
- ✅ Códigos sendo gerados e salvos no banco
- ✅ Validação funcionando corretamente
- ✅ Interface de debug disponível

## Para Envio de Email Real

### Opção 1: Gmail (Recomendado para teste)
Adicione no arquivo `.env`:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seuemail@gmail.com
MAIL_PASSWORD=suasenhaapp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seuemail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Importante:** Use uma "Senha de App" do Gmail, não sua senha normal.

### Opção 2: Mailtrap (Para desenvolvimento)
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seusite.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Opção 3: SendGrid
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sua_api_key_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seusite.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Como Testar

### Método 1: Com Email Real
1. Configure uma das opções acima no `.env`
2. Execute: `php artisan config:cache`
3. Teste no sistema normalmente

### Método 2: Modo Debug (Atual)
1. Use `MAIL_MAILER=log`
2. Acesse `/debug-codes` para ver códigos ativos
3. Ou verifique os logs: `tail -f storage/logs/laravel.log`

## URLs de Teste
- http://127.0.0.1:8000/forgot-password - Solicitar código
- http://127.0.0.1:8000/debug-codes - Ver códigos (modo debug)

## Usuário de Teste Criado
- Email: teste@example.com
- Telefone: 123456789
- Senha atual: password123
