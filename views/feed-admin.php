<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
    <input class="widefat"
           id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
           type="text"
           value="<?php echo esc_attr( $title ); ?>"
    />
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'default_region' ); ?>"><?php _e( 'Default region', 'text_domain' ); ?></label>
    <select name="<?php echo $this->get_field_name( 'default_region' ); ?>"
            id="<?php echo $this->get_field_id( 'default_region' ); ?>" class="widefat">

        <?php foreach ($regions as $key => $name): ?>
            <option value="<?php echo $key; ?>" <?php selected( $default_region, $key, true ) ?>>
                <?php echo $name; ?>
            </option>
        <?php endforeach; ?>
    </select>

</p>

<p>
    <input id="<?php echo esc_attr( $this->get_field_id( 'display_title' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'display_title' ) ); ?>"
           type="checkbox"
           value="1"
        <?php checked( '1', $display_title ); ?>
    />
    <label for="<?php echo esc_attr( $this->get_field_id( 'display_title' ) ); ?>"><?php _e( 'Display title', 'text_domain' ); ?></label>
</p>
