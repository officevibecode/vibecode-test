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
            max-width: 700px;
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
        
        .btn-danger {
            background: rgba(220, 53, 69, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-secondary {
            background: rgba(108, 117, 125, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-primary {
            background: rgba(0, 123, 255, 0.2);
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
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
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
            background: rgba(220, 53, 69, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid rgba(220, 53, 69, 0.3);
            margin-bottom: 30px;
            position: relative;
        }
        
        .client-info::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, rgba(220, 53, 69, 0.8), rgba(220, 53, 69, 0.4));
            border-radius: 0 0 0 12px;
        }
        
        .client-info h3 {
            margin: 0 0 15px 0;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }
        
        .client-details {
            margin: 12px 0;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .client-details strong {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .warning-message {
            background: rgba(255, 193, 7, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .warning-message h4 {
            margin: 0 0 15px 0;
            font-size: 1.2rem;
            font-weight: 400;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .btn {
                padding: 14px 24px;
                font-size: 14px;
            }
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
