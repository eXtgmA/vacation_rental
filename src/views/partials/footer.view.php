</body>
</html>
<?php
// unset possible messages
if(isset($_SESSION['old_POST'])){
    unset($_SESSION["old_POST"]);
}
if(isset($_SESSION['message'])){
    unset($_SESSION['message']);
}
?>
