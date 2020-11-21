<?php
@session_start();

require 'Player.php';

$player = new Player('player');
$dealer = new Player('dealer');

if (!isset($_SESSION['dealer_deck'])) {
    $_SESSION['dealer_deck'] = [];
    $_SESSION['dealer_points'] = 0;
    $_SESSION['player_deck'] = [];
    $_SESSION['player_points'] = 0;
    $_SESSION['out'] = [];
    $_SESSION['win'] = false;
    $_SESSION['status'] = 'in process..';
    $player->addCard($_SESSION['out']);
    $player->addCard($_SESSION['out']);
    $dealer->addCard($_SESSION['out']);
}

?>
<html lang="en">
<head>
    <title>BlackJack</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<table class="information">
    <tr>
<?php
if (@($_POST['hit'] or $_POST['stand']) and $_SESSION['win'] == true) {
    session_destroy();
    $_SESSION['status'] = 'in process..';
    header("Refresh: 0");
}

if ($_SESSION['player_points'] == 21 and !$_SESSION['win']) {
    while ($_SESSION['dealer_points'] < 17) {
        $dealer->addCard($_SESSION['out']);
        header("Refresh: 0");
        if ($_SESSION['dealer_points'] == 21) {
            $_SESSION['status'] = 'tie game';
            $_SESSION['win'] = 'tie';
            continue;
        } elseif($_SESSION['dealer_points'] >= 17 or $_SESSION['dealer_points'] > 21) {
            $_SESSION['status'] = 'you won';
            $_SESSION['win'] = 'player';
            continue;
        }
    }
}

if ($_SESSION['win'] == 'player') {
    echo "<td class=\"general-info\" id='win'>Outcome: <br> <em>{$_SESSION['status']}</em></td>";
} elseif ($_SESSION['win'] == 'dealer') {
    echo "<td class=\"general-info\" id='lose'>Outcome: <br> <em>{$_SESSION['status']}</em></td>";
} else {
    echo "<td class=\"general-info\" id='tie'>Outcome: <br> <em>{$_SESSION['status']}</em></td>";
}

if (isset($_POST['hit']) and !$_SESSION['win']) {
    $player->addCard($_SESSION['out']);
    header("Refresh: 0");

    if ($_SESSION['player_points'] == 21) {
        while ($_SESSION['dealer_points'] < 17) {
            $dealer->addCard($_SESSION['out']);
            header("Refresh: 0");
            if ($_SESSION['dealer_points'] == 21) {
                $_SESSION['status'] = 'tie game';
                $_SESSION['win'] = 'tie';
                continue;
            } elseif($_SESSION['dealer_points'] > 21 or $_SESSION['dealer_points'] < 21) {
                $_SESSION['status'] = 'you won';
                $_SESSION['win'] = 'player';
                continue;
            }
        }
    }
    elseif ($_SESSION['player_points'] > 21) {
        $_SESSION['status'] = 'dealer won';
        $_SESSION['win'] = 'dealer';
    }
} elseif (isset($_POST['reset'])) {
    session_destroy();
    $_SESSION['status'] = 'in process..';
    header("Refresh: 0");
} elseif (isset($_POST['stand']) and !$_SESSION['win']) {
    if ($_SESSION['player_points'] < $_SESSION['dealer_points']) {
        $_SESSION['status'] = 'dealer won';
        $_SESSION['win'] = 'dealer';
    }
    while ($_SESSION['dealer_points'] < 17) {
        $dealer->addCard($_SESSION['out']);
        header("Refresh: 0");
        if ($_SESSION['player_points'] == 21 and $_SESSION['dealer_points'] == 21) {
            $_SESSION['status'] = 'tie game';
            $_SESSION['win'] = 'tie';
            continue;
        } elseif ($_SESSION['player_points'] == $_SESSION['dealer_points']) {
            $_SESSION['status'] = 'tie game';
            $_SESSION['win'] = 'tie';
            continue;
        } elseif ($_SESSION['dealer_points'] > 21 or $_SESSION['player_points'] > $_SESSION['dealer_points']) {
            $_SESSION['status'] = 'you won';
            $_SESSION['win'] = 'player';
            continue;
        } elseif ($_SESSION['player_points'] < $_SESSION['dealer_points'] or $_SESSION['dealer_points'] == 21) {
            $_SESSION['status'] = 'dealer won';
            $_SESSION['win'] = 'dealer';
            continue;
        }
    }
}
?>
    </tr>
</table>
<table class="cards">
    <tr>
        <td><b>Dealer(<?php echo $_SESSION['dealer_points'];?>):</b></td>
        <?php
        for ($i = 0; $i < sizeof($_SESSION['dealer_deck']); $i++) {
            $folder = (sizeof($_SESSION['dealer_deck']) == 1) ? 'closeCards' : 'openCards';
            echo "<td><img src='images/$folder/{$_SESSION['dealer_deck'][$i]}.gif' height='96' width='71'></td>";
        }
        ?>
    </tr>
    <tr>
        <td><b>Your(<?php echo $_SESSION['player_points'];?>):</b></td>
        <?php
        for ($i = 0; $i < sizeof($_SESSION['player_deck']); $i++) {
            echo "<td><img src='images/openCards/{$_SESSION['player_deck'][$i]}.gif' height='96' width='71'></td>";
        }
        ?>
    </tr>
</table>
<form class="form" method="POST">
    <table class="buttons">
        <tr>
            <td><input type="submit" name="hit" value="HIT"></td>
            <td><input type="submit" name="stand" value="STAND"></td>
        </tr>
        <tr>
            <td><input type="submit" name="reset" value="RESET"></td>
        </tr>
    </table>
</form>
</body>
</html>

