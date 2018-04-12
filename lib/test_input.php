<?PHP

// Secure check user input for script injections!
function    test_input( $data ) {
    $data = trim( $data );
    $data = stripslashes( $data );
    $data = htmlspecialchars( $data) ;
    return $data;
}

// Print to Console
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
?>