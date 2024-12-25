<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ./profile/sign-in.html');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT firstName, lastName, email, phoneNum, zipCode FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNum = $_POST['phoneNum'];
    $zipCode = $_POST['zipCode'];

    $sql = "UPDATE users SET firstName=?, lastName=?, email=?, phoneNum=?, zipCode=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $zip_code, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        // Refresh user data
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

// Get the section from the URL
$section = isset($_GET['section']) ? $_GET['section'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<!--  

██╗░░██╗░█████╗░██╗░░░██╗░██████╗░██████╗░███████╗
██║░██╔╝██╔══██╗╚██╗░██╔╝██╔═══██╗██╔══██╗██╔════╝
█████╔╝░███████║░╚████╔╝░██║░░░██║██║░░██║█████╗░
██╔═██╗░██╔══██║░░╚██╔╝░░██║░░░██║██║░░██║██╔══╝░
██║░░██╗██║░░██║░░░██║░░░╚██████╔╝██████╔╝███████╗
╚═╝░░╚═╝╚═╝░░╚═╝░░░╚═╝░░░░╚═════╝░╚═════╝░╚══════╝
░██████╗░██╗░░░░░░█████╗░███╗░░░██╗██████╗░███████╗██╗░░░░██╗░█████╗░░░░░░██╗██╗░░░██╗
██╔═══██╗██║░░░░░██╔══██╗████╗░░██║██╔══██╗██╔════╝██║░░░░██║██╔══██╗░░░░░██║██║░░░██║
██║░░░██║██║░░░░░███████║██╔██╗░██║██████╔╝█████╗░░██║░█╗░██║███████║░░░░░██║██║░░░██║
██║░░░██║██║░░░░░██╔══██║██║╚██╗██║██╔══██╗██╔══╝░░██║███╗██║██╔══██║██░░░██║██║░░░██║
╚██████╔╝███████╗██║░░██║██║░╚████║██║░░██║███████╗╚███╔███╔╝██║░░██║╚█████╔╝╚██████╔╝
░╚═════╝░╚══════╝╚═╝░░╚═╝╚═╝░░╚═══╝╚═╝░░╚═╝╚══════╝░╚══╝╚══╝░╚═╝░░╚═╝░╚════╝░░╚═════╝░

·····························································································
:███╗░░░░░██████╗░██████╗░██████╗░███████╗██████╗░██╗░░░██╗██╗░░██╗░█████╗░██╗░░░██╗░░░░███╗:
:██╔╝░░░░██╔════╝██╔═══██╗██╔══██╗██╔════╝██╔══██╗╚██╗░██╔╝██║░██╔╝██╔══██╗╚██╗░██╔╝░░░░╚██║:
:██║░░░░░██║░░░░░██║░░░██║██║░░██║█████╗░░██████╔╝░╚████╔╝░█████╔╝░███████║░╚████╔╝░░░░░░██║:
:██║░░░░░██║░░░░░██║░░░██║██║░░██║██╔══╝░░██╔══██╗░░╚██╔╝░░██╔═██╗░██╔══██║░░╚██╔╝░░░░░░░██║:
:███╗░░░░╚██████╗╚██████╔╝██████╔╝███████╗██████╔╝░░░██║░░░██║░░██╗██║░░██║░░░██║░░░░░░░███║:
:╚══╝░░░░░╚═════╝░╚═════╝░╚═════╝░╚══════╝╚═════╝░░░░╚═╝░░░╚═╝░░╚═╝╚═╝░░╚═╝░░░╚═╝░░░░░░░╚══╝:
·····························································································
What are you doing here?! you sneaky developer...
-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://www.codebykay.com/project/pizzaway" rel="canonical">
    <title>Update Your MySLICE Rewards Profile | Pizza Way's</title>
    <meta name="description" content="Order Pizza Way's Take 'N' Bake pizza, sides &amp; desserts online and find a store near you. Enjoy scratch-made dough, fresh veggies, and mozzarella today.">
    <link rel="shortcut icon" href="../favico.png" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div id="preloader"></div>
    
    <div class="profileSuccessfullyUpdate" id="popupSuccessMessage"> 
        <p>Your account has been updated successfully!</p>
    </div>

    <header id="header">
        <div class="header">

            <!-- Toggle Button for Mobile -->
             <div class="tablet-menu">
                <button class="tablet-header-menu" id="tableToggleBtn" onclick="toggleTabletMenu()">
                    <i class='bx bx-menu'></i>
                </button>
             </div>

            <div class="tableMenu-slider" id="tabletMenu">
                <div class="tablet-menu-content">
                    <ul class="tablet-menuList">
                        <li><a href="../order/pizza-way-30th.html">Menu</a></li>
                        <li><a href="../myslice-rewards.html">Rewards</a></li>
                        <li><a href="../take-n-bake.html">Take 'N'Bake</a></li>
                        <li><a href="#">Delivery</a></li>
                        <li><a href="#">Baking Instructions</a></li>
                        <li><a href="#">Hub of Hacks</a></li>
                        <li class="remove320"><a href="#">Jack-O-Lantern Pizza</a></li>
                        <li class="remove320"><a href="#">Monkey Bread</a></li>
                        <li class="remove320"><a href="#">Calzones</a></li>
                        <li class="remove320"><a href="#">Dairy-Free Cheese</a></li>
                        <li class="remove320"><a href="#">Pizza Deals</a></li>
                        <li class="remove320"><a href="#">$7.99 Everyday Value Lineup</a></li>
                    </ul>
                </div>
            </div>
          
            <div class="logo">
                <a href="../index.html"><img src="../src/svg/PizzaWay Logo.svg" alt="PizzaWay Logo"></a>
            </div>
            
            <nav>
                <ul class="menu">
                    <li><a href="../order/pizza-way-30th.html">Menu</a></li>
                    <li><a href="../myslice-rewards.html">Rewards</a></li>
                    <li><a href="../take-n-bake.html">Take 'N' Bake</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropbtn">More <i class='bx bx-chevron-down'></i></a>
                        <div class="dropdown-content">
                            <a href="#">Delivery</a>
                            <a href="#">Baking Instructions</a>
                            <a href="#">Hub of Hacks</a>
                            <a href="#">Jack-O-Lantern Pizza</a>
                            <a href="#">Monkey Bread</a>
                            <a href="#">Calzones</a>
                            <a href="#">Dairy-Free Cheese</a>
                            <a href="#">Pizza Deals</a>
                            <a href="#">$7.99 Everyday Value Lineup</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="account-order">
            <div class="signIn dashboardSlideBtn">
                <a href="#" id="dashboardSlide">
                    <span style="display: none;">Sign In</span>
                    <div class="userIcon dashboard">
                        <i class='bx bx-user'></i>
                        <i class='bx bx-chevron-down'></i>
                        <span class="notificationCircle"></span> <!-- Small circle -->
                    </div>
                </a>
            </div>
            <div class="nav-order-details">
                <div class="order-detailsColumn">
                    <span style="display: flex; align-items: center; gap: 5px;"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-cy="dispatch-svg"><g clip-path="url(#clip0_2881_5116)"><path d="M11.62 5.52L9.75 5.05L8.05 3.35C8.05 3.35 7.98 3.3 7.95 3.28L7.57 1.76C7.46 1.31 7.06 1 6.6 1H4.38C3.92 1 3.52 1.31 3.41 1.76L3.03 3.28C3.03 3.28 2.96 3.32 2.93 3.35L1.8 4.48C1.75 4.53 1.68 4.58 1.61 4.6L0.68 4.91C0.27 5.05 0 5.43 0 5.86V8C0 8.13 0.05 8.26 0.15 8.35L0.65 8.85C0.74 8.94 0.87 9 1 9H1.03C1.15 9.85 1.88 10.5 2.75 10.5C3.62 10.5 4.35 9.85 4.47 9H7.52C7.64 9.85 8.37 10.5 9.24 10.5C10.11 10.5 10.84 9.85 10.96 9H10.99C11.12 9 11.25 8.95 11.34 8.85L11.84 8.35C11.93 8.26 11.99 8.13 11.99 8V6C11.99 5.77 11.83 5.57 11.61 5.52H11.62ZM6.61 2L6.83 2.86C6.46 2.8 5.99 2.75 5.5 2.75C5.01 2.75 4.55 2.81 4.17 2.86L4.39 2H6.61ZM2.75 9.5C2.34 9.5 2 9.16 2 8.75C2 8.34 2.34 8 2.75 8C3.16 8 3.5 8.34 3.5 8.75C3.5 9.16 3.16 9.5 2.75 9.5ZM9.25 9.5C8.84 9.5 8.5 9.16 8.5 8.75C8.5 8.34 8.84 8 9.25 8C9.66 8 10 8.34 10 8.75C10 9.16 9.66 9.5 9.25 9.5ZM11 7.79L10.81 7.98C10.52 7.4 9.93 7 9.25 7C8.57 7 7.96 7.41 7.68 8H4.33C4.05 7.41 3.45 7 2.76 7C2.07 7 1.48 7.4 1.2 7.98L1.01 7.79V5.86L1.95 5.55C2.17 5.48 2.37 5.35 2.53 5.19L3.65 4.06C3.72 3.99 3.81 3.94 3.9 3.92C4.23 3.85 4.87 3.74 5.51 3.74C6.15 3.74 6.79 3.85 7.12 3.92C7.21 3.94 7.3 3.98 7.37 4.05L9.16 5.84C9.22 5.9 9.3 5.95 9.39 5.97L11.01 6.38V7.78L11 7.79Z" fill="#DA291C"></path><path d="M7.14998 4.69C6.86998 4.41 6.47998 4.25 6.08998 4.25H5.06998C4.22998 4.25 3.44998 4.67 2.98998 5.36L2.57998 5.97C2.47998 6.12 2.46998 6.32 2.55998 6.48C2.64998 6.64 2.81998 6.74 2.99998 6.74H7.99998C8.19998 6.74 8.37998 6.62 8.45998 6.43C8.53998 6.24 8.48998 6.03 8.34999 5.89L7.13998 4.68L7.14998 4.69ZM3.95998 5.75C4.23998 5.43 4.64998 5.25 5.07998 5.25H6.09998C6.22998 5.25 6.35998 5.3 6.44998 5.4L6.79998 5.75H3.94998H3.95998Z" fill="#DA291C"></path></g><defs><clipPath id="clip0_2881_5116"><rect width="12" height="9.5" fill="white" transform="translate(0 1)"></rect></clipPath></defs></svg>
                    <p>Ordering <strong>Delivery</strong> to</p>
                </span>

                <div class="addrIdB"><span id="passAddr">Address should be pass here</span></div>
                </div>

                <a href="../order.html" class="orderBtn start-order-btn dashboard"><i class='bx bx-cart-alt' ></i> <span class="text-white opacity-20 mx-0.5">|</span> 0</a>
            </div>
        </div>         
    </header>

    <!-- Dashboard Home Page Section -->
    <aside id="userDashboard" class="user-dashboard">
        <div class="user-dashboard-headT">
            <button id="closeDashboard" class="close-dashboard">x</button>

            <h2>Hi, <span id="userName"><?php echo htmlspecialchars($user['firstName'] ?? ''); ?>!</span></h2>
        </div>

        <div class="dashboard-menu">
            <ul>
                <li><a href="?section=recentOrders">Recent Orders</a></li>
                <li><a href="?section=updateProfile">Update Profile</a></li>
                <li><a href="../order.html">Start Order</a></li>
                <li><a href="../logout.php">Sign Out</a></li>
            </ul>
        </div>

        <div class="mySliceDashboard">
            <h3>MySLICE Rewards</h3>

            <div class="dashboard-discounts">
                <span><svg width="33" height="40" viewBox="0 0 37 45" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-cy="rewards-icon-svg"><path d="M36.415 41.5098C35.8429 32.6115 35.8027 7.74184 35.8161 4.91442C35.8161 4.35072 35.8161 3.83176 35.8161 3.35754C35.8161 1.59488 34.6496 0.413805 32.7501 0.39591C28.6651 0.364593 21.246 0.843287 15.0247 4.27467C8.06149 8.09974 2.86364 14.3138 0.923947 17.4768C0.101588 18.8189 1.13848 19.7136 2.07257 20.4384C7.43578 24.4648 26.1355 38.5482 33.0719 43.5141C35.3513 45.147 36.6072 44.5251 36.415 41.5098ZM26.1936 26.214C22.7477 25.1269 17.6393 24.4245 14.6895 20.5771C12.2671 17.4096 16.6471 12.8196 21.9343 16.9667C22.3455 9.68792 28.8752 9.9474 29.6082 13.8664C30.5378 18.6355 27.6685 22.9169 26.1936 26.214Z" fill="#DA291C"></path></svg></span>

                <div class="dashboard-discounts-context">
                    <h4>$3 Off</h4>
                    <p>Enjoy $3 off one regular menu priced item <span style="font-size: 12px;">valid through 01/09/2025</span> </p>
                </div>
            </div>

            <div class="dashboard-nxt-reward">
                <div class="dashboard-nxt-reward-circle">
                    <h4>3</h4>
                </div>

                <div class="text-h4">
                    Visit until your next Reward
                </div>
            </div>
        </div>
    </aside>

    <main id="mainContent"> 
        <!-- Landing Page Section -->
        <section class="landing-page-container-wrapper wrapper">
            <div class="landing-page-container landingContent">
                <div class="headTitle">
                    <h1 class="text-60">My Account</h1>
                </div> 
            </div>
        </section>
        
        <section class="min-menu-sct">
            <div class="homeBt"><a href="../dashboard.php">Home</a></div>
            <div class="md-icon"><i class='bx bx-chevron-right'></i></div>
            <div class="pg-tite">My Account</div>
        </section>

        <div class="my-account-container">
            <div class="myaccountNav">
                <button id="recentOrdersBtn" class="active">Recent Orders</button>
                <button id="updateProfileBtn">Update Profile</button>
            </div>

            <div class="brokeLine"></div>

            <section id="recentOrdersSection" class="recent-orders">
                <span><svg width="105" height="105" viewBox="0 0 105 105" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><g clip-path="url(#clip0_3439_49244)"><path d="M80.3907 42.8203L70.2188 47.5781L49.3828 57.2578V22.9687C49.3828 22.0985 49.0371 21.2639 48.4218 20.6486C47.8064 20.0332 46.9718 19.6875 46.1016 19.6875C37.665 19.6875 29.4178 22.1892 22.403 26.8764C15.3883 31.5635 9.9209 38.2255 6.69235 46.0199C3.46379 53.8143 2.61906 62.3911 4.26496 70.6656C5.91086 78.9401 9.97347 86.5407 15.9391 92.5063C21.9046 98.4719 29.5053 102.534 37.7798 104.18C46.0543 105.826 54.631 104.982 62.4254 101.753C70.2198 98.5244 76.8818 93.0571 81.569 86.0423C86.2561 79.0275 88.7578 70.7804 88.7578 62.3437C88.7386 56.0992 87.3379 49.9363 84.6563 44.2969C84.2692 43.5517 83.6112 42.9832 82.8177 42.7086C82.0242 42.4339 81.1556 42.4739 80.3907 42.8203ZM62.5078 70.5469C62.5078 71.5203 62.2192 72.4719 61.6784 73.2813C61.1375 74.0907 60.3688 74.7216 59.4695 75.0941C58.5701 75.4666 57.5805 75.5641 56.6258 75.3742C55.671 75.1843 54.794 74.7155 54.1057 74.0272C53.4173 73.3388 52.9486 72.4618 52.7587 71.5071C52.5687 70.5523 52.6662 69.5627 53.0387 68.6634C53.4113 67.764 54.0421 66.9953 54.8515 66.4545C55.6609 65.9137 56.6125 65.625 57.586 65.625C58.8913 65.625 60.1432 66.1436 61.0663 67.0666C61.9893 67.9896 62.5078 69.2415 62.5078 70.5469ZM41.1797 72.1875C42.1532 72.1875 43.1048 72.4762 43.9142 73.017C44.7236 73.5578 45.3544 74.3265 45.7269 75.2259C46.0995 76.1252 46.1969 77.1148 46.007 78.0696C45.8171 79.0243 45.3483 79.9013 44.66 80.5897C43.9717 81.278 43.0947 81.7468 42.1399 81.9367C41.1852 82.1266 40.1955 82.0291 39.2962 81.6566C38.3968 81.2841 37.6281 80.6532 37.0873 79.8438C36.5465 79.0344 36.2578 78.0828 36.2578 77.1094C36.2578 75.804 36.7764 74.5521 37.6994 73.6291C38.6224 72.7061 39.8743 72.1875 41.1797 72.1875ZM34.6172 62.3437C33.6438 62.3437 32.6922 62.0551 31.8828 61.5143C31.0734 60.9734 30.4425 60.2047 30.07 59.3054C29.6975 58.406 29.6 57.4164 29.7899 56.4617C29.9798 55.5069 30.4486 54.6299 31.1369 53.9416C31.8253 53.2532 32.7022 52.7845 33.657 52.5946C34.6117 52.4047 35.6014 52.5021 36.5007 52.8747C37.4001 53.2472 38.1688 53.878 38.7096 54.6874C39.2504 55.4968 39.5391 56.4484 39.5391 57.4219C39.5391 58.7272 39.0205 59.9791 38.0975 60.9022C37.1745 61.8252 35.9226 62.3437 34.6172 62.3437ZM46.1016 98.4375C36.8398 98.3983 27.9476 94.8002 21.2646 88.3876C14.5817 81.9751 10.6196 73.239 10.1981 63.9867C9.77657 54.7345 12.9279 45.6742 19.0001 38.6806C25.0722 31.687 33.6005 27.2953 42.8203 26.4141V31.1719C36.7962 31.8062 31.0851 34.1728 26.3781 37.9855C21.6711 41.7981 18.17 46.8933 16.2986 52.6544C14.4272 58.4154 14.2657 64.5954 15.8335 70.4464C17.4014 76.2974 20.6315 81.5685 25.1329 85.6219C29.6342 89.6752 35.2139 92.3371 41.1966 93.2852C47.1794 94.2334 53.3087 93.4272 58.8428 90.9643C64.3768 88.5013 69.0785 84.4872 72.3786 79.4077C75.6787 74.3283 77.4359 68.4011 77.4375 62.3437C77.4648 58.8851 76.9102 55.4465 75.7969 52.1719L80.2266 50.2031C81.5822 54.1051 82.2484 58.2133 82.1953 62.3437C82.1953 67.0836 81.2617 71.7771 79.4479 76.1562C77.634 80.5353 74.9753 84.5143 71.6237 87.8659C68.2721 91.2175 64.2932 93.8761 59.9141 95.69C55.535 97.5039 50.8415 98.4375 46.1016 98.4375Z" fill="#E5E7E8"></path><path d="M101.227 24.6094C97.7718 17.2572 92.2969 11.0394 85.4411 6.68184C78.5852 2.32431 70.6313 0.0068523 62.5079 -3.77331e-06C61.7165 -0.00894967 60.9486 0.268394 60.3457 0.780923C59.7427 1.29345 59.3453 2.00663 59.2266 2.78906L52.6641 45.4453C52.5927 45.9156 52.6245 46.3958 52.7571 46.8526C52.8897 47.3094 53.12 47.7319 53.4322 48.0908C53.7443 48.4497 54.1307 48.7365 54.5647 48.9313C54.9986 49.126 55.4697 49.2241 55.9454 49.2187L57.4219 48.8906L99.586 29.0391C99.9899 28.8623 100.354 28.6059 100.657 28.2852C100.959 27.9646 101.194 27.5862 101.347 27.1727C101.5 26.7593 101.569 26.3192 101.548 25.8788C101.527 25.4383 101.418 25.0066 101.227 24.6094ZM89.5782 26.4141C86.9605 22.0202 83.3326 18.3141 78.9955 15.6034C74.6584 12.8927 69.7373 11.2556 64.6407 10.8281L65.2969 6.5625C71.1184 7.03676 76.7392 8.91039 81.6809 12.024C86.6226 15.1375 90.739 19.3988 93.6797 24.4453L89.5782 26.4141Z" fill="#E5E7E8"></path></g><defs><clipPath id="clip0_3439_49244"><rect width="105" height="105" fill="white"></rect></clipPath></defs></svg></span>

                <p class="no-orders-text">Your have no recent orders. Let's change that!</p>

                <a href="../order.html">Start A New Order</a>
            </section>

            <section class="update-profile" id="updateProfileSection">
                <div class="update-profileFlex">
                    <div class="updateInfo">
                        <h2>My Information</h2>
                        <form id="updateProfileForm" action="./update_profile.php" method="POST">
                            
                            <label for="firstNameInput">First Name<span aria-hidden="true">*</span></label>
                            <input type="text" id="firstNameInput" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
                           
                            <label for="lastNameInput">Last Name<span aria-hidden="true">*</span></label>
                            <input type="text" id="lastNameInput" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
                           
                            <label for="emailInput">Email Address<span aria-hidden="true">*</span></label>
                            <input type="email" id="emailInput" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                            <label for="zipCode">Phone Number<span aria-hidden="true">*</span></label>
                            <input disabled type="text" id="phoneNum" name="phoneNum" value="<?php echo htmlspecialchars($user['phoneNum']); ?>">
                            
                            <label for="zipCode">Zip Code<span aria-hidden="true">*</span></label>
                            <input type="text" id="zipCode" name="zipCode" value="<?php echo htmlspecialchars($user['zipCode']); ?>" required>
                        
                            <button type="submit" name="update_profile">Save Changes</button>
                        </form>

                        <div class="deleteAccount">Click <a href="#">here</a> for more support or to delete your account.</div>
                    </div>
    
                    <div class="resetPassword">
                        <h2>Change Password</h2>
                        <form id="updateProfileForm" action="./update_profile.php" method="POST">

                            <label for="passwordInput">New Password<span aria-hidden="true">*</span></label>
                            <input type="password" id="passwordInput" name="password">
                            
                            <label for="confirmPasswordInput">Confirm New Password<span aria-hidden="true">*</span></label>
                            <input type="password" id="confirmPasswordInput" name="confirmPassword">
                            
                            <button type="submit">Set New Password</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>    
    
    <!-- Top Footer Section -->
    <div class="top-footer">
        <div class="footer-menu-logo">
            <div class="logo">
                <a href="/"> <img src="../src/svg/PizzaWay-Footer-Logo.svg" alt="PizzaWay Logo"></a>
            </div>
    
            <ul class="footer-menu">
                <li><a href="#">FAQ & HELP</a></li>
                <li><a href="#">Gift Cards</a></li>
                <li><a href="#">Nutrition</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Franchise</a></li>
            </ul>
        </div>

        <button class="chatBox" type="button"><svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 9.92417V20.5H10.015C8.06419 20.4991 6.15622 19.9276 4.52612 18.8558C2.89603 17.7841 1.61502 16.259 0.840861 14.4683C0.0667064 12.6776 -0.166776 10.6996 0.169172 8.77794C0.50512 6.85624 1.39583 5.07477 2.73158 3.65295C4.06733 2.23114 5.7898 1.23108 7.68681 0.775949C9.58383 0.320821 11.5725 0.430504 13.408 1.09149C15.2435 1.75247 16.8455 2.93589 18.0168 4.49598C19.1881 6.05606 19.8775 7.92468 20 9.87167V9.92417ZM10.8334 6.33334H5.83336V8H10.8334V6.33334ZM14.1667 9.66667H5.83336V11.3333H14.1667V9.66667ZM14.1667 13H5.83336V14.6667H14.1667V13Z" fill="currentColor"></path>
          </svg> Chat
        </button>
    </div>
    <!-- Top Footer Section Ended Here! -->
    <footer>
        <!-- Main Footer Section Start -->
        <div class="inner-footer">
            <div class="faq-location">
                <div class="menu-list hide">
                    <ul>
                        <li><a href="#">Location Directory</a></li>
                        <li><a href="#">Fundraising</a></li>
                        <li><a href="#">Canada</a></li>
                        <li><a href="#">United Arab Emirates</a></li>
                    </ul>
                </div>
    
                <div class="menu-list">
                    <ul>
                        <li><a href="#">Franchise</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Do Not Sell My Info</a></li>
                    </ul>
                </div>
            </div>

            <div class="group-class">
                <div class="menu-list">
                    <h2 class="download-app">Get Our Mobile App</h2>
                    <ul class="apple-store">
                        <li><a href="#">
                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.22 1.08C14.1592 2.21506 13.7311 3.29959 13 4.17C12.2722 5.04453 11.239 5.60941 10.11 5.75C10.0394 5.75712 9.96812 5.74913 9.90085 5.72657C9.83359 5.70401 9.7719 5.6674 9.71987 5.61916C9.66785 5.57092 9.62669 5.51216 9.59913 5.44679C9.57156 5.38141 9.55822 5.31092 9.56 5.24C9.63618 4.12993 10.0754 3.07574 10.81 2.24C11.5495 1.40381 12.532 0.819881 13.62 0.57C13.6943 0.554762 13.7712 0.556643 13.8447 0.575501C13.9182 0.594359 13.9864 0.6297 14.0442 0.678845C14.1021 0.72799 14.1479 0.78965 14.1784 0.859153C14.2088 0.928657 14.2231 1.00418 14.22 1.08ZM16.0911 11.125C15.7542 11.7969 15.5792 12.5384 15.58 13.29C15.5784 14.1619 15.8163 15.0175 16.2677 15.7635C16.7191 16.5094 17.3668 17.117 18.14 17.52C18.245 17.5774 18.3259 17.6705 18.3681 17.7824C18.4103 17.8943 18.411 18.0176 18.37 18.13C18.0247 19.0729 17.5645 19.9696 17 20.8C16.08 22.12 15.07 23.44 13.61 23.47C12.896 23.4847 12.4188 23.2793 11.9222 23.0656C11.4033 22.8424 10.8633 22.61 10.01 22.61C9.12661 22.61 8.58179 22.8451 8.0514 23.0739C7.57912 23.2776 7.11827 23.4765 6.44 23.5C5 23.55 3.91 22.06 3 20.74C1.18 18 -0.250002 13.1 1.67 9.76001C2.11747 8.94805 2.76864 8.26657 3.55942 7.78263C4.3502 7.2987 5.25334 7.02898 6.18 7.00001C6.96486 7.00001 7.74971 7.3143 8.43943 7.59049C8.98 7.80695 9.46212 8.00001 9.84 8.00001C10.1757 8.00001 10.6257 7.82324 11.1502 7.61723C11.9692 7.2955 12.9697 6.90247 14 7.00001C14.681 7.03215 15.3489 7.19894 15.9651 7.49073C16.5813 7.78252 17.1336 8.19353 17.59 8.70001C17.6342 8.75122 17.6675 8.81085 17.688 8.8753C17.7084 8.93975 17.7156 9.00769 17.7091 9.07499C17.7025 9.1423 17.6824 9.20758 17.6499 9.26688C17.6174 9.32618 17.5732 9.37828 17.52 9.42001C16.9173 9.86914 16.4279 10.453 16.0911 11.125Z" fill="currentColor"></path>
                            </svg> App Store</a>
                        </li>
    
                        <li class="google-store"><a href="#"> 
                            <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 18C4 18.55 4.45 19 5 19H6V22.5C6 23.33 6.67 24 7.5 24C8.33 24 9 23.33 9 22.5V19H11V22.5C11 23.33 11.67 24 12.5 24C13.33 24 14 23.33 14 22.5V19H15C15.55 19 16 18.55 16 18V8H4V18ZM1.5 8C0.67 8 0 8.67 0 9.5V16.5C0 17.33 0.67 18 1.5 18C2.33 18 3 17.33 3 16.5V9.5C3 8.67 2.33 8 1.5 8ZM18.5 8C17.67 8 17 8.67 17 9.5V16.5C17 17.33 17.67 18 18.5 18C19.33 18 20 17.33 20 16.5V9.5C20 8.67 19.33 8 18.5 8ZM13.53 2.16L14.83 0.86C15.03 0.66 15.03 0.35 14.83 0.15C14.63 -0.05 14.32 -0.05 14.12 0.15L12.64 1.63C11.85 1.23 10.95 1 10 1C9.04 1 8.14 1.23 7.34 1.63L5.85 0.15C5.65 -0.05 5.34 -0.05 5.14 0.15C4.94 0.35 4.94 0.66 5.14 0.86L6.45 2.17C4.97 3.26 4 5.01 4 7H16C16 5.01 15.03 3.25 13.53 2.16ZM8 5H7V4H8V5ZM13 5H12V4H13V5Z" fill="currentColor"></path>
                            </svg> Google Play Store</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="social-icons">
                
                <a class="icons" href="#"><i class='bx bxl-facebook'></i></a>

                <a class="icons" href="#"><i class='bx bxl-twitter' ></i></a>
                
                <a class="icons" href="#"><i class='bx bxl-instagram' ></i></a>
                
                <a class="icons" href="#"><i class='bx bxl-pinterest-alt' ></i></a>
                
            </div>
        </div>
        <p class="copyright">
            © Pizza Way’s International 2024 All Rights Reserved <span>| Design & Built by @Codebykay</span>
        </p>
    </footer>
    <!-- Footer Menu Container  -->
    <div class="start-ordering">
        <a href="../order.html" class="start-ordering-btn">Start Order</a>
    </div>

    <div class="footer-menu-container">
        <div class="menu-btn">
            <div class="menus">
                <a href="../order/pizza-way-30th.html" class="menu-footer-icons">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34.2 34.2" style="enable-background:new 0 0 34.2 34.2;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#DA291C;}
                        </style>
                        <path class="st0" d="M17.1,34.2C7.7,34.2,0,26.5,0,17.1S7.7,0,17.1,0s17.1,7.7,17.1,17.1l0,0C34.2,26.6,26.5,34.2,17.1,34.2z
                             M17.1,1.2C8.3,1.2,1.2,8.3,1.3,17.1c0,8.8,7.1,15.9,15.9,15.8c8.7,0,15.8-7.1,15.8-15.8C33,8.3,25.9,1.2,17.1,1.2L17.1,1.2z
                             M18.9,30.5c-2.2,0-4-1.8-4-4s1.8-4,4-4s4,1.8,4,4l0,0C22.9,28.7,21.1,30.5,18.9,30.5z M18.9,23.9c-1.4,0-2.6,1.2-2.6,2.6
                            s1.2,2.6,2.6,2.6s2.6-1.2,2.6-2.6l0,0C21.5,25,20.4,23.9,18.9,23.9z M22.3,13.1c-2.2,0-4-1.8-4-4s1.8-4,4-4s4,1.8,4,4l0,0
                            C26.3,11.3,24.5,13.1,22.3,13.1z M22.3,6.5c-1.4,0-2.6,1.2-2.6,2.6s1.2,2.6,2.6,2.6s2.6-1.2,2.6-2.6l0,0C24.9,7.7,23.7,6.5,22.3,6.5
                            z M9.9,15.2c-2.2,0-4-1.8-4-4s1.8-4,4-4s4,1.8,4,4S12.1,15.2,9.9,15.2L9.9,15.2z M9.9,8.6c-1.4,0-2.6,1.1-2.6,2.6
                            c0,1.4,1.1,2.6,2.6,2.6c1.4,0,2.6-1.1,2.6-2.6v-0.1C12.5,9.7,11.3,8.6,9.9,8.6L9.9,8.6z M17.5,20c-0.7,0-1.4-0.3-1.9-0.9
                            c-0.7,0.2-1.5-0.1-1.9-0.8c-0.4-0.8-0.4-1.7,0-2.5c0.4-0.8,1-1.5,1.8-1.9s1.7-0.5,2.6-0.2c0.9,0.2,1.6,0.8,2,1.6
                            c0.3,0.7,0.1,1.4-0.5,1.9c0.2,1.1-0.3,2.1-1.3,2.6C18.1,20,17.8,20,17.5,20z M16.2,17.6l0.3,0.4c0.2,0.4,0.9,0.8,1.2,0.6
                            c0.4-0.3,0.6-0.9,0.5-1.4L18,16.7l0.4-0.3c0.2-0.1,0.3-0.3,0.4-0.5c-0.2-0.4-0.6-0.8-1.1-0.9c-0.6-0.1-1.1-0.1-1.6,0.2
                            c-0.5,0.2-0.9,0.7-1.1,1.2c-0.2,0.4-0.2,1,0,1.4c0.3,0.1,0.5,0.1,0.8,0L16.2,17.6z M8.3,25.5H8.1c-1-0.2-1.9-1-2.1-2.1
                            c-0.7-0.3-1.1-1-1-1.7c0.3-1.8,2-3.1,3.9-2.8c0,0,0,0,0.1,0c1.8,0.1,3.2,1.7,3.1,3.6l0,0c-0.2,0.8-0.8,1.3-1.6,1.4
                            C10.1,24.9,9.3,25.5,8.3,25.5z M8.4,20.3c-1-0.1-1.9,0.6-2,1.6c0,0.1,0.3,0.3,0.5,0.4l0.5,0.2V23c0,0.5,0.4,1,0.9,1.1
                            c0.4,0,0.9-0.4,1-0.9l0.1-0.5h0.5c0.3,0,0.6-0.1,0.8-0.3c0-1.1-0.8-2-1.9-2.1C8.6,20.4,8.5,20.4,8.4,20.3z M25.7,22.6
                            c-0.3,0-0.6-0.1-0.9-0.2c-0.9-0.6-1.3-1.7-1-2.8c-0.6-0.5-0.8-1.3-0.4-1.9l0,0c0.5-0.7,1.2-1.3,2-1.4c1.8-0.4,3.7,0.7,4.3,2.4
                            c0.3,0.8,0.2,1.8-0.2,2.5c-0.4,0.6-1.2,0.9-1.9,0.6C27.1,22.4,26.5,22.6,25.7,22.6z M24.6,18.4c0.1,0.2,0.2,0.3,0.4,0.5l0.5,0.3
                            l-0.2,0.5c-0.2,0.5-0.1,1.1,0.3,1.5c0.3,0.2,1.1-0.1,1.4-0.5l0.3-0.5l0.5,0.2c0.2,0.1,0.4,0.1,0.6,0.1c0.2-0.4,0.2-0.9,0.1-1.4
                            c-0.2-0.5-0.6-1-1.1-1.2c-0.5-0.3-1.1-0.4-1.6-0.3C25.2,17.7,24.8,18,24.6,18.4L24.6,18.4z M24,18.1l0.6,0.3l0,0L24,18.1z"></path>
                    </svg>
                </a>
                <span>Menu</span>
            </div>

            <div class="menus">
                <a href="../myslice-rewards.html" class="menu-footer-icons">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.14 32.47"><defs><style>.cls-1{fill:#da291c;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M17,32.47a2.15,2.15,0,0,1-1.74-1C15.1,31.3,3,13.54.61,9.07c-1.35-2.5-.17-3.8.66-4.35a31.84,31.84,0,0,1,14-4.67c5.55-.4,10.89,1.68,13.59,3h0a3.6,3.6,0,0,1,1.88,5l-.47,1c-.67,1.43-5.48,10.66-8.66,16.77-1.52,2.91-2.67,5.13-2.85,5.48a2.07,2.07,0,0,1-1.68,1.23ZM16.68,1.64c-.42,0-.85,0-1.28.05A30.07,30.07,0,0,0,2.18,6.08c-.68.46-.72,1.1-.12,2.21,2.38,4.4,14.4,22.08,14.52,22.26s.28.31.39.28.23-.13.33-.32c.18-.36,1.34-2.59,2.86-5.51,3.18-6.1,8-15.31,8.63-16.7l.47-1a2,2,0,0,0-1.09-2.79h0A28.36,28.36,0,0,0,16.68,1.64Zm-.6,18.79L15.56,20c-.62-.52-6.12-5.22-6.85-8.43a3.78,3.78,0,0,1,2.45-4.49,4.72,4.72,0,0,1,4.93,1.27A4.69,4.69,0,0,1,21.15,7a4,4,0,0,1,2.47,4.7c-.79,3.2-6.38,7.8-7,8.31Zm-3.47-12a2.81,2.81,0,0,0-.89.15,2.15,2.15,0,0,0-1.41,2.58c.47,2.05,4,5.48,5.79,7.1,1.86-1.59,5.42-4.95,5.92-7h0a2.33,2.33,0,0,0-1.45-2.77c-1-.38-2.53-.17-3.81,1.55L16.1,11l-.66-.88A3.58,3.58,0,0,0,12.61,8.46Z"></path></g></g></svg>
                </a>
                <span>Rewards</span>
            </div>

            <div class="menus">
                <a href="../take-n-bake.html" class="menu-footer-icons">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.84 28.54"><defs><style>.cls-1{fill:#da291c;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M25.67,28.54H4.17A4.17,4.17,0,0,1,0,24.37V4.17A4.17,4.17,0,0,1,4.17,0h21.5a4.17,4.17,0,0,1,4.17,4.17v20.2A4.17,4.17,0,0,1,25.67,28.54ZM4.17,1.64A2.54,2.54,0,0,0,1.64,4.17v20.2a2.53,2.53,0,0,0,2.53,2.52h21.5a2.52,2.52,0,0,0,2.52-2.52V4.17a2.53,2.53,0,0,0-2.52-2.53Z"></path><path class="cls-1" d="M22.6,23.75H7A1.74,1.74,0,0,1,5.28,22V14.56A1.74,1.74,0,0,1,7,12.83H22.6a1.74,1.74,0,0,1,1.73,1.73V22A1.74,1.74,0,0,1,22.6,23.75ZM7,14.47a.1.1,0,0,0-.1.09V22a.1.1,0,0,0,.1.09H22.6a.09.09,0,0,0,.09-.09V14.56a.09.09,0,0,0-.09-.09Z"></path><path class="cls-1" d="M22.84,10.6H6.91A.83.83,0,0,1,6.91,9H22.84a.83.83,0,0,1,0,1.65Z"></path><circle class="cls-1" cx="7.67" cy="5.92" r="1.2"></circle><circle class="cls-1" cx="14.92" cy="5.92" r="1.2"></circle><circle class="cls-1" cx="22.03" cy="5.92" r="1.2"></circle></g></g></svg>
                </a>
                <span>Take 'N' Bake</span>
            </div>

            <div class="menus">
                <button class="menu-footer-icons" id="menuToggleBtn" onclick="toggleSlideMenu()">
                    <i class='bx bx-menu'></i>
                </button>
                <span>More</span>
            </div>
        </div>
        
        <!-- Slide Menu Content -->
        <div class="slide-menu" id="slideMenu">
            <div class="slide-menu-content">
                <ul class="menu-lists">
                    <li><a href="../order/pizza-way-30th.html">Menu</a></li>
                    <li><a href="../myslice-rewards.html">Rewards</a></li>
                    <li><a href="../take-n-bake.html">Take 'N'Bake</a></li>
                    <li><a href="#">Delivery</a></li>
                    <li><a href="#">Baking Instructions</a></li>
                    <li><a href="#">Hub of Hacks</a></li>
                    <li class="remove320"><a href="#">Jack-O-Lantern Pizza</a></li>
                    <li class="remove320"><a href="#">Monkey Bread</a></li>
                    <li class="remove320"><a href="#">Calzones</a></li>
                    <li class="remove320"><a href="#">Dairy-Free Cheese</a></li>
                    <li class="remove320"><a href="#">Pizza Deals</a></li>
                    <li class="remove320"><a href="#">$7.99 Everyday Value Lineup</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Main Footer Section Ended Here! -->
    <script>
        var loader = document.getElementById("preloader");

        window.addEventListener("load", function(){
            loader.style.display = "none";
        })

        // Check if 'error' query parameter exists
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');

        // If the error parameter is set, show the error message
        if (error === "1") {
            document.getElementById('invalidErrorMessage').style.display = 'block';
        }

         // JavaScript Code
         document.addEventListener('DOMContentLoaded', function () {
            const recentOrdersBtn = document.getElementById('recentOrdersBtn');
            const updateProfileBtn = document.getElementById('updateProfileBtn');
            const recentOrdersSection = document.getElementById('recentOrdersSection');
            const updateProfileSection = document.getElementById('updateProfileSection');
        
            recentOrdersBtn.addEventListener('click', function () {
                recentOrdersSection.style.display = 'block';
                updateProfileSection.style.display = 'none';
                recentOrdersBtn.classList.add('active');
                updateProfileBtn.classList.remove('active');
            });
        
            updateProfileBtn.addEventListener('click', function () {
                recentOrdersSection.style.display = 'none';
                updateProfileSection.style.display = 'block';
                recentOrdersBtn.classList.remove('active');
                updateProfileBtn.classList.add('active');
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
                const dashboardSlideBtn = document.getElementById('dashboardSlide');
                const userDashboard = document.getElementById('userDashboard');
                const closeDashboard = document.getElementById('closeDashboard');
                
                dashboardSlideBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    userDashboard.classList.add('active');
                });
        
                closeDashboard.addEventListener('click', function() {
                    userDashboard.classList.remove('active');
                });
        
                // Close the dashboard when clicking outside of it
                document.addEventListener('click', function(event) {
                    if (!userDashboard.contains(event.target) && !dashboardSlideBtn.contains(event.target)) {
                        userDashboard.classList.remove('active');
                    }
                });
            });
        
            document.addEventListener("DOMContentLoaded", function() {
            const dashboardSlideBtn = document.querySelector(".dashboardSlideBtn");
            const mainContent = document.getElementById("mainContent");
            const navOrderDetails = document.querySelector(".nav-order-details");
            const closeDashboard = document.getElementById("closeDashboard");
        
            dashboardSlideBtn.addEventListener("click", function() {
                navOrderDetails.classList.add("slide-in"); // Assuming you have this class for the slide effect
                mainContent.classList.add("opacity-low");
            });
        
            closeDashboard.addEventListener("click", function() {
                navOrderDetails.classList.remove("slide-in");
                mainContent.classList.remove("opacity-low");
            });
        });
        
         window.onload = function() {
            // Check if the success flag is present in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');

            if (success === "1") {
                const popup = document.getElementById('popupSuccessMessage');
                popup.classList.add('show');  // Add the 'show' class to display the pop-up

                // Hide the pop-up after 5 seconds
                setTimeout(function() {
                    popup.classList.remove('show'); // Remove the 'show' class to hide the pop-up
                }, 5000); // 5000 milliseconds (5 seconds)
            }
        };
    </script>
    <script src="../main.js"></script>
</body>
</html>