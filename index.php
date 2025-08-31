<?php
require_once 'config.php';
include 'includes/header.php';

$sql = "SELECT * FROM items WHERE status = 'open' ORDER BY created_at DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Header/Navigation -->
<div class="header-bg relative">
    <nav class="container mx-auto px-4 py-6 flex justify-between items-center relative z-10">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-hands-helping text-primary-700 text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white font-raleway">Reunite<span class="text-secondary-300">Hub</span></h1>
        </div>
        
        <div class="hidden md:flex space-x-8">
            <a href="index.php" class="text-white hover:text-secondary-300 transition">Home</a>
            <a href="report.php" class="text-white hover:text-secondary-300 transition">Report</a>
            <a href="search.php" class="text-white hover:text-secondary-300 transition">Search</a>
            <a href="#" class="text-white hover:text-secondary-300 transition">Success Stories</a>
        </div>
        
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition">
                    <i class="fas fa-bell"></i>
                </button>
                <a href="logout.php" class="bg-secondary-500 hover:bg-secondary-700 text-white px-4 py-2 rounded-full transition">
                    <i class="fas fa-user mr-2"></i>
                    Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="bg-secondary-500 hover:bg-secondary-700 text-white px-4 py-2 rounded-full transition">
                    <i class="fas fa-user mr-2"></i>
                    Login
                </a>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="container mx-auto px-4 pt-16 pb-32 flex flex-col md:flex-row items-center relative z-10">
        <div class="md:w-1/2 mb-12 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 font-raleway">
                Lost Something? <br>
                <span class="text-secondary-300">Found Something?</span>
            </h1>
            <p class="text-xl text-purple-100 mb-8 max-w-lg">
                Our smart platform helps reunite lost items with their owners through community power and AI matching.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="report.php?type=found" class="bg-secondary-500 hover:bg-secondary-700 text-white px-6 py-3 rounded-full font-medium transition flex items-center pulse">
                    <i class="fas fa-search mr-2"></i>
                    Report Found Item
                </a>
                <a href="report.php?type=lost" class="bg-white text-primary-700 hover:bg-gray-100 px-6 py-3 rounded-full font-medium transition">
                    Report Lost Item
                </a>
            </div>
        </div>
        
        <div class="md:w-1/2 flex justify-center">
            <div class="relative w-full max-w-md">
                <div class="absolute -top-6 -left-6 bg-white p-4 rounded-2xl shadow-xl z-10">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-key text-secondary-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Keys Found</p>
                            <p class="text-primary-500 font-semibold text-xs">Near City Park</p>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="w-72 h-72 bg-white rounded-full flex items-center justify-center mx-auto floating">
                        <div class="w-56 h-56 bg-primary-100 rounded-full flex items-center justify-center">
                            <div class="w-40 h-40 bg-primary-300 rounded-full flex flex-col items-center justify-center text-center p-4">
                                <i class="fas fa-hands-helping text-white text-4xl mb-3"></i>
                                <p class="text-white font-bold">Reuniting People with Their Belongings</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-2xl shadow-xl z-10">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-wallet text-accent-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Wallet Found</p>
                            <p class="text-primary-500 font-semibold text-xs">Coffee Shop</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="container mx-auto px-4 -mt-24 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card p-6 text-center">
            <div class="text-3xl font-bold text-primary-700 mb-2">5,327</div>
            <div class="text-gray-600">Items Reported</div>
        </div>
        <div class="stat-card p-6 text-center">
            <div class="text-3xl font-bold text-accent-500 mb-2">3,891</div>
            <div class="text-gray-600">Items Returned</div>
        </div>
        <div class="stat-card p-6 text-center">
            <div class="text-3xl font-bold text-secondary-500 mb-2">98%</div>
            <div class="text-gray-600">Success Rate</div>
        </div>
        <div class="stat-card p-6 text-center">
            <div class="text-3xl font-bold text-primary-500 mb-2">24h</div>
            <div class="text-gray-600">Avg. Return Time</div>
        </div>
    </div>
</div>

<!-- How It Works -->
<section class="py-20 pattern-bg">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-4 font-raleway">How ReuniteHub Works</h2>
            <p class="text-gray-600">
                Our smart system makes it easy to report and find lost items through intelligent matching
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="card p-6 text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <div class="w-16 h-16 bg-primary-300 rounded-full flex items-center justify-center text-white text-2xl">
                        1
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-3">Report Item</h3>
                <p class="text-gray-600">
                    Report a lost or found item with details, location, and photos
                </p>
            </div>
            
            <div class="card p-6 text-center">
                <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <div class="w-16 h-16 bg-secondary-500 rounded-full flex items-center justify-center text-white text-2xl">
                        2
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-3">Smart Matching</h3>
                <p class="text-gray-600">
                    Our AI matches items based on category, location, and description
                </p>
            </div>
            
            <div class="card p-6 text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center text-white text-2xl">
                        3
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-3">Get Notified</h3>
                <p class="text-gray-600">
                    Receive instant notifications when a potential match is found
                </p>
            </div>
            
            <div class="card p-6 text-center">
                <div class="w-20 h-20 bg-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <div class="w-16 h-16 bg-accent-500 rounded-full flex items-center justify-center text-white text-2xl">
                        4
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-3">Reunite Safely</h3>
                <p class="text-gray-600">
                    Connect and arrange safe return with our assistance
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Items -->
<section class="py-16 pattern-bg">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold mb-2 font-raleway">Recently Reported Items</h2>
                <p class="text-gray-600">Help reunite these items with their owners</p>
            </div>
            <a href="search.php" class="mt-4 md:mt-0 bg-white hover:bg-gray-100 text-primary-700 px-6 py-3 rounded-full font-medium transition border border-primary-200">
                View All Items
            </a>
        </div>
        
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

<script>
    // Simple tab switching for report section
    document.querySelectorAll('.report-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.report-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Animation for stats cards on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-pulse');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });
    });
</script>