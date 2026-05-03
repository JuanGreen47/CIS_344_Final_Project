<?php
require_once __DIR__ . '/Database.php';

class RealEstateDatabase {
private PDO $conn;

public function __construct() {
    $database = new Database();
    $this->conn = $database->connect();
}

public function addUser( string $userName, string $contactInfo, string $passwordHash, string $userType): bool {
$sql = "INSERT INTO users (userName, contactInfo, passwordHash, userType)
        VALUES (:userName, :contactInfo, :passwordHash, :userType)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userName' => $userName,
            ':contactInfo' => $contactInfo,
            ':passwordHash' => $passwordHash,
            ':userType' => $userType
        ]);


}


public function getUserByUsername(string $userName) {
$sql = "SELECT * FROM users WHERE userName = :userName LIMIT 1";
$stmt = $this->conn->prepare($sql);
$stmt->execute ([':userName' => $userName]);
return $stmt->fetch();
}



public function addProperty(string $title, string $propertyType, string $address, string $city, float $price, string $status, int $agentId): bool {

$sql = "INSERT INTO properties (title, propertyType, address, city, price, status, agentId)
        VALUES (:title, :propertyType, :address, :city, :price, :status, :agentId)";

$stmt = $this->conn->prepare($sql);

return $stmt->execute([
    ':title'  => $title,
    ':propertyType' => $propertyType,
    ':address' => $address,
    ':city' => $city,
    ':price' => $price,
    ':status' => $status,
    ':agentId' => $agentId



]);
}

public function getAllProperties(): array {
    $sql = "SELECT * FROM PropertyListingView ORDER BY propertyId DESC";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll();

}

public function getPropertyById(int $propertyId) {

$sql = "SELECT p.*, u.userName AS agentName
        FROM properties p
        JOIN users u ON p.agentId = u.userId 
        WHERE p.propertyId = :propertyId";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':propertyId' => $propertyId]);
    return $stmt->fetch();

}


public function addInquiry(int $userId, int $propertyId, string $message): bool {
    $sql = "INSERT INTO inquiries (userId, propertyId, message, inquiryDate)
            VALUES (:userId, :propertyId, :message, NOW())";

$stmt = $this->conn->prepare($sql);
return $stmt->execute([
    ':userId' => $userId,
    ':propertyId' => $propertyId,
    ':message' => $message
]);
}


public function getUserDetails(int $userId) {
    $user =$this->conn->prepare ("SELECT * FROM users WHERE userId = :userId");
    $user->execute([':userId' => $userId]);
    $userDetails = $user->fetch();

    
    $inquiries = $this->conn->prepare("SELECT * FROM inquiries WHERE userId = :userId");
    $inquiries->execute([':userId' =>$userId]);
    $userDetails['inquiries'] = $inquiries->fetchAll();

    $favorites = $this->conn->prepare("SELECT * FROM favorites WHERE userId = :userId");
    $favorites->execute([':userId' => $userId]);
    $userDetails['favorites'] = $favorites->fetchAll();

    return $userDetails;

}

public function getPropertiesByCity(string $city): array {

$sql = "SELECT * FROM PropertyListingView WHERE city = :city ORDER BY propertyId DESC";
$stmt = $this->conn->prepare($sql);
$stmt->execute([':city' => $city]);
return $stmt->fetchAll();

}

public function addFavorite(int $userId, int $propertyId): bool {
    $sql = "INSERT INTO Favorites (userId, propertyId, savedDate)
            VALUES (:userId, :propertyId, NOW())";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':userId'      => $userId,
        ':propertyId'  => $propertyId
    ]);
}

public function getFavoritesByUser (int $userId): array {
    $sql = "SELECT f.*, p.title, p.propertyType, p.City, p.price, p.status
        FROM Favorites f
        JOIN Properties p ON f.propertyId = p.propertyId
        WHERE f.userId = :userId
        ORDER BY f.savedDate DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll();
}
}



?>