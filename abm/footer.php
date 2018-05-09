
</div>
</div>
<footer>
    <div class="navbar navbar-inverse footer">
        <div class="container-fluid">
            <div class="copyright">
                &copy; <a href="http://www.sietepuentes.com" target="blank">sietepuentes</a> - <?php echo date("Y"); ?> All rights reserved
            </div>

        </div>
    </div>
</footer>
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<?php
list($params) = sscanf($_SERVER["QUERY_STRING"], "a=%s");
$action = explode("&", $params)[0];
$script = basename($_SERVER["PHP_SELF"]);
if ($action == "agregar" || $action == "editar" || $action == "cargar" || $script == "equipos.php" || $script == "jugadores.php" || $script == "mecanica.php" || $script == "trivias.php") {
    echo '<script src="js/functions.js"></script>';
    echo '<script src="js/check-form.js"></script>';
}
?>
</body>
</html>