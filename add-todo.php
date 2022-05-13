<?php
include "includes/config.php";
session_start();
if (!isset($_SESSION["user_email"])) {
    header("Location: index.php");
    die();
}

$msg = "";

if (isset($_POST["addTodo"])) {
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $desc = mysqli_real_escape_string($conn, $_POST["desc"]);
    $priority = mysqli_real_escape_string($conn, $_POST["priority"]);

    // Get User Id based on user email
    $sql = "SELECT id FROM users WHERE email='{$_SESSION["user_email"]}'";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
        $row = mysqli_fetch_assoc($res);
        $user_id = $row["id"];
    } else {
        $user_id = 0;
    }
    $sql = null;

    // Inserting todo
    $sql = "INSERT INTO todos (title, description, priority, user_id) VALUES ('$title', '$desc', '$priority', '$user_id')";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        $_POST["title"] = "";
        $_POST["desc"] = "";
        $_POST["priority"] = "";
        $msg = "<div class='alert alert-success'>To-Do is created. Add another one.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>To-Do is not created! Try again.</div>";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <?php getHead(); ?>
</head>

<body class="bg-light">
    <?php getHeader(); ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="card bg-white rounded border shadow">
                    <div class="card-header px-4 py-3">
                        <h4 class="card-title">Add To-Do</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php echo $msg; ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Create a To-Do" value="<?php if (isset($_POST["addTodo"])) {
                                                                                                                                                    echo $_POST["title"];
                                                                                                                                                } ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea class="form-control" id="desc" name="desc" rows="3"><?php if (isset($_POST["addTodo"])) {
                                                                                                            echo $_POST["title"];
                                                                                                        } ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Set Priority: </label>
                                <!-- <input type="text" class="form-control" id="priority" name="priority" placeholder="Type 1/0; 1 = High, 0 = Low" required> -->
                                <select name="priority" id="priority">
                                    <option value="High">High</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" name="addTodo" class="btn btn-primary me-2">Add To-Do</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</body>

</html>