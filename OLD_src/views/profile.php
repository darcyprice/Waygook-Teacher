<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/Waygook-Teacher');
/* if ($_SERVER['HTTP_HOST'] == "localhost") {
  set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/Waygook-Teacher');
} else {
  set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);
} */

require_once("src/views/head.php");
require_once("src/views/header.php");

require_once("src/controllers/profile.php");
?>

<main>

  <div class="container profile-container">

    <!-- SIDEBAR -->
    <section class="section">
      <?php require_once("src/views/sidebar.php"); ?>
    </section>
    <!-- \.SIDEBAR -->

    <!-- PROFILE CONTAINER -->
    <section class="section profile">
      <div class="row">
        <div class="img-l round">
          <img
            src="<?php echo $profileUser['profile_pic']; ?>"
            alt="profile-photo">
        </div>
        <div class="intro">
          <h4><?php echo $profileUser['first_name'] . " " . $profileUser['last_name']; ?></h4>
        </div>
        <div class="btn btn-primary btn-inline">BOOK LESSON</div>
      </div>
      <div class="description">
        <h4>About Me</h4>
        <div class="content">
          <!-- nl2br ensures that the line breaks are printed -->
          <?php echo nl2br($profileUser["description"]); ?>
        </div>
      </div>
    </section>

    </section>
    <!-- \.PROFILE CONTAINER -->
  </div>

</main>

<?php
require_once("src/views/footer.php");
?>