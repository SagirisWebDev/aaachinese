( function () {
    if ( dynamoPreview && dynamoPreview.initialCss && ! document.getElementById( 'dynamo-dynamic-css' ) ) {
        const styleEl = document.createElement( 'style' );
        styleEl.id = 'dynamo-dynamic-css';
        styleEl.textContent = dynamoPreview.initialCss;
        document.head.appendChild( styleEl );
    }

    const fallbackTokens = [
        'colors_primary',
        'colors_secondary',
        'colors_accent',
        'colors_background',
        'colors_text',
        'colors_link',
        'colors_section_alt',
        'spacing_header_padding_top',
        'spacing_header_padding_bottom',
        'spacing_footer_padding_top',
        'spacing_footer_padding_bottom',
        'spacing_content_padding_top',
        'spacing_content_padding_bottom',
        'spacing_content_padding_x',
        'layout_container_max_width',
        'layout_content_width',
        'layout_sidebar_width',
    ];

    let tokens;
    if ( dynamoPreview && dynamoPreview.initialCss ) {
        const matches = dynamoPreview.initialCss.match( /--dynamo-([a-z0-9]+(?:-[a-z0-9]+)*)/g ) || [];
        tokens = matches
            .map( function ( m ) { return m.replace( '--dynamo-', '' ).replace( /-/g, '_' ); } )
            .filter( function ( t, i, arr ) { return arr.indexOf( t ) === i; } );
        if ( ! tokens.length ) {
            tokens = fallbackTokens;
        }
    } else {
        tokens = fallbackTokens;
    }

    tokens.forEach( function ( token ) {
        const prop = '--dynamo-' + token.replace( /_/g, '-' );
        wp.customize( 'dynamo_' + token, function ( value ) {
            value.bind( function ( newval ) {
                document.documentElement.style.setProperty( prop, newval );
            } );
        } );
    } );
} )();
