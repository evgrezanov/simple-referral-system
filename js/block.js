( function( blocks, element, blockEditor ) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType( 'simple-referral-system/referral-link', {
        title: 'Referral Link',
        icon: 'admin-links',
        category: 'widgets',
        edit: function( props ) {
            return el(
                'div',
                useBlockProps(),
                'Your referral link will appear here.'
            );
        },
        save: function() {
            return null; // Render in PHP
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );