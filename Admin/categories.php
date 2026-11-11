<?php
include_once '../config/database.php';
include './include/header.php';
include './include/siuedpar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"> <i class="bi bi-tags"></i> Categories Management </h1>
        </div>

        <div id="messageContainer"></div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label for="categoryName" class="form-label">Category Name </label>
                        <input type="text" class="form-control" id="categoryName" name="name">
                    </div>
                    <div class="col-md-6">

                        <label for="categoryDescription" class="form-
label">Descrption</label>

                        <input type="text" class="form-control"
                            id="categoryDescription" name="descrption">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100"
                            name="add_category">
                            <i class="bi bi-plus-circle"></i> Add </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">

            <?php
            $name = '';
            $description = '';

            if (isset($_GET['edit_id'])) {
                $edit_id = $_GET['edit_id'];

                $stmt = $pdo->prepare("SELECT * FROM `caragter` WHERE id = ?");
                $stmt->execute([$edit_id]);

                $category = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($category) {
                    $name = $category['name'];
                    $description = $category['descrption'];
                }
            }

            if (isset($_POST['update_category'])) {
                
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);

                $stmt = $pdo->prepare(
                    "UPDATE `caragter` SET `name` = ?, `descrption` = ? WHERE `id` = ?"
                );

                $stmt->execute([$name, $description, $edit_id]);
                
            }
            ?>


            <div class="card-header">
                <h5 class="card-title ">Edit New Category</h5>
            </div>

            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">

                        <label for="categoryName" class="form-label">Category Name </label>

                        <input type="text" value="<?= $name; ?>" class="form-
control" id="categoryName" name="name">

                    </div>

                    <div class="col-md-6">
                        <label for="categoryDescription" class="form-label">Description</label>

                        <input type="text" value="<?= $description; ?>" class="form-
control" id="categoryDescription" name="description">

                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-warning w-100"
                            name="update_category">

                            <i class="bi bi-pencil-square"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Categories</h5>
            </div>
            <div class="card-body">

                <?php
                $stmt = $pdo->query("SELECT * FROM caragter ORDER BY id DESC");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="categoriesTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['id'] ?></td>
                                    <td><?= $category['name'] ?></td>
                                    <td><?= $category['descrption'] ?></td>
                                    <td>
                                        <a href="categories.php?edit_id=<?= $category['id'] ?>"
                                            class="btn btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        <a href="categories.php?del_id=<?= $category['id'] ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
include 'include/footer.php';
?>

<?php
$massage = '';
$massage_type = '';

if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $descrption = trim($_POST['descrption']);

    if (empty($name)) {
        $massage = 'Category name is required.';
        $massage_type = 'danger';
    } else {
        $stmt = $pdo->prepare("INSERT INTO `caragter` (`name`, `descrption`) VALUES (?, ?)");
        $stmt->execute([$name, $descrption]);

        $massage = 'Category added successfully.';
        $massage_type = 'success';
    }
}

if ($massage): ?>

    <div class="alert alert-<?= $massage_type; ?> alert-dismissible fade show" role="alert">
        <?= $massage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php endif; ?>

<?php

if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    $stmt = $pdo->prepare("DELETE FROM `caragter` WHERE id= ?");
    $stmt->execute([$del_id]);

    $message = "Category deleted successfully!";
    $message_type = "success";
}
?>