<?php ob_start(); ?>

<?php require_once BCC_ROOTDIR . 'partials/styles-css.html'; ?>
<?php require_once BCC_ROOTDIR . 'partials/hook-js.html'; ?>

<p>
    <button
        type="button"
        class="btn-bcc"
        name="bcc-click"
        data-click="primeira-versao"
        data-admin="<?php echo admin_url('admin-ajax.php'); ?>"
    >
        Clique Aqui e capte
    </button>
</p>
