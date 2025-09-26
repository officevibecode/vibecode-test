# Sistema de Gestão de Clientes - PHP/MySQL

Sistema CRUD simples para gestão de clientes desenvolvido em PHP com MySQL.

## 📋 Características

- **CRUD Completo**: Criar, Ler, Atualizar e Apagar clientes
- **Interface Responsiva**: Design limpo e moderno com CSS inline
- **Validação de Dados**: Validação tanto no frontend quanto no backend
- **Segurança**: Uso de prepared statements para prevenir SQL injection
- **Confirmações**: Confirmação dupla para exclusão de registros

## 🗂️ Estrutura de Arquivos

```
vibecode-test/
├── db.php          # Conexão com a base de dados
├── index.php       # Lista de clientes (página principal)
├── create.php      # Formulário para criar novo cliente
├── edit.php        # Formulário para editar cliente existente
├── delete.php      # Página de confirmação e exclusão
└── README.md       # Este arquivo
```

## 🗄️ Base de Dados

### Configuração MySQL
- **Host**: 185.187.169.13
- **Utilizador**: admin_vibecode
- **Senha**: admin_vibecode
- **Base de Dados**: admin_vibecode

### Estrutura da Tabela `clientes`

```sql
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Nota**: A tabela é criada automaticamente quando o sistema é executado pela primeira vez.

## 🚀 Como Usar

### 1. Configuração
- Certifique-se de que o servidor web (Apache/Nginx) está configurado para executar PHP
- Verifique se a extensão PDO MySQL está ativada no PHP
- Coloque todos os arquivos no diretório do servidor web

### 2. Acesso ao Sistema
- Acesse `index.php` no seu navegador
- Esta é a página principal que lista todos os clientes

### 3. Operações Disponíveis

#### ➕ **Adicionar Cliente**
- Clique no botão "Adicionar Novo Cliente" na página principal
- Preencha o formulário com nome, email e telefone
- Clique em "Criar Cliente"

#### ✏️ **Editar Cliente**
- Na lista de clientes, clique no botão "Editar" do cliente desejado
- Modifique os dados no formulário
- Clique em "Atualizar Cliente"

#### 🗑️ **Apagar Cliente**
- Na lista de clientes, clique no botão "Apagar" do cliente desejado
- Confirme a exclusão na página de confirmação
- **Atenção**: Esta ação não pode ser desfeita!

#### 📋 **Listar Clientes**
- A página principal (`index.php`) mostra todos os clientes
- Os clientes são ordenados alfabeticamente por nome
- Mostra ID, nome, email, telefone e ações disponíveis

## 🔒 Funcionalidades de Segurança

- **Prepared Statements**: Proteção contra SQL injection
- **Validação de Email**: Verificação de formato válido
- **Sanitização de Dados**: Uso de `htmlspecialchars()` para prevenir XSS
- **Verificação de Duplicatas**: Impede emails duplicados
- **Confirmação de Exclusão**: Dupla confirmação antes de apagar

## 🎨 Interface

- **Design Responsivo**: Funciona em desktop e mobile
- **CSS Inline**: Estilo incorporado para facilidade de deployment
- **Cores Intuitivas**: 
  - Azul para ações principais
  - Verde para criar/adicionar
  - Amarelo para editar
  - Vermelho para apagar
- **Feedback Visual**: Mensagens de sucesso e erro claras

## 📝 Validações Implementadas

### Frontend (HTML5)
- Campos obrigatórios
- Validação de email
- Limites de caracteres

### Backend (PHP)
- Verificação de campos vazios
- Validação de formato de email
- Verificação de emails duplicados
- Sanitização de dados de entrada

## 🔧 Requisitos Técnicos

- **PHP**: 7.0 ou superior
- **MySQL**: 5.6 ou superior
- **Extensões PHP**: PDO, PDO_MySQL
- **Servidor Web**: Apache ou Nginx

## 🚨 Notas Importantes

1. **Sem Autenticação**: O sistema é público, sem login necessário
2. **Dados Sensíveis**: As credenciais da base de dados estão no código (apenas para demonstração)
3. **Produção**: Para uso em produção, mova as credenciais para variáveis de ambiente
4. **Backup**: Faça backup regular da base de dados

## 🐛 Resolução de Problemas

### Erro de Conexão
- Verifique se as credenciais da base de dados estão corretas
- Confirme se o servidor MySQL está acessível
- Verifique se a extensão PDO_MySQL está ativada

### Página em Branco
- Ative a exibição de erros PHP: `error_reporting(E_ALL)`
- Verifique os logs do servidor web
- Confirme se todos os arquivos estão no local correto

### Problemas de Codificação
- Certifique-se de que os arquivos estão salvos em UTF-8
- Verifique se o charset da base de dados está configurado para UTF-8

## 📞 Suporte

Para dúvidas ou problemas, verifique:
1. Os logs de erro do PHP
2. Os logs do servidor web
3. A conectividade com a base de dados
4. As permissões dos arquivos

---

**Desenvolvido para demonstração de sistema CRUD básico em PHP/MySQL**
