<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "send_mail";

$user = stateUser();
if(isset($user)){
  $message = Mail::find_one($_GET["id"]?$_GET["id"]:-1);
  if($message){
    if(isset($_POST["mode"]) && $_POST["mode"] == "delete"){
      $message->delete();
      printHeader($lang["readmail_del_msg001"]);

    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<?php

      print '<div class="section is-medium has-text-centered container"><h1 class="title">' . $lang["readmail_del_msg001"] . '</h1>';
      print "<a href='./index.php'>{$lang['readmail_del_back']}</a>";
      print "</div>";
      printFooter();
      exit();
    }
    $fromuser = $message->from_user();
    $touser = $message->to_user();

    printHeader($message->title);
    
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

  <div class="section">
    <table class="table">
      <tbody>
        <tr><td class="subtitle is-5"><?php echo $lang["readmail_title"]; ?></td><td class="title is-5"><?php hp($message->title); ?></td></tr>
        <tr><td class="subtitle is-5"><?php echo $lang["readmail_from"]; ?></td><td class="title is-5"><?php echo h($fromuser->name); ?></td></tr>
        <tr><td class="subtitle is-5"><?php echo $lang["readmail_to"]; ?></td><td class="title is-5"><?php echo h($touser->name); ?></td></tr>
      </tbody>
    </table>

    <hr width="60%">
    <div class="content section">
      <pre>
        <?php print($message->message); ?>
      </pre>
    </div>
  </div>
  <div class="section">
    <hr width="60%">
  <?php    if($user->id == $message->to_user_id){ ?>
    <form method="POST" action="./sendmail.php">
        <label class="label"><?php echo $lang["readmail_rep_form"]; ?></label>
        <input type="hidden" name="to_id" value="<?php echo h($fromuser->id); ?>" />

        <div class="field">
          <label class="label" for="title"><?php echo $lang["readmail_rep_title"]; ?></label>
          <input class="input" type="text" name="title" id="title" value="Re:<?php echo $message->title; ?>" size="20"/>
        </div>

        <div class="field">
          <label class="label" for="message"><?php echo $lang["readmail_rep_body"]; ?></label>
          <textarea class="textarea" name="message" id="message" rows="5" cols="50"></textarea>
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
        <input class="button is-info" type="submit" value="<?php echo $lang["readmail_rep_submit"]; ?>" />
      </div>
    </form>
<?php
    } else {
?>

    <div class="has-text-right">
      <button class="js-modal-trigger button is-danger" data-target="modal-js-example">
        <?php echo $lang["readmail_del_del"]; ?>
      </button>
    </div>

    <div id="modal-js-example" class="modal">
      <div class="modal-background"></div>
      <div class="modal-card">
        <form method="POST" class="pure-form pure-form-stacked">
          <section class="modal-card-body">
            <div class="notification is-danger is-light">
              <p><?php echo $lang["readmail_del_msg002"]; ?></p>
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
              <input type="hidden" name="mode" value="delete">
              <input class="button is-danger" type="submit" value="<?php echo $lang["readmail_del_submit"]; ?>" />
            </div>
          </section>
        </form>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
      // Functions to open and close a modal
      function openModal($el) {
        $el.classList.add('is-active');
      }

      function closeModal($el) {
        $el.classList.remove('is-active');
      }

      function closeAllModals() {
        (document.querySelectorAll('.modal') || []).forEach(($modal) => {
          closeModal($modal);
        });
      }

      // Add a click event on buttons to open a specific modal
      (document.querySelectorAll('.js-modal-trigger') || []).forEach(($trigger) => {
        const modal = $trigger.dataset.target;
        const $target = document.getElementById(modal);

        $trigger.addEventListener('click', () => {
          openModal($target);
        });
      });

      // Add a click event on various child elements to close the parent modal
      (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach(($close) => {
        const $target = $close.closest('.modal');

        $close.addEventListener('click', () => {
          closeModal($target);
        });
      });

      // Add a keyboard event to close all modals
      document.addEventListener('keydown', (event) => {
        if(event.key === "Escape") {
          closeAllModals();
        }
      });
    });
    </script>

<?php
    }
    printFooter();
  } else {
    header("Location: ./index.php");
    exit();
  }
} else {
  header("Location: ./login.php");
  exit();
}

