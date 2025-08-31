<?php
//session_start();
require_once 'config.php';
include 'includes/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$type = isset($_GET['type']) && in_array($_GET['type'], ['lost', 'found']) ? $_GET['type'] : '';

$sql = "SELECT * FROM items WHERE status = 'open'";
$params = [];
$types = '';

if ($search) {
    $sql .= " AND (description LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}
if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}
if ($type) {
    $sql .= " AND type = ?";
    $params[] = $type;
    $types .= 's';
}
$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        throw new Exception("Database error");
    }
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    error_log("Search query failed: " . $e->getMessage());
    echo "<p class='text-red-600 text-center'>Error loading items. Please try again later.</p>";
    exit;
}
?>

<!-- Search Header Section -->
<div class="header-bg relative">
    <div class="container mx-auto px-4 pt-32 pb-20 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 font-raleway">
            Find Lost & Found Items
        </h1>
        <p class="text-xl text-purple-100 mb-8 max-w-2xl mx-auto">
            Search through our database of lost and found items to help reunite belongings with their owners
        </p>
    </div>
</div>

<!-- Search Section -->
<section class="py-16 bg-white -mt-10">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="card p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 font-raleway text-center">Search Filters</h2>
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Search Keywords</label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="What are you looking for?" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Category</label>
                            <select name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">All Categories</option>
                                <option value="Phone" <?php echo $category === 'Phone' ? 'selected' : ''; ?>>Phone</option>
                                <option value="Wallet/Purse" <?php echo $category === 'Wallet/Purse' ? 'selected' : ''; ?>>Wallet/Purse</option>
                                <option value="Keys" <?php echo $category === 'Keys' ? 'selected' : ''; ?>>Keys</option>
                                <option value="Bag/Backpack" <?php echo $category === 'Bag/Backpack' ? 'selected' : ''; ?>>Bag/Backpack</option>
                                <option value="ID Card" <?php echo $category === 'ID Card' ? 'selected' : ''; ?>>ID Card</option>
                                <option value="Jewelry" <?php echo $category === 'Jewelry' ? 'selected' : ''; ?>>Jewelry</option>
                                <option value="Documents" <?php echo $category === 'Documents' ? 'selected' : ''; ?>>Documents</option>
                                <option value="Other" <?php echo $category === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Item Type</label>
                            <select name="type" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">All Types</option>
                                <option value="lost" <?php echo $type === 'lost' ? 'selected' : ''; ?>>Lost</option>
                                <option value="found" <?php echo $type === 'found' ? 'selected' : ''; ?>>Found</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-medium transition flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            Search Items
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="py-16 pattern-bg">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold mb-2 font-raleway">Search Results</h2>
                <p class="text-gray-600">
                    <?php if ($search || $category || $type): ?>
                        Showing results for 
                        <?php 
                        $filters = [];
                        if ($search) $filters[] = "search: \"$search\"";
                        if ($category) $filters[] = "category: $category";
                        if ($type) $filters[] = "type: $type";
                        echo implode(', ', $filters);
                        ?>
                    <?php else: ?>
                        Browse all lost and found items
                    <?php endif; ?>
                </p>
            </div>
            <div class="mt-4 md:mt-0 text-gray-600">
                <?php echo $result->num_rows; ?> item(s) found
            </div>
        </div>
        
        <?php if ($result->num_rows === 0): ?>
            <div class="text-center py-12">
                <div class="bg-white p-8 rounded-2xl shadow-md max-w-md mx-auto">
                    <i class="fas fa-search text-4xl text-primary-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No items found</h3>
                    <p class="text-gray-600 mb-4">Try adjusting your search criteria or browse all items</p>
                    <a href="search.php" class="bg-primary-500 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Clear Filters
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card overflow-hidden">
                        <div class="item-image bg-gradient-to-r <?php echo $row['type'] == 'lost' ? 'from-purple-400 to-indigo-500' : 'from-amber-400 to-orange-500'; ?> relative">
                            <div class="category-badge <?php echo $row['type'] == 'lost' ? 'lost-badge' : 'found-badge'; ?>">
                                <?php echo strtoupper($row['type']); ?>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <?php if ($row['category'] == 'Phone'): ?>
                                    <i class="fas fa-mobile-alt text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'Wallet/Purse'): ?>
                                    <i class="fas fa-wallet text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'Keys'): ?>
                                    <i class="fas fa-key text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'Bag/Backpack'): ?>
                                    <i class="fas fa-briefcase text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'ID Card'): ?>
                                    <i class="fas fa-id-card text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'Jewelry'): ?>
                                    <i class="fas fa-gem text-white text-6xl"></i>
                                <?php elseif ($row['category'] == 'Documents'): ?>
                                    <i class="fas fa-file-alt text-white text-6xl"></i>
                                <?php else: ?>
                                    <i class="fas fa-question-circle text-white text-6xl"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-xl"><?php echo htmlspecialchars($row['category']); ?></h3>
                                    <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($row['description']); ?></p>
                                </div>
                                <span class="text-xs text-gray-500"><?php echo time_elapsed_string($row['created_at']); ?></span>
                            </div>
                            <div class="flex items-center mt-4 text-sm">
                                <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                                <span><?php echo htmlspecialchars($row['location']); ?></span>
                            </div>
                            <div class="flex items-center mt-2 text-sm">
                                <i class="fas fa-clock text-gray-500 mr-2"></i>
                                <span><?php echo date('M j, g:i A', strtotime($row['date_time'])); ?></span>
                            </div>
                            <button onclick="alert('Contact support to <?php echo $row['type'] == 'lost' ? 'notify if found' : 'claim this item'; ?>')" class="mt-6 w-full bg-<?php echo $row['type'] == 'lost' ? 'primary' : 'accent'; ?>-500 hover:bg-<?php echo $row['type'] == 'lost' ? 'primary' : 'accent'; ?>-700 text-white py-3 rounded-lg transition flex items-center justify-center">
                                <i class="fas <?php echo $row['type'] == 'lost' ? 'fa-bell' : 'fa-check-circle'; ?> mr-2"></i>
                                <?php echo $row['type'] == 'lost' ? 'Notify if Found' : 'Claim This Item'; ?>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
// Helper function to show time elapsed
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<?php include 'includes/footer.php'; ?>