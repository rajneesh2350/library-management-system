<?php
require_once '../../config/database.php';
include '../../includes/header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
        $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
        $publication_year = $_POST['publication_year'];
        $category = $_POST['category'];
        $total_copies = $_POST['total_copies'];

        // Generate book_id
        $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(book_id, 6) AS UNSIGNED)) as max_id FROM books");
        $row = mysqli_fetch_assoc($result);
        $next_id = str_pad(($row['max_id'] + 1), 5, '0', STR_PAD_LEFT);
        $book_id = "BOOK-" . $next_id;

        $available_copies = $total_copies;
        $status = $total_copies > 0 ? 'Available' : 'Maintenance';

        $query = "INSERT INTO books (book_id, title, author, isbn, publisher, publication_year, category, total_copies, available_copies, status)
                            VALUES ('$book_id', '$title', '$author', '$isbn', '$publisher', '$publication_year', '$category', '$total_copies', '$available_copies', '$status')";

        if(mysqli_query($conn, $query)) {
                $success_message = "Book created successfully! Book ID: " . $book_id;
                header("Location: index.php?success=1");
                exit();
        } else {
                $error_message = "Error: " . mysqli_error($conn);
        }
}
?>

<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Add New Book</h1>
                <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                </a>
        </div>

        <div class="card">
                <div class="card-body">
                        <form method="POST" action="">
                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Title *</label>
                                                        <input type="text" name="title" class="form-control" required>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Author *</label>
                                                        <input type="text" name="author" class="form-control" required>
                                                </div>
                                        </div>
                                </div>

                                <div class="row">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>ISBN</label>
                                                        <input type="text" name="isbn" class="form-control">
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Publisher</label>
                                                        <input type="text" name="publisher" class="form-control">
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Publication Year</label>
                                                        <input type="number" name="publication_year" class="form-control" min="1000" max="<?php echo date('Y'); ?>">
                                                </div>
                                        </div>
                                </div>

                                <div class="row">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Category *</label>
                                                        <select name="category" class="form-control" required>
                                                                <option value="Fiction">Fiction</option>
                                                                <option value="Non-Fiction">Non-Fiction</option>
                                                                <option value="Science">Science</option>
                                                                <option value="Technology">Technology</option>
                                                                <option value="History">History</option>
                                                                <option value="Biography">Biography</option>
                                                                <option value="Other">Other</option>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Total Copies *</label>
                                                        <input type="number" name="total_copies" class="form-control" min="1" value="1" required>
                                                </div>
                                        </div>
                                </div>

                                <div class="text-right">
                                        <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Create Book
                                        </button>
                                </div>
                        </form>
                </div>
        </div>
</div>

<?php include '../../includes/footer.php'; ?>