<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
  header("Location: login.php");
  exit;
}

include 'user_notification_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['submit_read'])) {
    $notif_id = $_POST['notif_id'];
    $query = "UPDATE complaints SET seen = 1 WHERE userID = :user_id AND id = :notif_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':notif_id', $notif_id);
    $stmt->execute();
    $notifData = getAllNotificationData($conn, $user_id); // refresh data
  }

  if (isset($_POST['submit_readAll'])) {
    updateSeenStatus($conn, $user_id, "seen = 1");
    $notifData = getAllNotificationData($conn, $user_id);
  }

  if (isset($_POST['submit_unread'])) {
    $notifData = getAllNotificationData($conn, $user_id, "seen = 0");
  }

  if (isset($_POST['submit_all'])) {
    $notifData = getAllNotificationData($conn, $user_id);
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notification</title>

  <link rel="icon" type="image/x-icon" href="img/favicon.ico">

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- tailwind link -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- tabler icon link -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

</head>

<body class="bg-[#E8E8E7] h-screen w-screen flex flex-col gap-y-2 items-center justify-start">

  <section class="w-[60rem] max-w-[90%] flex justify-end mt-2">
    <a class="bg-blue-500 hover:bg-blue-400 px-3 py-2 rounded-md text-white" href="user_dashboard.php">back to dashboard</a>
  </section>

  <section class="bg-white shadow rounded-lg h-[5rem] w-[60rem] max-w-[90%] flex items-center justify-between p-5">

    <form action="" method="POST" class="m-0 p-0 flex gap-x-3">
      <input
       
        type="submit"
        value="All"
        name="submit_all"
        class="p-1 border border-gray-400">

      <input
       
        type="submit"
        value="Unread"
        name="submit_unread"
        class="p-1 border border-gray-400">
    </form>

    <form action="" method="POST" class="m-0 p-0">
      <input
      
        type="submit"
        value="Mark all as read"
        name="submit_readAll"
        class="p-1 border border-gray-400">
    </form>

  </section>

  <section class="bg-white shadow h-[30rem] w-[60rem] max-w-[90%] overflow-y-auto">
    <?php if (!empty($notifData)) { ?>
      <?php foreach ($notifData as $row) { ?>
        <div class="relative <?php echo $row['seen'] === 1 ? 'bg-white' : 'bg-blue-100' ?> h-fit w-full border p-5 flex items-center justify-between">
          <p>The case
            <span id="textToCopy<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['CNum']); ?></span>
            has lapse
            <?php echo $row['CMethod'] == 'Mediation' ? '15 days for mediation' : ($row['CMethod'] == 'Conciliation' ? '30 days for conciliation' : ''); ?>
          </p>

          <section class="flex gap-x-4 items-center">

            <i class="ti ti-copy text-2xl" onclick="copyText(<?php echo $row['id']; ?>)"></i>

            <form action="" method="POST" class="m-0 p-0">
              <input
                type="hidden"
                name="notif_id"
                value="<?php echo $row['id']; ?>">

              <input
                <?php echo $row['seen'] === 1 ? 'disabled' : '' ?>
                type="submit"
                value="read"
                name="submit_read"
                class="p-1 border border-gray-400">
            </form>

          </section>
        </div>

      <?php } ?>
    <?php } else { ?>
      <section class="h-full w-full flex flex-col items-center justify-center gap-y-2">
        <img
          src="https://lollypop.design/wp-content/uploads/2022/01/emotional-design_mascots.png"
          alt="empty image"
          class="h-[14rem]">
        <p class="text-3xl text-gray-500 font-bold">No Notification yet!</p>
      </section>
    <?php } ?>
  </section>

  <!-- for custom alert -->
  <div class="hidden bg-red-500 text-white p-4 absolute top-5 left-1/2 transform -translate-x-1/2 rounded-lg z-[1000]" id="customAlert"></div>

  <script>
    function copyText(index) {

      var text = document.getElementById("textToCopy" + index).innerText;

      navigator.clipboard.writeText(text).then(function() {

        var alertBox = document.getElementById("customAlert");
        alertBox.style.display = "block";
        alertBox.style.backgroundColor = "#34b4eb";
        document.getElementById("customAlert").innerText = 'case#' + ' ' + text + ' ' + 'copied';

        setTimeout(function() {
          alertBox.style.display = "none";
        }, 1000);

      }).catch(function(error) {
        var alertBox = document.getElementById("customAlert");
        alertBox.style.display = "block";
        alertBox.style.backgroundColor = "#f57f81";
        document.getElementById("customAlert").innerText = 'error occured';
        setTimeout(function() {
          alertBox.style.display = "none";
        }, 1000);

      });
    }
  </script>
</body>

</html>