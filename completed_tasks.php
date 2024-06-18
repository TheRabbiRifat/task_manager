<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Tasks - Task Manager App</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Completed Tasks</h1>
        <ul id="completed-tasks-list">
            <!-- Task items will be dynamically added here -->
        </ul>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchCompletedTasks();

            function fetchCompletedTasks() {
                fetch('php/get_completed_tasks.php')
                    .then(response => response.json())
                    .then(tasks => {
                        const completedTasksList = document.getElementById('completed-tasks-list');
                        completedTasksList.innerHTML = '';

                        tasks.forEach(task => {
                            const taskItem = document.createElement('li');
                            taskItem.classList.add('task');
                            taskItem.innerHTML = `
                                <h3>${task.title}</h3>
                                <p>${task.description}</p>
                                <p><strong>Due Date:</strong> ${task.due_date}</p>
                                <p><strong>Priority:</strong> ${task.priority}</p>
                                <p><strong>Status:</strong> ${task.status}</p>
                                <p><strong>Assigned To:</strong> ${task.assigned_to_username}</p>
                                <p><strong>Assigned By:</strong> ${task.assigned_by_username}</p>
                            `;
                            completedTasksList.appendChild(taskItem);
                        });
                    })
                    .catch(error => console.error('Error fetching completed tasks:', error));
            }
        });
    </script>
</body>
</html>
