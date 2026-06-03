<?php
require_once '../../config/database.php';
include '../../includes/header.php';

$books_query = "SELECT * FROM books ORDER BY created_at DESC";
$books = mysqli_query($conn, $books_query);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Books Management</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Book
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Total Copies</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = mysqli_fetch_assoc($books)): ?>
                        <tr>
                            <td><?php echo $book['book_id']; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo $book['category']; ?></td>
                            <td><?php echo $book['total_copies']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $book['available_copies'] > 0 ? 'success' : 'danger'; ?>">
                                    <?php echo $book['available_copies']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php
                                    echo $book['status'] == 'Available' ? 'success' :
                                        ($book['status'] == 'Checked Out' ? 'warning' : 'secondary');
                                ?>">
                                    <?php echo $book['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('delete.php?id=<?php echo $book['id']; ?>', '<?php echo $book['title']; ?>')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>