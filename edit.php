<?php
/**
 * Página para editar cliente existente
 * Carrega os dados do cliente e permite alteração
 */

// Incluir arquivo de conexão
require_once 'db.php';

$erro = '';
$sucesso = '';
$cliente = null;

// Verificar se foi passado um ID válido
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

// Buscar dados do cliente
try {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch();
    
    if (!$cliente) {
        $erro = 'Cliente não encontrado.';
    }
} catch(PDOException $e) {
    $erro = 'Erro ao buscar cliente: ' . $e->getMessage();
}

// Processar formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $cliente) {
    // Receber dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    
    // Validar dados
    if (empty($nome)) {
        $erro = 'O nome é obrigatório.';
    } elseif (empty($email)) {
        $erro = 'O email é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um email válido.';
    } elseif (empty($telefone)) {
        $erro = 'O telefone é obrigatório.';
    } else {
        // Verificar se email já existe (exceto para o cliente atual)
        try {
            $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            
            if ($stmt->fetch()) {
                $erro = 'Este email já está registrado por outro cliente.';
            } else {
                // Atualizar cliente
                $stmt = $pdo->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?");
                
                if ($stmt->execute([$nome, $email, $telefone, $id])) {
                    $sucesso = 'Cliente atualizado com sucesso!';
                    // Atualizar dados do cliente para mostrar no formulário
                    $cliente['nome'] = $nome;
                    $cliente['email'] = $email;
                    $cliente['telefone'] = $telefone;
                } else {
                    $erro = 'Erro ao atualizar cliente.';
                }
            }
        } catch(PDOException $e) {
            $erro = 'Erro na base de dados: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Sistema de Gestão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        
        .btn {
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .form-actions {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .client-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .client-info h3 {
            margin: 0 0 10px 0;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Cliente</h1>
        
        <!-- Mostrar mensagens de erro ou sucesso -->
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($cliente): ?>
            <!-- Informações do cliente -->
            <div class="client-info">
                <h3>Cliente ID: <?php echo htmlspecialchars($cliente['id']); ?></h3>
                <p>Editando os dados do cliente selecionado</p>
            </div>
            
            <!-- Formulário para editar cliente -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="<?php echo htmlspecialchars($cliente['nome']); ?>" 
                           required 
                           maxlength="100"
                           placeholder="Digite o nome completo do cliente">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo htmlspecialchars($cliente['email']); ?>" 
                           required 
                           maxlength="150"
                           placeholder="Digite o email do cliente">
                </div>
                
                <div class="form-group">
                    <label for="telefone">Telefone *</label>
                    <input type="tel" 
                           id="telefone" 
                           name="telefone" 
                           value="<?php echo htmlspecialchars($cliente['telefone']); ?>" 
                           required 
                           maxlength="20"
                           placeholder="Digite o telefone do cliente">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Atualizar Cliente</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                Cliente não encontrado ou erro ao carregar dados.
            </div>
            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">Voltar à Lista</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Voltar à lista de clientes</a>
    </div>
</body>
</html>
