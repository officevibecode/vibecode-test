<?php
/**
 * Página para criar novo cliente
 * Formulário para inserir nome, email e telefone
 */

// Incluir arquivo de conexão
require_once 'db.php';

$erro = '';
$sucesso = '';

// Processar formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Verificar se email já existe
        try {
            $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $erro = 'Este email já está registrado.';
            } else {
                // Inserir novo cliente
                $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$nome, $email, $telefone])) {
                    $sucesso = 'Cliente criado com sucesso!';
                    // Limpar campos após sucesso
                    $nome = $email = $telefone = '';
                } else {
                    $erro = 'Erro ao criar cliente.';
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
    <title>Adicionar Cliente - Sistema de Gestão</title>
    <link rel="stylesheet" href="dark-theme.css">
    <style>
        /* Estilos específicos da página create */
        .container {
            max-width: 600px;
        }
        
        h1 {
            font-size: 2.4rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Novo Cliente</h1>
        
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
        
        <!-- Formulário para criar cliente -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" 
                       id="nome" 
                       name="nome" 
                       value="<?php echo htmlspecialchars($nome ?? ''); ?>" 
                       required 
                       maxlength="100"
                       placeholder="Digite o nome completo do cliente">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                       required 
                       maxlength="150"
                       placeholder="Digite o email do cliente">
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone *</label>
                <input type="tel" 
                       id="telefone" 
                       name="telefone" 
                       value="<?php echo htmlspecialchars($telefone ?? ''); ?>" 
                       required 
                       maxlength="20"
                       placeholder="Digite o telefone do cliente">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">✨ Criar Cliente</button>
                <a href="index.php" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
        
        <a href="index.php" class="back-link">← Voltar à lista de clientes</a>
    </div>
</body>
</html>
