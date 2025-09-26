<?php
/**
 * Página para apagar cliente
 * Mostra confirmação e executa a exclusão
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

// Buscar dados do cliente para mostrar na confirmação
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

// Processar exclusão quando confirmada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar']) && $cliente) {
    try {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        
        if ($stmt->execute([$id])) {
            $sucesso = 'Cliente apagado com sucesso!';
            $cliente = null; // Limpar dados do cliente após exclusão
        } else {
            $erro = 'Erro ao apagar cliente.';
        }
    } catch(PDOException $e) {
        $erro = 'Erro na base de dados: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apagar Cliente - Sistema de Gestão</title>
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
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-primary {
            background-color: #007bff;
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
        
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
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
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .client-info h3 {
            margin: 0 0 15px 0;
            color: #495057;
        }
        
        .client-details {
            margin: 10px 0;
        }
        
        .client-details strong {
            color: #495057;
        }
        
        .warning-message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .warning-message h4 {
            margin: 0 0 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Apagar Cliente</h1>
        
        <!-- Mostrar mensagens de erro ou sucesso -->
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($sucesso); ?>
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary">Voltar à Lista de Clientes</a>
                </div>
            </div>
        <?php elseif ($cliente): ?>
            <!-- Aviso de confirmação -->
            <div class="warning-message">
                <h4>⚠️ Atenção!</h4>
                <p>Esta ação não pode ser desfeita. O cliente será permanentemente removido da base de dados.</p>
            </div>
            
            <!-- Informações do cliente a ser apagado -->
            <div class="client-info">
                <h3>Dados do Cliente a Ser Apagado:</h3>
                <div class="client-details">
                    <strong>ID:</strong> <?php echo htmlspecialchars($cliente['id']); ?>
                </div>
                <div class="client-details">
                    <strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome']); ?>
                </div>
                <div class="client-details">
                    <strong>Email:</strong> <?php echo htmlspecialchars($cliente['email']); ?>
                </div>
                <div class="client-details">
                    <strong>Telefone:</strong> <?php echo htmlspecialchars($cliente['telefone']); ?>
                </div>
                <?php if (isset($cliente['created_at'])): ?>
                <div class="client-details">
                    <strong>Registrado em:</strong> <?php echo date('d/m/Y H:i', strtotime($cliente['created_at'])); ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Formulário de confirmação -->
            <form method="POST" action="" onsubmit="return confirm('Tem CERTEZA ABSOLUTA que deseja apagar este cliente? Esta ação não pode ser desfeita!');">
                <div class="form-actions">
                    <button type="submit" name="confirmar" value="1" class="btn btn-danger">
                        🗑️ Confirmar Exclusão
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                Cliente não encontrado ou já foi removido.
            </div>
            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">Voltar à Lista</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Voltar à lista de clientes</a>
    </div>
    
    <script>
        // Adicionar confirmação extra via JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.querySelector('form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    const confirmed = confirm('ÚLTIMA CONFIRMAÇÃO: Apagar este cliente permanentemente?');
                    if (!confirmed) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>
