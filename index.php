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
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
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
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .actions {
            white-space: nowrap;
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
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestão de Clientes</h1>
        
        <!-- Botão para adicionar novo cliente -->
        <div style="margin-bottom: 20px;">
            <a href="create.php" class="btn btn-success">+ Adicionar Novo Cliente</a>
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
                                <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="delete.php?id=<?php echo $cliente['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja apagar este cliente?')">
                                   Apagar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: center; color: #666;">
                Total de clientes: <?php echo count($clientes); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
