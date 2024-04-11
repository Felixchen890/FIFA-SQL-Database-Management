<!DOCTYPE html>
<html>
  <head>
    <title>Football Team Database</title>
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-image: url('football.png');
        background-repeat: no-repeat;
        background-size: cover;
        color: white;
        font-size: 18px;
      }

      .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 100px;
      }

      button {
        width: 200px;
        height: 50px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin: 10px;
      }
    </style>
  </head>
  <body>
    <h1>Football Team Database</h1>
    <div class="button-container">
      <button onclick="location.href='displayTable.php'">Display Database</button>
      <button onclick="location.href='choice.php'">Manipulate Database</button>
      <button onclick="location.href='query.php'">Query</button>
    </div>
  </body>
</html>
