<?php
/**
 * Página principal - Lista todos os clientes
 * Inclui botões para adicionar, editar e apagar clientes
 */

// Incluir arquivo de conexão
require_once 'db.php';

// Buscar todos os clientes da base de dados
try {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
    $clientes = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar clientes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Clientes</title>
    <link rel="stylesheet" href="dark-theme.css">
    <style>
        /* Estilos específicos da página index */
        .btn {
            padding: 10px 20px;
            font-size: 13px;
            min-width: 100px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestão de Clientes</h1>
        
        <!-- Botão para adicionar novo cliente -->
        <div class="add-button-container">
            <a href="create.php" class="btn btn-success">✨ Adicionar Novo Cliente</a>
        </div>
        
        <!-- Mostrar erro se houver -->
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <!-- Tabela de clientes -->
        <?php if (empty($clientes)): ?>
            <div class="no-data">
                <h3>Nenhum cliente encontrado</h3>
                <p>Clique no botão "Adicionar Novo Cliente" para começar.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning">✏️ Editar</a>
                                    <a href="delete.php?id=<?php echo $cliente['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja apagar este cliente?')">
                                       🗑️ Apagar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="stats">
                💼 Total de clientes: <?php echo count($clientes); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
