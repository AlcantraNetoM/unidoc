# UNIDOC Mobile API Documentation

## 🚀 API está funcionando!

A API móvel foi implementada com sucesso e está rodando em:
- **Base URL**: `http://localhost:8000/api/v1`
- **Status**: ✅ Online e funcionando

## 📋 Endpoints Disponíveis

### 🔐 Autenticação

#### POST `/api/v1/login`
Fazer login e obter token de acesso.

**Request:**
```json
{
    "email": "usuario@email.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login realizado com sucesso",
    "data": {
        "user": {
            "id": 1,
            "name": "Nome do Usuário",
            "email": "usuario@email.com",
            "role": "admin",
            "empresa": {
                "id": 1,
                "nome": "Nome da Empresa"
            }
        },
        "token": "1|laravel_sanctum_token...",
        "token_type": "Bearer"
    },
    "timestamp": "2025-06-30T20:54:17.837022Z"
}
```

#### POST `/api/v1/logout`
Fazer logout e invalidar token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Logout realizado com sucesso",
    "timestamp": "2025-06-30T20:54:17.837022Z"
}
```

#### GET `/api/v1/me`
Obter dados do usuário autenticado.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Nome do Usuário",
            "email": "usuario@email.com",
            "role": "admin"
        }
    }
}
```

### 📊 Dashboard

#### GET `/api/v1/dashboard`
Obter dados do dashboard com estatísticas.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_files": 150,
            "storage_used": 1073741824,
            "categories_count": 10
        },
        "recent_files": [...],
        "user": {...}
    }
}
```

### 📁 Gestão de Arquivos

#### GET `/api/v1/files`
Listar arquivos do usuário com paginação.

**Headers:**
```
Authorization: Bearer {token}
```

**Parâmetros opcionais:**
- `per_page`: Número de itens por página (padrão: 15)
- `search`: Buscar por nome do arquivo
- `category_id`: Filtrar por categoria
- `subcategory_id`: Filtrar por subcategoria

**Exemplo:**
```
GET /api/v1/files?per_page=20&search=documento&category_id=1
```

**Response:**
```json
{
    "success": true,
    "data": {
        "files": [
            {
                "id": 1,
                "original_name": "documento.pdf",
                "file_size": 1024000,
                "created_at": "2025-06-30T10:00:00.000000Z",
                "category": {
                    "id": 1,
                    "name": "Documentos"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "total_pages": 5,
            "per_page": 15,
            "total": 75
        }
    }
}
```

#### POST `/api/v1/files`
Upload de arquivo(s).

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request (form-data):**
- `files[]`: Arquivo(s) para upload (required)
- `category_id`: ID da categoria (opcional)
- `subcategory_id`: ID da subcategoria (opcional)
- `tags`: Tags do arquivo (opcional)

**Response:**
```json
{
    "success": true,
    "message": "Arquivo(s) enviado(s) com sucesso",
    "data": {
        "files": [...]
    }
}
```

#### GET `/api/v1/files/{id}/download`
Download de arquivo.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** Arquivo binário para download

#### DELETE `/api/v1/files/{id}`
Excluir arquivo.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Arquivo excluído com sucesso"
}
```

### 🗂️ Categorias

#### GET `/api/v1/categories`
Listar todas as categorias com subcategorias.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Documentos",
                "subcategories": [
                    {
                        "id": 1,
                        "name": "Contratos"
                    }
                ]
            }
        ]
    }
}
```

#### GET `/api/v1/categories/{id}/subcategories`
Obter subcategorias de uma categoria específica.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "subcategories": [
            {
                "id": 1,
                "name": "Contratos",
                "category_id": 1
            }
        ]
    }
}
```

### 👤 Perfil do Usuário

#### PUT `/api/v1/profile`
Atualizar perfil do usuário.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "name": "Novo Nome",
    "email": "novo@email.com",
    "phone": "+351987654321",
    "password": "nova_password",
    "password_confirmation": "nova_password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Perfil atualizado com sucesso",
    "data": {
        "user": {...}
    }
}
```

## 🔒 Autenticação

A API usa **Laravel Sanctum** com tokens Bearer. Para todas as rotas protegidas, inclua o header:

```
Authorization: Bearer {seu_token_aqui}
```

## 📱 Exemplo de uso em Flutter

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api/v1';
  String? _token;

  // Login
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    final data = jsonDecode(response.body);
    if (data['success']) {
      _token = data['data']['token'];
    }
    return data;
  }

  // Obter arquivos
  Future<Map<String, dynamic>> getFiles({
    int page = 1,
    String? search,
    int? categoryId,
  }) async {
    var url = '$baseUrl/files?page=$page';
    if (search != null) url += '&search=$search';
    if (categoryId != null) url += '&category_id=$categoryId';

    final response = await http.get(
      Uri.parse(url),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $_token',
      },
    );

    return jsonDecode(response.body);
  }

  // Upload de arquivo
  Future<Map<String, dynamic>> uploadFile(
    List<String> filePaths, {
    int? categoryId,
    String? tags,
  }) async {
    var request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/files'),
    );

    request.headers.addAll({
      'Authorization': 'Bearer $_token',
      'Accept': 'application/json',
    });

    // Adicionar arquivos
    for (String filePath in filePaths) {
      request.files.add(
        await http.MultipartFile.fromPath('files[]', filePath),
      );
    }

    // Adicionar campos opcionais
    if (categoryId != null) {
      request.fields['category_id'] = categoryId.toString();
    }
    if (tags != null) {
      request.fields['tags'] = tags;
    }

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    return jsonDecode(response.body);
  }

  // Obter dashboard
  Future<Map<String, dynamic>> getDashboard() async {
    final response = await http.get(
      Uri.parse('$baseUrl/dashboard'),
      headers: {
        'Authorization': 'Bearer $_token',
        'Accept': 'application/json',
      },
    );

    return jsonDecode(response.body);
  }
}
```

## 🧪 Como testar a API

### 1. Usando cURL

```bash
# Testar informações da app
curl "http://localhost:8000/api/v1/app-info"

# Fazer login
curl -X POST "http://localhost:8000/api/v1/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"seu@email.com","password":"sua_senha"}'

# Listar arquivos (substitua YOUR_TOKEN)
curl "http://localhost:8000/api/v1/files" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 2. Usando Postman

1. Importe a collection com base URL: `http://localhost:8000/api/v1`
2. Configure o token no Authorization: Bearer Token
3. Teste todos os endpoints

## 🚨 Códigos de Status HTTP

- `200`: Sucesso
- `201`: Criado com sucesso
- `400`: Dados inválidos
- `401`: Não autenticado
- `403`: Acesso negado (conta não aprovada ou trial expirado)
- `404`: Não encontrado
- `422`: Erro de validação
- `500`: Erro interno do servidor

## 🎯 Recursos Implementados

✅ **Autenticação segura** com Laravel Sanctum  
✅ **CRUD completo de arquivos** (upload, download, listagem, exclusão)  
✅ **Sistema de categorias** e subcategorias  
✅ **Dashboard** com estatísticas  
✅ **Perfil do usuário** editável  
✅ **Paginação** eficiente  
✅ **Filtros de busca** por nome e categoria  
✅ **Validação** robusta de dados  
✅ **Respostas padronizadas** em JSON  
✅ **Tratamento de erros** completo  
✅ **Verificação de permissões** por empresa/usuário  
✅ **Controle de trial** e aprovação de conta  

A API está **100% funcional** e pronta para ser consumida pelo seu aplicativo móvel! 🚀
