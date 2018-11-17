<?php session_start();

class Header {
/**
 * @param $page Page
 */
function __construct($page) {
    $this->page = $page;
    if ($page->settings->header_show_totals) {
        $t = $page->settings->table;
        $t_bans = $t['bans'];
        $t_mutes = $t['mutes'];
        $t_warnings = $t['warnings'];
        $t_kicks = $t['kicks'];
        try {
            $st = $page->conn->query("SELECT
            (SELECT COUNT(*) FROM $t_bans),
            (SELECT COUNT(*) FROM $t_mutes),
            (SELECT COUNT(*) FROM $t_warnings),
            (SELECT COUNT(*) FROM $t_kicks)");
            ($row = $st->fetch(PDO::FETCH_NUM)) or die('Failed to fetch row counts.');
            $st->closeCursor();
            $this->count = array(
                'bans.php'     => $row[0],
                'mutes.php'    => $row[1],
                'warnings.php' => $row[2],
                'kicks.php'    => $row[3],
            );
        } catch (PDOException $ex) {
            Settings::handle_error($page->settings, $ex);
        }
    }
}

function navbar($links) {
    echo '<ul class="navbar-nav mr-auto">';
    foreach ($links as $page => $title) {
        $li = "li";
        $class = "nav-item";
        if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $class .= " active";
        }
        $li .= " class=\"$class\"";
        if ($this->page->settings->header_show_totals && isset($this->count[$page])) {
            $title .= " <span class=\"badge\">";
            $title .= $this->count[$page];
            $title .= "</span>";
        }
        echo "<$li><a class=\"nav-link\" href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}




function print_header() {
$settings = $this->page->settings;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- COMMON TAGS -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Search Engine -->
<meta name="description" content="<?php echo $settings->meta_description; ?>">
<meta name="image" content="<?php echo $settings->meta_image; ?>">
<meta name="author" content="LiteBans">

<!-- Schema.org for Google -->
<meta itemprop="name" content="LiteBans Material Design Theme (Multiple Themes Included)">
<meta name="og:description" content="<?php echo $settings->meta_description; ?>">
<meta name="og:image" content="<?php echo $settings->meta_image; ?>">

<!-- Open Graph general (Facebook, Pinterest & Google+) -->
<meta name="og:description" content="<?php echo $settings->meta_title; ?>">
<meta name="og:image" content="<?php echo $settings->meta_image; ?>">
<meta name="og:site_name" content="<?php echo $settings->meta_title; ?>">
<meta name="og:type" content="website">
    
    
<?php
$themeurl = "inc/css/" . $settings->default_theme . ".css";

if (isset($_POST['theme']))
	{
	$_SESSION['theme'] = strtolower($_POST['theme']);
	}

if (isset($_SESSION['theme']))
	{
	$theme = $_SESSION['theme'];
	}

if (!empty($theme))
	{
	$themeurl = "inc/css/" . $theme . ".css";
	}
  else
	{
	$themeurl = "inc/css/" . $settings->default_theme . ".css";
	} ?>

    <!-- CSS -->
    
<link href="<?php echo $this->page->autoversion('inc/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->page->autoversion('inc/css/mdb.min.css'); ?>" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo $settings->favico_image; ?>">
<link href="<?php echo $this->page->autoversion($themeurl); ?>" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.3/js/all.js"></script>
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/tippy.js@1.2.1/dist/tippy.css">


    
    
    <script type="text/javascript">
        function withjQuery(f) {
            if (window.jQuery) f();
            else window.setTimeout(function () {
                withjQuery(f);
            }, 100);
        }
    </script>

<script>
$(document).ready(function () {
    var interval = 5000;   //number of mili seconds between each call
    var refresh = function() {
   $.getJSON("https://use.gameapis.net/mc/query/players/<?php echo $settings->server_ip ?>",function(json){
          if (json.status !== true) {
         
        } else {
            // success
            $(".player-count").html(json.players.online);
            setTimeout(function(){ $('.player-count').removeClass('zoomIn').addClass('zoomOut') }, 14350); 
            setTimeout(function(){ $('.player-count').removeClass('zoomOut').addClass('zoomIn') }, 0);
        }
    });
    setTimeout(function() {
        refresh();
            },
        interval);
            }
        refresh();
});
</script>

</head>
<?php if ($settings->show_navigation) : ?>
<header role="banner">
    <div class="container">
        <nav class="navbar navbar-expand-sm navbar-dark aqua-gradient">
            <a class="navbar-brand" href="<?php echo $settings->name_link; ?>">
                <?php echo $settings->name; ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#litebans-navbar"
                    aria-controls="litebans-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="litebans-navbar">
            <?php
            $this->navbar(array(
                "index.php"    => "<i class=\"fas fa-home\" style=\"padding-right:5px;\"></i>".$this->page->t("title.index"),
                "bans.php"     => "<i class=\"fas fa-ban\" style=\"padding-right:5px;\"></i>".$this->page->t("title.bans"),
                "mutes.php"    => "<i class=\"fas fa-comment\" style=\"padding-right:5px;\"></i>".$this->page->t("title.mutes"),
                "warnings.php" => "<i class=\"fas fa-gavel\" style=\"padding-right:5px;\"></i>".$this->page->t("title.warnings"),
                "kicks.php"    => "<i class=\"fas fa-suitcase\" style=\"padding-right:5px;\"></i>".$this->page->t("title.kicks"),
            ));
            ?>
  <ul class="navbar-nav ml-auto">
          <li class="nav-item">
        <?php $this->page->print_theme_changer(); ?>
    </li>
      <?php if ($settings->show_credits) : ?>
    <div class="dropdown">
  <button class="btn navdrop dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php echo $this->page->t("credits") ?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="https://github.com/darbyjack/litebans-material-design"><?php echo $this->page->t("github") ?></a>
    <a class="dropdown-item" href="https://www.spigotmc.org/resources/litebans.3715/"><?php echo $this->page->t("litebans") ?></a>
    <a class="dropdown-item" href="https://glaremasters.me/"><?php echo $this->page->t("glare") ?></a>
      
      <?php if ($settings->display_version) : ?>
      <?php $version = file_get_contents("https://glaremasters.me/api/litebans-version.php"); ?>
    <?php if ($settings->version == $version) : ?>
      <a class="dropdown-item"><?php echo $this->page->t("version") ?><b><?php echo $this->page->t("version_latest") ?></b></a>
      <?php else : ?>
      <a class="dropdown-item" href="https://www.spigotmc.org/resources/litebans-material-design-theme-multiple-themes-included.46648/"><?php echo $this->page->t("click_for_latest_version") ?></a>
      <?php endif; ?>
      <?php else : ?>
      <?php endif; ?>
      
  </div>
      </div>
      <?php else : ?>
      <?php endif; ?>
  </ul>
            </div>
        </nav>
    </div>
</header>
<br><br>
<?php else : ?>
<?php endif; ?>
<?php
}
}
?>
