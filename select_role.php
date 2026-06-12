<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['set_role'])) {
    $chosen_role = $_GET['set_role'];
    
    if ($chosen_role === 'Seller') {
        $_SESSION['role'] = 'Seller';
        header("Location: seller_dashboard.php");
        exit();
    } elseif ($chosen_role === 'Buyer') {
        $_SESSION['role'] = 'Buyer';
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Select Your Role</title>
    <link rel="stylesheet" href="./assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #052A5E; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .role-master-wrapper {
            text-align: center;
            color: #FFFFFF;
            width: 100%;
            max-width: 900px;
            padding: 20px;
        }

        .role-main-title {
            font-size: 56px; 
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .role-sub-title {
            font-size: 38px; 
            color: #D2E0FB;
            font-weight: 400;
            margin-bottom: 70px;
        }

        .role-flex-row {
            display: flex;
            justify-content: center;
            gap: 100px; 
        }

        .role-selection-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #FFFFFF;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .role-selection-link:hover {
            transform: translateY(-8px);
            filter: brightness(1.1);
        }

        .role-visual-box {
            width: 180px;
            height: 180px;
            background-color: #FFFFFF; 
            border: 15px solid #D9D9D9; 
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .role-svg-icon {
            width: 75px;
            height: 75px;
            fill: #333333;
        }

        .role-label-text {
            font-size: 34px; 
            font-weight: 500;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <div class="role-master-wrapper">
        <h1 class="role-main-title">WELCOME TO ROBOSHOP</h1>
        <p class="role-sub-title">Please select your role</p>

        <div class="role-flex-row">
            
            <a href="select_role.php?set_role=Seller" class="role-selection-link">
                <div class="role-visual-box">
                    <svg class="role-svg-icon" viewBox="0 0 24 24">
                        <path d="M20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A2,2,0,0,0,2,8V19a2,2,0,0,0,2,2H20a2,2,0,0,0,2-2V8A2,2,0,0,0,20,6V6ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10V5ZM20,19H4V13H20V19ZM20,11H4V8H20V11Z"/>
                    </svg>
                </div>
                <span class="role-label-text">Seller</span>
            </a>

            <a href="select_role.php?set_role=Buyer" class="role-selection-link">
                <div class="role-visual-box">
                    <svg class="role-svg-icon" viewBox="0 0 24 24">
                        <path d="M12,12A5,5,0,1,0,7,7,5,5,0,0,0,12,12Zm0-8A3,3,0,1,1,9,7,3,3,0,0,1,12,4Zm0,10c-4.42,0-8,2.24-8,5v2H20V19C20,16.24,16.42,14,12,14Zm6,5H6V18.39c.63-.79,2.44-1.89,6-1.89s5.37,1.1,6,1.89Z"/>
                    </svg>
                </div>
                <span class="role-label-text">Buyer</span>
            </a>

        </div>
    </div>

</body>
</html>
