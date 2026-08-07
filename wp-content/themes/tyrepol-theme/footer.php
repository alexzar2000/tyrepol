<footer class="footer">
    <div class="footer__inner">
      <nav class="footer__links">
        <a class="footer__link" href="#">Polityka prywatności</a>
        <a class="footer__link" href="#">Polityka cookies</a>
      </nav>
      <p class="footer__copy">&copy; <?php echo date('Y'); ?> TyrePol. Wszelkie prawa zastrzeżone.</p>
    </div>
  </footer>

  <button id="scrolltop" class="scrolltop" type="button" aria-label="Przewiń do góry strony">
    <svg class="scrolltop__ring" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
    <span class="scrolltop__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"></path></svg>
    </span>
  </button>

  <?php wp_footer(); ?> 
</body>
</html>