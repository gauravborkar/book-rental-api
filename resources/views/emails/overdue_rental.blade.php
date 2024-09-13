<!DOCTYPE html>
<html>
<head>
    <title>Overdue Rental Notification</title>
</head>
<body>
    <h1>Your Rental is Overdue</h1>
    <p>The following book is overdue:</p>
    <ul>
        <li><strong>Book:</strong> {{ $bookTitle }}</li>
        <li><strong>Rental Date:</strong> {{ $rentalDate }}</li>
        <li><strong>Due Date:</strong> {{ $dueDate }}</li>
    </ul>
    <p>Please return the book as soon as possible to avoid additional charges.</p>
</body>
</html>