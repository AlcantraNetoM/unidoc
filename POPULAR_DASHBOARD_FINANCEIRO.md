# 📊 Instruções para Popular o Dashboard Financeiro

## 🎯 Objetivo
Este guia irá ajudá-lo a popular o banco de dados com dados de exemplo para que todos os gráficos e métricas do dashboard financeiro funcionem perfeitamente.

## ⚡ Execução Rápida

### Opção 1: Executar apenas o seeder financeiro
```bash
cd /Users/vambertvan-dunem/Documents/Prova\ bakcup\ final\ v4./prova
php artisan db:seed --class=FinancialDashboardSeeder
```

### Opção 2: Executar todos os seeders (incluindo o financeiro)
```bash
cd /Users/vambertvan-dunem/Documents/Prova\ bakcup\ final\ v4./prova
php artisan db:seed
```

### Opção 3: Reset completo do banco (CUIDADO: apaga todos os dados)
```bash
cd /Users/vambertvan-dunem/Documents/Prova\ bakcup\ final\ v4./prova
php artisan migrate:fresh --seed
```

## 📊 O que será criado

### 🏢 Empresas (5 unidades)
- **TechSol Angola** - Conta ativa, 12 meses pagos
- **ProBusiness Lda** - Conta ativa, 8 meses pagos  
- **InovaCorp** - Conta ativa, 6 meses pagos
- **DataFlow Solutions** - Em período de teste
- **NextGen Angola** - Conta expirada, aguardando pagamento

### 👤 Usuários Pessoais (6 unidades)
- **João Silva** - Conta ativa, 8 meses pagos
- **Maria Santos** - Conta ativa, 5 meses pagos
- **Carlos Mendes** - Conta ativa, 3 meses pagos
- **Ana Costa** - Em período de teste
- **Pedro Rocha** - Conta expirada
- **Luísa Fernandes** - Conta ativa, 10 meses pagos

### 💳 Pagamentos (30+ registros)
- ✅ **Pagamentos Aprovados**: Histórico completo para todas as contas ativas
- ⏳ **Pagamentos Pendentes**: 5+ pagamentos aguardando aprovação
- ❌ **Pagamentos Rejeitados**: 3 pagamentos rejeitados com motivos

### 🔔 Notificações (6+ registros)
- Notificações de pagamentos aprovados
- Avisos de expiração de conta
- Alertas diversos do sistema

## 🎯 Resultados Esperados

Após executar o seeder, o dashboard financeiro em `/super-admin/financial-dashboard` terá:

### 📈 Métricas Principais
- **Receita Total**: ~450.000 Kz
- **Pagamentos Este Mês**: 5-8 pagamentos
- **Contas Ativas**: 11 contas
- **Taxa de Crescimento**: Dados dos últimos 12 meses

### 📊 Gráficos Funcionais
- **Receita por Mês**: Gráfico de barras com dados dos últimos 12 meses
- **Pagamentos por Status**: Gráfico de pizza (aprovados/pendentes/rejeitados)
- **Crescimento de Usuários**: Linha temporal de crescimento
- **Distribuição por Tipo de Conta**: Personal vs Empresarial

### 📋 Tabelas Populadas
- **Pagamentos Recentes**: 10 pagamentos mais recentes
- **Contas em Expiração**: Contas próximas do vencimento
- **Análise de Retenção**: Dados de retenção e churn

## 🔍 Verificação

Para verificar se os dados foram criados corretamente:

```bash
# Verificar pagamentos criados
php artisan tinker
>>> App\Models\Payment::count()
>>> App\Models\Payment::where('status', 'approved')->count()
>>> App\Models\Payment::where('status', 'pending')->count()

# Verificar usuários e empresas
>>> App\Models\User::where('role', 'personal_user')->count()
>>> App\Models\Empresa::count()

# Verificar receita total
>>> App\Models\Payment::where('status', 'approved')->sum('amount')
```

## 🚀 Acesso ao Dashboard

Após executar o seeder:
1. Faça login como super admin
2. Acesse: `/super-admin/financial-dashboard`
3. Todos os gráficos e métricas estarão populados!

## ⚠️ Observações Importantes

- Os dados criados são **apenas para demonstração**
- Os comprovanivos de pagamento são caminhos fictícios
- Valores em **Kwanzas (Kz)**: Personal = 5.000 Kz/mês, Empresarial = 15.000 Kz/mês
- Os usuários criados têm senha padrão: `password`

## 🔧 Solução de Problemas

### Erro de Foreign Key
Se houver erro de chave estrangeira, primeiro crie um super admin:
```bash
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=FinancialDashboardSeeder
```

### Tabelas não existem
Execute as migrações primeiro:
```bash
php artisan migrate
php artisan db:seed --class=FinancialDashboardSeeder
```

---

**🎉 Pronto!** Agora você pode testar completamente o dashboard financeiro com dados realistas!
