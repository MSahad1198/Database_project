</div><!-- end .page-body -->

<footer style="
    background: var(--navy);
    color: var(--grey-text);
    text-align: center;
    padding: 18px 40px;
    font-size: .78rem;
    letter-spacing: .04em;
    margin-top: auto;
">
    MediCore Hospital Management System &nbsp;·&nbsp; <?php echo date('Y'); ?>
    &nbsp;·&nbsp; Logged in as <strong style="color:var(--teal);"><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
</footer>

</body>
</html>