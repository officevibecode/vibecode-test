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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            padding: 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="20" cy="20" r="2" fill="url(%23a)"/><circle cx="80" cy="40" r="1.5" fill="url(%23a)"/><circle cx="40" cy="80" r="1" fill="url(%23a)"/><circle cx="90" cy="90" r="2.5" fill="url(%23a)"/><circle cx="10" cy="60" r="1.2" fill="url(%23a)"/></svg>') repeat;
            opacity: 0.3;
            pointer-events: none;
            z-index: -1;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        }
        
        h1 {
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.2rem;
            font-weight: 300;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.5px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn {
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 12px;
            display: inline-block;
            margin: 8px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: rgba(0, 123, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-secondary {
            background: rgba(108, 117, 125, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .alert {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .form-actions {
            text-align: center;
            margin-top: 40px;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
        
        .client-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .client-info h3 {
            margin: 0 0 10px 0;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }
        
        .client-info p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            input[type="text"],
            input[type="email"],
            input[type="tel"] {
                padding: 14px 16px;
            }
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
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Atualizar Cliente</button>
                    <a href="index.php" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                Cliente não encontrado ou erro ao carregar dados.
            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">Voltar à Lista</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Voltar à lista de clientes</a>
    </div>
</body>
</html>
