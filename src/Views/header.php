<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records Listing</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  

    <div class="container">
    <div class="navbar">
    <ul><li><a href="/dashboard">Dashboard</a></li>
          <li><a href="/viewMyPackage">View Package Details</a></li>
          <li><a href="/showUpgradeForm">Upgrade Package</a></li></ul>
    
      <nav>
        <ul>
        <li><div style="position: relative;">
          <button class="notificationBtn" id="notification-btn">🔔 Notifications (<span id="notification-count">0</span>)</button>
          <div class="notificationDD" id="notification-dropdown" style="">
          <ul id="notification-list"></ul>
      </div>
  </div></li>
          <li><a href="/logout">Logout</a></li>
        </ul>
      </nav>
  </div>
  <div class="main">