<div class="wrap">
        <h2>Listar Cliques</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>

        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
                <thead>
                    <tr>
                        <th class="ss-th-width">Categoria</th>
                        <th class="ss-th-width">Total de Cliques</th>
                        <th class="ss-th-width">Ações</th>
                    </tr>
                </thead>


                <tbody>
                    <?php foreach (bcc_get_grouped_rows() AS $row): ?>

                        <?php
                            $path = 'admin.php?page=list_clicks_list_view&name='.$row->name;
                            $url = admin_url($path);
                        ?>
                        <tr>
                            <td class="manage-column ss-list-width">
                                <?php echo $row->name; ?>
                            </td>
                            <td class="manage-column ss-list-width">
                                <?php echo $row->total; ?>
                            </td>
                            <td class="manage-column ss-list-width">
                                <a href="<?php echo $url; ?>">
                                    Ver Cliques
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
