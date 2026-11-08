    </main>
    <footer class="pie">
        <div class="container">
            <p>Mansión Umbría · Proyecto DAWlloween · 2.º DAW</p>
            <button type="button" id="arana" aria-label="Araña decorativa">🕷️</button>
            <p id="secreto" hidden></p>
        </div>
    </footer>
    <script src="vendor/bootstrap.bundle.min.js"></script>
    <script src="js/comun.js"></script>
<?php
if (!empty($scripts) && is_array($scripts)) {
    foreach ($scripts as $src) {
        echo '    <script src="' . h($src) . '"></script>' . "\n";
    }
}
?>
</body>
</html>
