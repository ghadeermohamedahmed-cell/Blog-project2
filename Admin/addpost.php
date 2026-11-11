<?php
include '../config/database.php';
include '../Admin/include/header.php';
?>
<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-
items-center pb-2 mb-3 border-bottom">

            <h1 class="h2"><i class="bi bi-file-text"></i> Add New Post </h1>
            <a href="post.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Posts </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Create New Blog Post
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Post Title</label>
                        <input type="text" class="form-control" name="titel">
                    </div>
                    <div class="mb-3">

                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" rows="6"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="catogery_id" required>
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM caragter");
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                            foreach ($categories as $category) {
                            ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Create Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include '../Admin/include/footer.php'

?>

<?php
$message = '';
$message_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titel = $_POST["titel"];
    $content = $_POST["content"];
    $catogery_id = $_POST["catogery_id"];
    $status = $_POST["status"];
    $image = $_FILES["image"]["name"];
    $fileName = '../uploued/' . $image;
    move_uploaded_file($_FILES["image"]["tmp_name"], $fileName);
    $stmt = $pdo->prepare("INSERT INTO `post`( `titel`, `content`, `image`, `catogery_id`, `status`) VALUES (?,?,?,?,?)");
    $stmt->execute([$titel, $content, $image, $catogery_id, $status]);
    $message = "Post added successfully!";
    $message_type = "success";
}
?>