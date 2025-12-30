<?php

require_once 'User.php';
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'create':
                $data = [
                    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                    'first_name' => filter_input(INPUT_POST, 'first_name'),
                    'last_name' => filter_input(INPUT_POST, 'last_name'),
                    'age' => filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT)
                ];

                if (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $error = 'Некорректный email';
                } elseif (!$data['first_name'] || strlen($data['first_name']) < 2) {
                    $error = 'Имя должно содержать минимум 2 символа';
                } elseif (!$data['last_name'] || strlen($data['last_name']) < 2) {
                    $error = 'Фамилия должна содержать минимум 2 символа';
                } elseif (!$data['age'] || $data['age'] < 0 || $data['age'] > 150) {
                    $error = 'Возраст должен быть от 0 до 150 лет';
                } else {
                    $user->create($data);
                    header('Location: index.php');
                    exit();
                }
                break;
                
            case 'update':
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                $data = [
                    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                    'first_name' => filter_input(INPUT_POST, 'first_name'),
                    'last_name' => filter_input(INPUT_POST, 'last_name'),
                    'age' => filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT)
                ];

                if (!$id || $id < 1) {
                    $error = 'Некорректный ID пользователя';
                } elseif (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $error = 'Некорректный email';
                } elseif (!$data['first_name'] || strlen($data['first_name']) < 2) {
                    $error = 'Имя должно содержать минимум 2 символа';
                } elseif (!$data['last_name'] || strlen($data['last_name']) < 2) {
                    $error = 'Фамилия должна содержать минимум 2 символа';
                } elseif (!$data['age'] || $data['age'] < 0 || $data['age'] > 150) {
                    $error = 'Возраст должен быть от 0 до 150 лет';
                } else {
                    $user->update($id, $data);
                    header('Location: index.php');
                    exit();
                }
                break;
                
            case 'delete':
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                
                if (!$id || $id < 1) {
                    $error = 'Некорректный ID пользователя';
                } else {
                    $user->delete($id);
                    header('Location: index.php');
                    exit();
                }
                break;
        }
    }
}

$users = $user->list();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление пользователями</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button, input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .add-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #ffcdd2;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Управление пользователями</h1>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <h2>Список пользователей</h2>
        <?php if (empty($users)): ?>
            <p>Пользователей нет. Добавьте первого пользователя.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Возраст</th>
                        <th>Дата добавления</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $userData): ?>
                        <tr>
                            <td><?= htmlspecialchars($userData['id']) ?></td>
                            <td>
                                <form method="POST" action="index.php" style="margin: 0;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>">
                                    <input type="email" 
                                           name="email" 
                                           value="<?= htmlspecialchars($userData['email']) ?>" 
                                           required 
                                           style="width: 100%;">
                            </td>
                            <td>
                                <input type="text" 
                                       name="first_name" 
                                       value="<?= htmlspecialchars($userData['first_name']) ?>" 
                                       required 
                                       style="width: 100%;">
                            </td>
                            <td>
                                <input type="text" 
                                       name="last_name" 
                                       value="<?= htmlspecialchars($userData['last_name']) ?>" 
                                       required 
                                       style="width: 100%;">
                            </td>
                            <td>
                                <input type="number" 
                                       name="age" 
                                       value="<?= htmlspecialchars($userData['age']) ?>" 
                                       min="0" 
                                       max="150" 
                                       required 
                                       style="width: 100%;">
                            </td>
                            <td><?= htmlspecialchars($userData['date_created']) ?></td>
                            <td>
                                <button type="submit" 
                                        name="action" 
                                        value="update" 
                                        class="btn-edit" 
                                        style="margin-right: 5px;">
                                    Сохранить
                                </button>
                                </form>
                                
                                <form method="POST" action="index.php" style="display: inline; margin: 0;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" 
                                            class="btn-delete" 
                                            onclick="return confirm('Вы уверены, что хотите удалить пользователя?');">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="add-form">
            <h2>Добавить нового пользователя</h2>
            <form method="POST" action="index.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="first_name">Имя:</label>
                    <input type="text" id="first_name" name="first_name" required minlength="2">
                </div>
                <div class="form-group">
                    <label for="last_name">Фамилия:</label>
                    <input type="text" id="last_name" name="last_name" required minlength="2">
                </div>
                <div class="form-group">
                    <label for="age">Возраст:</label>
                    <input type="number" id="age" name="age" min="0" max="150" required>
                </div>
                <input type="hidden" name="action" value="create">
                <input type="submit" value="Добавить пользователя">
            </form>
        </div>
    </div>
</body>
</html>