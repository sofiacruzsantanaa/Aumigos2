<?php
// ensure asset_href is available (defined in header include). If not, define a fallback.
if (!function_exists('asset_href')) {
    function asset_href($path) {
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $base = str_replace('\\', '/', rtrim($scriptDir, '/\\'));
        if ($base === '' || $base === '.') {
            $base = '';
        } else {
            if ($base[0] !== '/') {
                $base = '/' . ltrim($base, '/');
            }
        }
        $p = str_replace('\\', '/', $path);
        return $base . '/' . ltrim($p, '/');
    }
}

?>

<footer class="footer">
  <div class="footer-container">

    <div class="footer-box footer-brand">
      <img src="<?php echo htmlspecialchars(asset_href('assets/img/Logo.jpeg')); ?>" alt="Logo AUmas Gêmeas">
      <div>
        <h3>AUmas Gêmeas</h3>
        <p>Conectando corações e patinhas desde 2020.</p>
      </div>
    </div>

    <div class="footer-box">
      <h3>Contato</h3>
      <p>contato@aumasgemeas.com.br</p>
      <p>(11) 98765-4321</p>
    </div>

    <div class="footer-box">
      <h3>Links</h3>
      <p><a href="<?php echo htmlspecialchars(asset_href('inicio.php')); ?>">Início</a></p>
      <p><a href="<?php echo htmlspecialchars(asset_href('catalogo.php')); ?>">AUmigos</a></p>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> AUmas Gêmeas. Todos os direitos reservados.</p>
  </div>
</footer>

</body>
</html>