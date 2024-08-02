<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>To-Do List</title>
</head>
<?php
include 'connection.php';

?>
<body>
<div class="container mt-5">
    <h2 class="text-center">To-Do List</h2>
    <form class="form-horizontal">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" placeholder="name">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">Subject</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" placeholder="subject">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    <table class="table mt-4">
        <thead>
        <tr>
            <th>Name</th>
            <th>Subject</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="taskTableBody">
        </tbody>
    </table>
</div>

<!-- Edit Task Modal -->
<div class="modal" id="editTaskModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-name">Edit Task</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editForm" method="POST" action="update.php">
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-name">name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-subject">subject</label>
                        <textarea class="form-control" id="edit-subject" name="subject"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchTasks();

        document.getElementById('createForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('create.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                fetchTasks();
                this.reset();
            }).catch(error => console.error('Error:', error));
        });

        document.getElementById('editForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('update.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                fetchTasks();
                var modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                modal.hide();
            }).catch(error => console.error('Error:', error));
        });
    });

    function fetchTasks() {
        fetch('read.php')
            .then(response => response.json())
            .then(data => {
                var tbody = document.getElementById('taskTableBody');
                tbody.innerHTML = '';
                data.forEach(task => {
                    var tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${task.name}</td>
                        <td>${task.subject}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                            <button class="btn btn-info btn-sm" onclick="editTask(${task.id}, '${task.name}', '${task.subject}')">Edit</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function deleteTask(id) {
        var formData = new FormData();
        formData.append('id', id);

        fetch('delete.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text()).then(data => {
            fetchTasks();
        }).catch(error => console.error('Error:', error));
    }

    function editTask(id, name, subject) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-subject').value = subject;
        var modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        modal.show();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
