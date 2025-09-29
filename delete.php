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
    <link rel="stylesheet" href="dark-theme.css">
    <style>
        /* Estilos específicos da página delete */
        .container {
            max-width: 700px;
        }
        
        h1 {
            font-size: 2.4rem;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🗑️ Apagar Cliente</h1>
        
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
                    <a href="index.php" class="btn btn-primary">Voltar à Lista</a>
                </div>
            </div>
        <?php elseif ($cliente): ?>
            <div class="warning-message">
                <h4>⚠️ Atenção!</h4>
                <p>Esta ação é irreversível. O cliente será permanentemente removido da base de dados.</p>
            </div>
            
            <div class="client-info">
                <h3>Dados do Cliente a ser Apagado:</h3>
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
                <div class="client-details">
                    <strong>Registrado em:</strong> <?php echo date('d/m/Y às H:i', strtotime($cliente['created_at'])); ?>
                </div>
            </div>
            
            <form method="POST" action="">
                <div class="form-actions">
                    <button type="submit" name="confirmar" class="btn btn-danger" 
                            onclick="return confirm('Tem certeza absoluta que deseja apagar este cliente? Esta ação não pode ser desfeita!')">
                        🗑️ Confirmar Exclusão
                    </button>
                    <a href="index.php" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                Cliente não encontrado.
            </div>
            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">Voltar à Lista</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Voltar à lista de clientes</a>
    </div>
</body>
</html>
