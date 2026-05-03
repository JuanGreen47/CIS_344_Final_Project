<?php include 'includes/header.php'; ?>
<h2>Welcome</h2>
<p>Welcome to the Real Estate Agency Portal for Spring 2026.</p>
<p>Browse our listings or register to submit inquiries and save your favorite properties</p>
<div class="card">
    <h3>Project Roles</h3>
    <ul>
        <li>Agent: add and manage listings</li>
        <li>Buyer: browse properties and submit inquiries</li>
        <li>Renter: browse properties and submit inquiries</li>
    </ul>
</div>

<?php if (!isset($_SESSION['user'])): ?>
    <div class="card">
        <a href="register.php">Register Now</a>
        <a href="login.php">Login</a>
    </div>
    <?php endif; ?>
    <?php include 'includes/footer.php'; ?>