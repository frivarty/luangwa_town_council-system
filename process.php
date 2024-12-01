



<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check for explicit logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Check for user inactivity (20 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 20 * 60)) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Update last activity timestamp
$_SESSION['last_activity'] = time();

class ClubManager {
    private $pdo;

    public function registerUser ($username, $password, $role,$nrc,$name = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role,nrc,name) VALUES (?, ?, ?,?,?)");
            $stmt->execute([$username, $hashedPassword, $role,$nrc,$name]);
            echo "User '{$username}' registered successfully.\n";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    public function authenticateUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
    

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO ("mysql:host={$host};dbname={$dbname}", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function registerClub($clubName,$status,$ward) {
        $stmt = $this->pdo->prepare("INSERT INTO clubs (club_name, status, ward, date_registered) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$clubName, $status,$ward]);
        echo "Club '{$clubName}' registered successfully.\n";
    }

    public function registerPerson($memberId, $personName, $clubName) {
        $clubId = $this->getClubId($clubName);
    
        if (!$clubId) {
            echo "Club '{$clubName}' does not exist.\n";
            return;
        }
    
        // Check if the member ID already exists
        $stmt = $this->pdo->prepare("SELECT * FROM members WHERE member_id = ?");
        $stmt->execute([$memberId]);
        $existingMember = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingMember) {
            echo "Member ID '{$memberId}' is already registered with '{$existingMember['club_name']}'.\n";
            return;
        }
    
        // Attempt to insert a new record
        try {
            $stmt = $this->pdo->prepare("INSERT INTO members (member_id, person_name, club_id) VALUES (?, ?, ?)");
            $stmt->execute([$memberId, $personName, $clubId]);
    
            // Log the change in the audit_log table
            $this->logAudit("INSERT", "members", $this->pdo->lastInsertId());
    
            echo "Person '{$personName}' with Member ID '{$memberId}' registered successfully to '{$clubName}'.\n";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    private function logAudit($action, $tableName, $recordId) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO audit_log (action, table_name, record_id) VALUES (?, ?, ?)");
            $stmt->execute([$action, $tableName, $recordId]);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    

    public function getAllClubsAndMembers() {
        $stmt = $this->pdo->query("SELECT clubs.club_name, members.person_name FROM clubs LEFT JOIN members ON clubs.club_id = members.club_id");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

        return $result;
    }

    public function searchPerson($personName) {
        $stmt = $this->pdo->prepare("SELECT clubs.club_name FROM members JOIN clubs ON members.club_id = clubs.club_id WHERE members.person_name = ? LIMIT 1");
        $stmt->execute([$personName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return "Person '{$personName}' belongs to club '{$result['club_name']}'.";
        } else {
            return "Person '{$personName}' not found in any club.";
        }
    }

    private function getClubId($clubName) {
        $stmt = $this->pdo->prepare("SELECT club_id FROM clubs WHERE club_name = ? LIMIT 1");
        $stmt->execute([$clubName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result['club_id'] : null;
    }
// Process the form data
    public function processForm() {
        if (isset($_POST['submitClub'])) {
            $clubName = $_POST['clubName'];
             $status = isset($_POST['status']) ? $_POST['status'] : '';
             $ward = $_POST['ward'];
            $this->registerClub($clubName, $status,$ward);
        } elseif (isset($_POST['submitPerson'])) {
            $memberId = $_POST['memberId'];
            $personName = $_POST['personName'];
            $clubName = $_POST['clubName'];
            $this->registerPerson($memberId, $personName, $clubName);
        }
    }

    
}

// Example usage
$host = "localhost";
$dbname = "club";
$username = "root";
$password = "";

$clubManager = new ClubManager($host, $dbname, $username, $password);

// Process the form data
$clubManager->processForm();



// ... (existing code remains unchanged)

if (isset($_POST['registerUser'])) {
    $name = $_POST['name'];
    $nrc = $_POST['nrc'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = isset($_POST['isAdmin']) ? 'admin' : 'user';
    $clubManager->registerUser($username, $password, $role,$nrc,$name);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $clubManager->authenticateUser($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

   // Store login success message and timestamp in the session
   $_SESSION['login_success_message'] = "Login successful.";
   $_SESSION['login_success_timestamp'] = time();

   // Redirect to the dashboard page
   header("Location: dashboard.php");
   exit();
    
    } else {
        echo "Login failed. Invalid credentials.\n";
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
     // Store logout success message and timestamp in the session
     $_SESSION['logout_success_message'] = "Logout successful.";
     $_SESSION['logout_success_timestamp'] = time();
 
     // Redirect to the index page
     header("Location: index.php");
     exit();
}




?>




   






