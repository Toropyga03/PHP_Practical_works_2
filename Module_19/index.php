<?php

require_once 'User.php';
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $data = [
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'age' => $_POST['age']
        ];
        
        $user->create($data);
        header('Location: index.php');
        exit();
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'];
        $data = [
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'age' => $_POST['age']
        ];
        
        $user->update($id, $data);
        header('Location: index.php');
        exit();
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $user->delete($id);
        header('Location: index.php');
        exit();
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .table-form {
            display: contents;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Управление пользователями</h1>
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

                        <form method="POST" action="index.php" class="table-form">
                            <tr>
                                <td><?php echo htmlspecialchars($userData['id']); ?></td>
                                <td>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" min="0" max="150" required>
                                </td>
                                <td><?php echo htmlspecialchars($userData['date_created']); ?></td>
                                <td class="action-buttons">
                                    <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
                                    <input type="hidden" name="action" value="update">
                                    <button type="submit" class="btn-edit">Сохранить</button>
                                    <form method="POST" action="index.php" 
                                          onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');"
                                          style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn-delete">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        </form>
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
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Фамилия:</label>
                    <input type="text" id="last_name" name="last_name" required>
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