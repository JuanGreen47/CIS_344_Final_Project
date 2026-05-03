<?php 
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';
requireRole(['buyer', 'renter']);
$db = new RealEstateDatabase();
$message = '';
$userId = (int)$_SESSION['user']['userId'];
if (isset($_GET['propertyId'])) {
    $propertyId = (int)$_GET['propertyId'];
    try {
        $db->addFavorite($userId, $propertyId);
        $message = 'Property saved to favorites!';
    } catch (Throwable $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}
$favorites = $db->getFavoritesByUser($userId);
?>
<?php include 'includes/header.php'; ?>
<2>My Favorites</2>
<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<?php if (!$favorites): ?>
    <p>You have no saved properties yet.</p>
<?php else: ?>
    <?php foreach ($favorites as $favorite): ?>
        <div class="card">
            <h3><?= htmlspecialchars($favorite['title']) ?></h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($favorite['propertyType']) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($favorite['city']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($favorite['price']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($favorite['status']) ?></p>
            <a href="property_details.php?id=<?= (int)$favorite['propertyId'] ?>">View Details</a>

        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php include 'includes/footer.php'; ?>