<?php
require 'connection.php';
require "include/custom-scrollbar.php";

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$superadmin = $stmt->fetch(PDO::FETCH_ASSOC);

function isActive($path)
{
  $currentPage = basename($_SERVER['SCRIPT_NAME']);
  return $currentPage == $path ? '!bg-blue-400 text-white' : '';
}

?>

<script>
  $(document).ready(function() {
    $("#userProfile").click(function() {
      $("#userProfileDropdown").toggle();

      if ($("#userProfileDropdown").is(":visible")) {
        $("#userProfileDropdown").css("display", "flex");
      } else {
        $("#userProfileDropdown").css("display", "none");
      }
    });
  });
</script>

<!-- <link rel="stylesheet" href="assets/css/styles.min.css" /> -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script src="assets/libs/jquery/dist/jquery.min.js"></script>
<script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sidebarmenu.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="assets/libs/simplebar/dist/simplebar.js"></script>
<script src="assets/js/dashboard.js"></script>


<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">

        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
          </svg>
        </button>

        <a href="user_dashboard.php" class="flex ms-2 md:me-24">
          <p class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
              EKPsys
          </p>
        </a>

      </div>
      <div class="flex items-center">
        <div class="flex items-center ms-3">

          <div>
            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
              <span class="sr-only">Open user menu</span>

              <img class="w-8 h-8 rounded-full" src="profile_pictures/<?php echo $superadmin['profile_picture'] ?: 'defaultpic.jpg'; ?>?t=<?php echo time(); ?>" alt="user photo">

            </button>
          </div>

          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
            <div class="px-4 py-3" role="none">
              <p class="text-sm text-gray-900 dark:text-white" role="none">
                <?php echo $superadmin['first_name'];
                echo $superadmin['last_name']; ?>
              </p>
              <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                <?php echo $superadmin['email']; ?>
              </p>
            </div>
            <ul class="py-1" role="none">

              <li>
                <a href="sa_setting.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
              </li>

              <li>
                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-44 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
  <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">

    <div class="w-full flex flex-col gap-y-1 items-center mb-3">
      <img class="w-20 h-20 rounded-full" src="profile_pictures/<?php echo $superadmin['profile_picture'] ?: 'defaultpic.jpg'; ?>?t=<?php echo time(); ?>" alt="user photo">

      <p><?php echo $superadmin['first_name']; ?> </p>
    </div>


    <ul class="font-medium">
      <li>
        <a href="sa_dashboard.php" class="<?php echo isActive('sa_dashboard.php'); ?> flex gap-x-2 items-center p-2 rounded-lg hover:bg-gray-100 group">
          <i class="ti ti-dashboard text-2xl"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li>
        <a href="sa_registeredmuni.php" class="<?php echo isActive('sa_registeredmuni.php'); ?> flex gap-x-2 items-center p-2 rounded-lg hover:bg-gray-100 group">
          <i class="ti ti-archive text-2xl"></i>
          <span>Registered Municipalities</span>
        </a>
      </li>

      <li>
        <a href="sa_reports.php" class="<?php echo isActive('sa_reports.php'); ?> flex gap-x-2 items-center p-2 rounded-lg hover:bg-gray-100 group">
          <i class="ti ti-report text-2xl"></i>
          <span>Reports</span>
        </a>
      </li>

      <hr class="my-3">

      <li>
        <a href="sa_setting.php" class="<?php echo isActive('sa_setting.php'); ?> flex gap-x-2 items-center p-2 rounded-lg hover:bg-gray-100 group">
          <i class="ti ti-settings text-2xl"></i>
          <span>Settings</span>
        </a>
      </li>

    </ul>
  </div>
</aside>