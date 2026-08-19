<?php

// --- ACF field group --------------------------------------------------------

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_interventi_cover',
        'title'  => 'Foto copertina',
        'fields' => [

            [
                'key'           => 'field_interventi_copertina',
                'name'          => 'copertina',
                'label'         => 'Foto copertina',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'array',
                'instructions'  => 'Foto banner mostrata sotto il titolo, ratio 16:9.',
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'interventi'],
        ]],
        'menu_order'      => 0,
        'position'        => 'acf_after_title',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);

    acf_add_local_field_group([
        'key'    => 'group_interventi',
        'title'  => 'Dati intervento',
        'fields' => [

            [
                'key'           => 'field_interventi_anno_inizio',
                'name'          => 'anno_inizio',
                'label'         => 'Anno inizio',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 1900,
                'max'           => 2100,
                'step'          => 1,
            ],
            [
                'key'           => 'field_interventi_anno_fine',
                'name'          => 'anno_fine',
                'label'         => 'Anno fine',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 1900,
                'max'           => 2100,
                'step'          => 1,
            ],
            [
                'key'           => 'field_interventi_posizione',
                'name'          => 'posizione',
                'label'         => 'Posizione',
                'type'          => 'text',
                'required'      => 0,
            ],
            [
                'key'           => 'field_interventi_destinazione_uso',
                'name'          => 'destinazione_uso',
                'label'         => "Destinazione d'uso",
                'type'          => 'taxonomy',
                'taxonomy'      => 'destinazione_uso',
                'field_type'    => 'checkbox',
                'add_term'      => 1,
                'save_terms'    => 1,
                'load_terms'    => 1,
                'return_format' => 'object',
                'required'      => 0,
            ],
            [
                'key'           => 'field_interventi_committenza',
                'name'          => 'committenza',
                'label'         => 'Committenza',
                'type'          => 'text',
                'required'      => 0,
            ],
            [
                'key'           => 'field_interventi_team',
                'name'          => 'team',
                'label'         => 'Team',
                'type'          => 'textarea',
                'required'      => 0,
                'rows'          => 3,
                'instructions'  => 'Nomi separati da virgola.',
            ],
            [
                'key'           => 'field_interventi_budget',
                'name'          => 'budget',
                'label'         => 'Budget',
                'type'          => 'text',
                'required'      => 0,
            ],
            [
                'key'           => 'field_interventi_categoria',
                'name'          => 'categoria',
                'label'         => 'Categoria',
                'type'          => 'text',
                'required'      => 0,
                'instructions'  => 'Codice es. 2.04',
            ],
            [
                'key'              => 'field_interventi_servizi',
                'name'             => 'servizi',
                'label'            => 'Servizi correlati',
                'type'             => 'relationship',
                'required'         => 1,
                'post_type'        => ['servizi'],
                'filters'          => ['search'],
                'min'              => 1,
                'return_format'    => 'id',
                'instructions'     => 'Relazione N↔N. Il reverse (interventi di un servizio) richiede una query helper separata.',
            ],
            [
                'key'              => 'field_interventi_articoli',
                'name'             => 'articoli',
                'label'            => 'Articoli correlati',
                'type'             => 'relationship',
                'required'         => 0,
                'post_type'        => ['articoli'],
                'filters'          => ['search'],
                'return_format'    => 'id',
                'instructions'     => '',
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'interventi'],
        ]],
        'menu_order'     => 0,
        'position'       => 'normal',
        'style'          => 'default',
        'label_placement'=> 'top',
    ]);
});


// --- Metabox galleria (custom, ACF free non ha Gallery) ---------------------

add_action('add_meta_boxes', function() {
    add_meta_box(
        'sp_galleria',
        'Galleria',
        'sp_galleria_render',
        'interventi',
        'normal',
        'default'
    );
});

function sp_galleria_render(WP_Post $post) {
    wp_nonce_field('sp_galleria_save', 'sp_galleria_nonce');
    $ids = get_post_meta($post->ID, 'galleria', true);
    if (!is_array($ids)) $ids = [];
    ?>
    <div id="sp-galleria-wrap">
        <div id="sp-galleria-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
            <?php foreach ($ids as $id) :
                $src = wp_get_attachment_image_src($id, 'thumbnail');
                if ($src) : ?>
                    <div class="sp-gal-item" data-id="<?php echo esc_attr($id); ?>" style="position:relative;">
                        <img src="<?php echo esc_url($src[0]); ?>" style="width:80px;height:80px;object-fit:cover;">
                        <button type="button" class="sp-gal-remove" data-id="<?php echo esc_attr($id); ?>"
                            style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;">×</button>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
        <input type="hidden" id="sp-galleria-ids" name="sp_galleria_ids" value="<?php echo esc_attr(implode(',', $ids)); ?>">
        <button type="button" id="sp-galleria-add" class="button">Aggiungi immagini</button>
    </div>
    <script>
    (function($) {
        var frame;
        $('#sp-galleria-add').on('click', function() {
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Seleziona immagini', multiple: true });
            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var ids = $('#sp-galleria-ids').val().split(',').filter(Boolean);
                selection.each(function(attachment) {
                    var id = String(attachment.id);
                    if (ids.indexOf(id) === -1) {
                        ids.push(id);
                        var thumb = attachment.attributes.sizes && attachment.attributes.sizes.thumbnail
                            ? attachment.attributes.sizes.thumbnail.url
                            : attachment.attributes.url;
                        $('#sp-galleria-preview').append(
                            '<div class="sp-gal-item" data-id="' + id + '" style="position:relative;">' +
                            '<img src="' + thumb + '" style="width:80px;height:80px;object-fit:cover;">' +
                            '<button type="button" class="sp-gal-remove" data-id="' + id + '" style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;">×</button>' +
                            '</div>'
                        );
                    }
                });
                $('#sp-galleria-ids').val(ids.join(','));
            });
            frame.open();
        });
        $(document).on('click', '.sp-gal-remove', function() {
            var id = $(this).data('id');
            $(this).closest('.sp-gal-item').remove();
            var ids = $('#sp-galleria-ids').val().split(',').filter(function(v) { return v !== String(id); });
            $('#sp-galleria-ids').val(ids.join(','));
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post_interventi', function($post_id) {
    if (!isset($_POST['sp_galleria_nonce']) || !wp_verify_nonce($_POST['sp_galleria_nonce'], 'sp_galleria_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = isset($_POST['sp_galleria_ids']) ? sanitize_text_field($_POST['sp_galleria_ids']) : '';
    $ids = array_values(array_filter(array_map('absint', explode(',', $raw))));
    update_post_meta($post_id, 'galleria', $ids);
});
