<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Tasks - Task Manager App</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* CSS for top buttons and task buttons */
        .btn, .btn-complete, .btn-delete {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            background-color: #dc3545; /* Pink color */
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }

        .btn:hover, .btn-complete:hover, .btn-delete:hover {
            background-color: #c82333; /* Darker pink color on hover */
        }

        /* CSS for tasks */
        .tasks {
            margin-top: 20px;
        }

        .tasks h3 {
            margin-bottom: 10px;
        }

        .tasks ul {
            list-style-type: none;
            padding: 0;
        }

        .tasks li {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pending Tasks</h1>
        <a href="dashboard.php" class="btn">Dashboard</a>
        <a href="profile.php" class="btn">My Profile</a>
        <a href="php/logout.php" class="btn">Logout</a>

        <div class="tasks">
            <h3>Pending Tasks</h3>
            <ul id="pending-tasks-list">
                <!-- Fetched pending tasks will be inserted here -->
            </ul>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchPendingTasks();

            function fetchPendingTasks() {
                fetch('php/get_pending_tasks.php')
                    .then(response => response.json())
                    .then(tasks => {
                        const pendingTasksList = document.getElementById('pending-tasks-list');
                        pendingTasksList.innerHTML = '';

                        tasks.forEach(task => {
                            const taskItem = document.createElement('li');
                            taskItem.classList.add('task');
                            taskItem.innerHTML = `
                                <h3>${task.title}</h3>
                                <p>${task.description}</p>
                                <p><strong>Due Date:</strong> ${task.due_date}</p>
                                <p><strong>Priority:</strong> ${task.priority}</p>
                                <p><strong>Assigned To:</strong> ${task.assigned_to_username}</p>
                                <p><strong>Assigned By:</strong> ${task.assigned_by_username}</p>
                                <p><strong>Assigned Time:</strong> ${task.assigned_time}</p>
                                <button class="btn-complete" data-task-id="${task.id}">Complete</button>
                                <button class="btn-delete" data-task-id="${task.id}">Delete</button>
                            `;
                            pendingTasksList.appendChild(taskItem);

                            // Add event listeners for complete and delete buttons
                            taskItem.querySelector('.btn-complete').addEventListener('click', function () {
                                completeTask(task.id);
                            });

                            taskItem.querySelector('.btn-delete').addEventListener('click', function () {
                                deleteTask(task.id);
                            });
                        });
                    })
                    .catch(error => console.error('Error fetching pending tasks:', error));
            }

            function completeTask(taskId) {
                fetch('php/complete_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}`,
                })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Error completing task.');
                })
                .then(data => {
                    console.log(data);
                    fetchPendingTasks(); // Refresh the pending task list
                })
                .catch(error => console.error('Error completing task:', error));
            }

            function deleteTask(taskId) {
                fetch('php/delete_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}`,
                })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Error deleting task.');
                })
                .then(data => {
                    console.log(data);
                    fetchPendingTasks(); // Refresh the pending task list
                })
                .catch(error => console.error('Error deleting task:', error));
            }
        });
    </script>
</body>
</html>
