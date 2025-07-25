<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Users Table</title>
  <style>
    table {
      width: 80%;
      border-collapse: collapse;
      margin: 20px auto;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ccc;
    }
    th {
      background-color: #f2f2f2;
    }
    .action-btn {
      padding: 5px 10px;
      margin-right: 5px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
    .view-btn { background-color: #3498db; color: white; }
    .edit-btn { background-color: #f39c12; color: white; }
    .delete-btn { background-color: #e74c3c; color: white; }
  </style>
</head>
<body>

  <h2 style="text-align: center;">Users Table</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Example user row -->
      <tr>
        <td>1</td>
        <td>Alice Johnson</td>
        <td>alice@example.com</td>
        <td>
          <button class="action-btn view-btn" onclick="viewUser(1)">View</button>
          <button class="action-btn edit-btn" onclick="editUser(1)">Edit</button>
          <button class="action-btn delete-btn" onclick="deleteUser(1)">Delete</button>
        </td>
      </tr>
      <!-- Add more rows as needed -->
    </tbody>
  </table>

  <script>
    function viewUser(id) {
      alert("View user with ID: " + id);
      // Implement actual view logic or navigation here
    }

    function editUser(id) {
      alert("Edit user with ID: " + id);
      // Implement actual edit logic or navigation here
    }

    function deleteUser(id) {
      if (confirm("Are you sure you want to delete user ID " + id + "?")) {
        alert("Deleted user with ID: " + id);
        // Implement actual delete logic here
      }
    }
  </script>

</body>
</html>
