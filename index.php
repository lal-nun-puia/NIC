<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to News7</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                  url('https://source.unsplash.com/1600x900/?news,world') no-repeat center center fixed;
      background-size: cover;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
    }
    .btn-custom {
      width: 200px;
      margin: 10px;
      font-size: 18px;
    }
    h1 {
      font-size: 50px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h1>Welcome to News7</h1>
  <p class="lead">What would you like to do?</p>

  <div>
    <a href="register.php" class="btn btn-success btn-custom">Register</a>
    <a href="login.php" class="btn btn-primary btn-custom">Login</a>
  </div>

</body>
</html>