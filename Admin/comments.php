
<?php
include_once '../config/database.php';

$posts = $pdo->query("SELECT `id`, `titel` FROM `post`")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['add_comment'])) {

    $stmt = $pdo->prepare("INSERT INTO `comment` (`post_id`, `name`, `email`, `comment`, `status`) VALUES (?, ?, ?, ?, 'pending')");

    $stmt->execute([
        $_POST['post_id'],
        $_POST['name'],
        $_POST['email'],
        $_POST['comment']
    ]);

    header("Location: comments.php");
    exit;
}

if (isset($_GET['approve'])) {

    $id = $_GET['approve'];

    $stmt = $pdo->prepare("UPDATE `comment` SET `status` = 'approved' WHERE id = ?");
    $stmt->execute([$id]);

   
}

if (isset($_GET['reject'])) {

    $id = $_GET['reject'];

    $stmt = $pdo->prepare("UPDATE `comment` SET `status` = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
}

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM `comment` WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: comments.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM `comment` ORDER BY created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = $pdo->query("SELECT COUNT(*) FROM `comment`")->fetchColumn();

$pending = $pdo->query("SELECT COUNT(*) FROM `comment` WHERE `status` = 'pending'")->fetchColumn();

$approved = $pdo->query("SELECT COUNT(*) FROM `comment` WHERE `status` = 'approved'")->fetchColumn();

$rejected = $pdo->query("SELECT COUNT(*) FROM `comment` WHERE `status` = 'rejected'")->fetchColumn();

include '../Admin/include/header.php';
?>

<div class="row mt-4">

    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h4><?= $total ?></h4>
                <p>Total Comments</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h4><?= $pending ?></h4>
                <p>Pending</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h4><?= $approved ?></h4>
                <p>Approved</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h4><?= $rejected ?></h4>
                <p>Rejected</p>
            </div>
        </div>
    </div>

</div>

<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <div class="py-4">

        <h1 class="h2 mb-3">
            <i class="bi bi-chat-dots"></i> Comments Management
        </h1>

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">Add New Comment</h5>
            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Post</label>

                        <select name="post_id" class="form-control" required>

                            <option value="">Select Post</option>

                            <?php foreach ($posts as $post): ?>

                                <option value="<?= $post['id'] ?>">
                                    <?= htmlspecialchars($post['titel']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Comment</label>
                        <textarea name="comment" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" name="add_comment" class="btn btn-primary">
                        Add Comment
                    </button>

                </form>

            </div>
        </div>

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">All Comments</h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-hover">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($comments as $comment): ?>

                                <tr>

                                    <td><?= $comment['id'] ?></td>

                                    <td><?= htmlspecialchars($comment['name']) ?></td>

                                    <td><?= htmlspecialchars($comment['comment']) ?></td>

                                    <td>

                                        <?php if ($comment['status'] == 'approved'): ?>

                                            <span class="badge bg-success">Approved</span>

                                        <?php elseif ($comment['status'] == 'pending'): ?>

                                            <span class="badge bg-warning">Pending</span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">Rejected</span>

                                        <?php endif; ?>

                                    </td>

                                    <td><?= $comment['created_at'] ?></td>

                                    <td>

                                        <a href="comments.php?approve=<?= $comment['id'] ?>"
                                            class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i> Approve
                                        </a>

                                        <a href="comments.php?reject=<?= $comment['id'] ?>"
                                            class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i> Reject
                                        </a>

                                        <a href="comments.php?delete=<?= $comment['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            <?php if (empty($comments)): ?>

                                <tr>
                                    <td colspan="6" class="text-center">
                                        No Comments Found
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </div>
</div>

<?php include '../Admin/include/footer.php'; ?>
