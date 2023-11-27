<div class="wrap">
        <h2>Listar Cliques</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        
        <a class="button show-settings" href="<?php echo admin_url('admin.php?page=list_clicks_list_index'); ?>">Voltar</a>

        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
                <thead>
                    <tr>
                        <th class="ss-th-width">Clique Número</th>
                        <th class="ss-th-width">Categoria</th>
                        <th class="ss-th-width">Data</th>
                    </tr>
                </thead>


                <tbody>
                    <?php foreach (bcc_find_rows_by_name($_GET['name']) AS $index => $row): ?>
                        <tr>
                            <td class="manage-column ss-list-width">
                                <?php echo $row->position; ?>
                            </td>
                            <td class="manage-column ss-list-width">
                                <?php echo $row->name; ?>
                            </td>
                            <td class="manage-column ss-list-width">
                            <?php echo date_format(date_create($row->created_at), 'd\/m\/Y \à\s H:i:s'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
