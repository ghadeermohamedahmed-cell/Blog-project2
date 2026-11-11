<?php
include_once "./config/database.php";

$post = null;
$posts = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare(
        "SELECT * FROM post WHERE id = ? AND status = ?"
    );

    $stmt->execute([$id, "published"]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header("Location: index.php");
        exit;
    }

    $stmt = $pdo->prepare("UPDATE `post` SET `views` = views + 1 WHERE id = ?");
    $stmt->execute([$id]);
    $post['views']++;
} else {

    $stmt = $pdo->prepare("SELECT * FROM `post` WHERE `status` = ? ORDER BY created_at DESC");
    $stmt->execute(["published"]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-dark bg-dark px-4">
        <a href="index.php" class="navbar-brand"> My Blog</a>

        <a href="./Admin/post.php" class="btn btn-outline-light btn-sm">Home</a>
    </nav>

    <div class="container mt-4">

        <?php if ($post !== null) { ?>

            <div class="row justify-content-center">

                <div class="col-md-8">

                    <h2><?= $post['titel'] ?></h2>

                    <p class="text-muted">
                        Posted on:
                        <?= date("F j, Y", strtotime($post['created_at'])) ?>

                        | Views:
                        <?= $post['views'] ?>
                    </p>

                    <?php if (!empty($post['image'])) { ?>

                        <img src="uploued/<?= $post['image'] ?>" class="img-fluid rounded mb-3" alt="Post Image" style="width: 100%;">
                    <?php } ?>
                    <p>
                        <?= $post['content'] ?>
                    </p>

                    <a href="index.php" class="btn btn-primary m-3">Back to Posts</a>

                </div>

            </div>

        <?php } else { ?>

            <h2 class="mb-4">Latest Posts</h2>

            <?php foreach ($posts as $post) { ?>

                <div class="row border-bottom pb-3 mb-3">

                    <div class="col-md-2">

                        <?php if (!empty($post['image'])) { ?>

                            <img src="uploued/<?= $post['image'] ?>" class="img-fluid rounded" alt="Post Image" style="width: 100%; height: 100px; object-fit: cover;">

                        <?php } ?>

                    </div>

                    <div class="col-md-10">

                        <h5><?= $post['titel'] ?></h5>

                        <small class="text-muted">
                            <?= date("F j, Y", strtotime($post['created_at'])) ?>
                        </small>

                        <p class="mt-2">
                            <?= substr($post['content'], 0, 180) ?>
                        </p>

                        <a href="index.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm">Read More</a>

                    </div>

                </div>

            <?php } ?>

        <?php } ?>

    </div>

    <footer class="bg-dark text-white text-center p-3 mt-auto">
        <p class="mb-0">&copy; 2025 My Blog</p>
    </footer>

</body>

</html>