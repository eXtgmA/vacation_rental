    <hr>
    <div style="display: flex;justify-content: center">
        <span>© <?php echo date("Y"); ?> Ferienverwaltung GmbH - </span>
        <a href="/impressum">Impressum</a>
    </div>
</body>
</html>
<?php
// unset possible old data sourced from array $_POST
if (isset($_SESSION['old_POST'])) {
    unset($_SESSION["old_POST"]);
}
// unset possible messages
unset($_SESSION['message']);
?>
