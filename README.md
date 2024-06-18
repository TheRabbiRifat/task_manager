# To-Do List Application

A simple To-Do List web application with PHP, MySQL, and JavaScript.

## Table of Contents
- [About](#about)
- [Features](#features)
- [Demo](#demo)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## About

This is a basic To-Do List web application where users can add tasks, mark them as completed, view all tasks, and view completed tasks separately. Tasks are stored in a MySQL database and managed using PHP for the backend and JavaScript for the frontend.

## Features

- Add new tasks
- Mark tasks as completed
- Delete tasks
- View all tasks
- View completed tasks

## Technologies Used

- PHP
- MySQL
- JavaScript
- HTML/CSS

## Setup Instructions

To run this project locally, follow these steps:

1. -Clone the repository:**

   ```bash
   git clone https://github.com/TheRabbiRifat/task_manager.git
   cd task_manager

2.  -Set up the database:

Create a MySQL database named task_manager.
Import the task_manager.sql file provided in the repository to set up nessesary tables.
Configure the database connection:
Update the database connection details in php/config.php with your MySQL database credentials. 

## Usage
-Adding a task:
Enter a task in the input field and click "Add Task".

-Completing a task:
Click "Complete" next to the task to mark it as completed.

-Viewing completed tasks:
Click "View Completed Tasks" to see all completed tasks.

-Viewing pending tasks:
Click "View pending Tasks" to see all pending tasks.

-Deleting a task:
Click "Delete" next to the task to remove it from the list.

## Contributing
Contributions are welcome! If you have any suggestions, improvements, or issues, feel free to open an issue or create a pull request.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
