-- Create database
CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

-- Table: library_members
CREATE TABLE IF NOT EXISTS library_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    full_name VARCHAR(200),
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    membership_type ENUM('Standard', 'Premium', 'VIP') DEFAULT 'Standard',
    membership_start_date DATE NOT NULL,
    membership_end_date DATE,
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_member_id (member_id),
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- Table: books
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    publisher VARCHAR(255),
    publication_year INT,
    category ENUM('Fiction', 'Non-Fiction', 'Science', 'Technology', 'History', 'Biography', 'Other') DEFAULT 'Other',
    total_copies INT NOT NULL DEFAULT 1,
    available_copies INT NOT NULL DEFAULT 0,
    status ENUM('Available', 'Checked Out', 'Maintenance') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_book_id (book_id),
    INDEX idx_title (title),
    INDEX idx_author (author),
    INDEX idx_status (status),
    INDEX idx_category (category)
);

-- Table: transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(50) UNIQUE NOT NULL,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    transaction_type ENUM('Issue', 'Return') NOT NULL,
    transaction_date DATE NOT NULL,
    due_date DATE,
    return_date DATE,
    status ENUM('Issued', 'Returned', 'Overdue') DEFAULT 'Issued',
    fine_amount DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES library_members(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_member (member_id),
    INDEX idx_book (book_id),
    INDEX idx_status (status),
    INDEX idx_transaction_date (transaction_date)
);

-- Insert sample data
INSERT INTO library_members (member_id, first_name, last_name, email, phone, membership_type, membership_start_date, status) VALUES
('LIB-MEM-00001', 'John', 'Doe', 'john.doe@example.com', '+1234567890', 'Premium', CURDATE(), 'Active'),
('LIB-MEM-00002', 'Jane', 'Smith', 'jane.smith@example.com', '+1234567891', 'Standard', CURDATE(), 'Active'),
('LIB-MEM-00003', 'Robert', 'Johnson', 'robert.j@example.com', '+1234567892', 'VIP', CURDATE(), 'Active');

INSERT INTO books (book_id, title, author, isbn, category, total_copies, available_copies, status) VALUES
('BOOK-00001', 'The Great Gatsby', 'F. Scott Fitzgerald', '978-0-7432-7356-5', 'Fiction', 5, 5, 'Available'),
('BOOK-00002', '1984', 'George Orwell', '978-0-452-28423-4', 'Fiction', 3, 3, 'Available'),
('BOOK-00003', 'Sapiens', 'Yuval Noah Harari', '978-0-06-231609-7', 'Non-Fiction', 2, 2, 'Available'),
('BOOK-00004', 'A Brief History of Time', 'Stephen Hawking', '978-0-553-38016-3', 'Science', 1, 1, 'Available'),
('BOOK-00005', 'Clean Code', 'Robert C. Martin', '978-0-13-235088-4', 'Technology', 4, 4, 'Available');

-- Trigger to update book available_copies when transaction is created
DELIMITER //
CREATE TRIGGER update_book_copies_after_transaction
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    IF NEW.transaction_type = 'Issue' THEN
        UPDATE books
        SET available_copies = available_copies - 1,
            status = IF(available_copies - 1 <= 0, 'Checked Out', 'Available')
        WHERE id = NEW.book_id;
    ELSEIF NEW.transaction_type = 'Return' THEN
        UPDATE books
        SET available_copies = available_copies + 1,
            status = 'Available'
        WHERE id = NEW.book_id;
    END IF;
END//
DELIMITER ;