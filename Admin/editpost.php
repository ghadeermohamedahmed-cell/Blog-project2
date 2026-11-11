<?php
include_once '../config/database.php';
include './include/header.php';


$titel = '';
$id = $_GET['id'];

if ($id) {

    $stmt = $pdo->prepare("SELECT * FROM `post` WHERE id = ?");
    $stmt->execute([$id]);

    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $titel = $post['titel'];
       
    }
}

if (isset($_POST['update_post'])) {

    $titel = $_POST['titel'];
    $id = $_POST['id'];

    $stmt = $pdo->prepare(
        "UPDATE `post` SET `titel` = ? WHERE `id` = ?"
    );

    $stmt->execute([$titel, $id]);

    header("Location: post.php");
   
}


?>

<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <div class="py-4">

        <h2 class="mb-3">Edit Post</h2>

        <div class="card">

            <div class="card-body">

                <form method="POST">

                    <input type="hidden" name="id" value="<?= $id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Post Title</label>

                        <input type="text" class="form-control"
                            name="titel"
                            value="<?= ($titel); ?>"
                            required>
                    </div>

                    <button type="submit" name="update_post"
                        class="btn btn-warning">
                        Update
                    </button>

                    <a href="post.php" class="btn btn-secondary">
                        Back
                    </a>

                </form>

            </div>
        </div>
    </div>
</div>